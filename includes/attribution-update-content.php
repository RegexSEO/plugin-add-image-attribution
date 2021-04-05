<?php

function image_attribution_update_content($content)
{
    $htmlContent = new DOMDocument();
    libxml_use_internal_errors(true);
    $htmlContent->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

    $images = $htmlContent->getElementsByTagName('img');
    foreach ($images as $imageEl) {

        $imageSrc = $imageEl->getAttribute('src');

        // remove dimensions from image
        if (preg_match('/-(\d{1,4})x(\d{1,4})\.([a-z]{3,})$/i', $imageSrc)) {
            $imageExtension = pathinfo($imageSrc, PATHINFO_EXTENSION);
            $originalImageSrc = preg_replace('/-(\d{1,4})x(\d{1,4})\.([a-z]{3,})$/i', '', $imageSrc) . "." . $imageExtension;
        } else {
            $originalImageSrc = $imageSrc;
        }

        // get id from original image url
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $originalImageSrc));
        $imageId = $attachment[0];

        // create attribution string elements
        $imageTitle = get_the_title($imageId);
        $originalSite = get_post_meta($imageId, '_custom_media_original_site', true);
        $authorName = get_post_meta($imageId, '_custom_media_author_name', true);
        $authorLink = get_post_meta($imageId, '_custom_media_author_link', true);
        $licenseName = get_post_meta($imageId, '_custom_media_license_name', true);
        $licenseLink = get_post_meta($imageId, '_custom_media_license_link', true);

        if ($authorName == "" && $authorLink == "" && $licenseName == "" && $licenseLink == "") {
            continue;
        }

        /** String Example: Debt Financing by Cytonn Photography | CC BY-SA 3.0  */
        if ($originalSite != "" && $imageTitle != "") {
            $imageTitleEl = $htmlContent->createElement('a', $imageTitle);
            $imageTitleEl->setAttribute('href', $originalSite);
            $imageTitleEl->setAttribute('target', '_blank');
            $imageTitleEl->setAttribute('rel', 'nofollow');
        } else if ($originalSite != "") {
            $imageTitleEl = $htmlContent->createElement('a', 'Photo');
            $imageTitleEl->setAttribute('href', $originalSite);
            $imageTitleEl->setAttribute('target', '_blank');
            $imageTitleEl->setAttribute('rel', 'nofollow');
        } else if ($imageTitle != "") {
            $imageTitleEl = $htmlContent->createTextNode($imageTitle);
        } else {
            $imageTitleEl = $htmlContent->createTextNode("Photo");
        }

        if ($authorName != "" && $authorLink != "") {
            $authorNameEl = $htmlContent->createElement('a', $authorName);
            $authorNameEl->setAttribute('href', $authorLink);
            $authorNameEl->setAttribute('target', '_blank');
            $authorNameEl->setAttribute('rel', 'nofollow');
        } else if ($authorName != "") {
            $authorNameEl = $htmlContent->createTextNode($authorName);
        }

        if ($licenseName != "" && $licenseLink != "") {
            $licenseNameEl = $htmlContent->createElement('a', $licenseName);
            $licenseNameEl->setAttribute('href', $licenseLink);
            $licenseNameEl->setAttribute('target', '_blank');
            $licenseNameEl->setAttribute('rel', 'nofollow');
        } else if ($licenseName != "") {
            $licenseNameEl = $htmlContent->createTextNode($licenseName);
        }


        // create and append attribution element
        $attributionEl = $htmlContent->createElement('small');
        $attributionEl->appendChild($imageTitleEl);

        if ($authorNameEl) {
            $tempEl = $htmlContent->createTextNode(' by ');
            $attributionEl->appendChild($tempEl);
            $attributionEl->appendChild($authorNameEl);
        }

        if ($licenseNameEl) {
            $tempEl = $htmlContent->createTextNode(' | ');
            $attributionEl->appendChild($tempEl);
            $attributionEl->appendChild($licenseNameEl);
        }

        $attributionElParent = $htmlContent->createElement('div');
        $attributionElParent->appendChild($attributionEl);

        try {
            $imageEl->parentNode->insertBefore($attributionElParent, $imageEl->nextSibling);
        } catch (\Exception $e) {
            $imageEl->parentNode->appendChild($attributionElParent);
        }
    }

    $content = $htmlContent->saveHTML();
    libxml_clear_errors();

    return $content;
}

add_filter('the_content', 'image_attribution_update_content', 6);
