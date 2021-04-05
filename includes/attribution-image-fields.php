<?php

// Add Custom Fields to Attachment
function image_attribution_add_custom_media_fields($form_fields, $post)
{
    // Author Name
    $field_value = get_post_meta($post->ID, '_custom_media_author_name', true);
    $form_fields['custom_media_author_name'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __('Author Name'),
        'input'  => 'text'
    );

    // Author Link
    $field_value = get_post_meta($post->ID, '_custom_media_author_link', true);
    $form_fields['custom_media_author_link'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __('Author Link'),
        'input'  => 'text'
    );

    // Original Site
    $field_value = get_post_meta($post->ID, '_custom_media_original_site', true);
    $form_fields['custom_media_original_site'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __('Original Site'),
        'input'  => 'text'
    );

    // License Name
    $field_value = get_post_meta($post->ID, '_custom_media_license_name', true);
    $form_fields['custom_media_license_name'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __('License Name'),
        'input'  => 'text'
    );

    // License Link
    $field_value = get_post_meta($post->ID, '_custom_media_license_link', true);
    $form_fields['custom_media_license_link'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __('License Link'),
        'input'  => 'text'
    );

    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'image_attribution_add_custom_media_fields', null, 2);

// Save Custom Fields from Attachment
function image_attribution_save_custom_media_fields($attachment_id)
{
    // Author Name
    if (isset($_REQUEST['attachments'][$attachment_id]['custom_media_author_name'])) {
        $custom_media_author_name = $_REQUEST['attachments'][$attachment_id]['custom_media_author_name'];
        update_post_meta($attachment_id, '_custom_media_author_name', $custom_media_author_name);
    }

    // Author Link
    if (isset($_REQUEST['attachments'][$attachment_id]['custom_media_author_link'])) {
        $custom_media_author_link = $_REQUEST['attachments'][$attachment_id]['custom_media_author_link'];
        update_post_meta($attachment_id, '_custom_media_author_link', $custom_media_author_link);
    }

    // Original Site
    if (isset($_REQUEST['attachments'][$attachment_id]['custom_media_original_site'])) {
        $custom_media_original_site = $_REQUEST['attachments'][$attachment_id]['custom_media_original_site'];
        update_post_meta($attachment_id, '_custom_media_original_site', $custom_media_original_site);
    }

    // License Name
    if (isset($_REQUEST['attachments'][$attachment_id]['custom_media_license_name'])) {
        $custom_media_license_name = $_REQUEST['attachments'][$attachment_id]['custom_media_license_name'];
        update_post_meta($attachment_id, '_custom_media_license_name', $custom_media_license_name);
    }

    // License Link
    if (isset($_REQUEST['attachments'][$attachment_id]['custom_media_license_link'])) {
        $custom_media_license_link = $_REQUEST['attachments'][$attachment_id]['custom_media_license_link'];
        update_post_meta($attachment_id, '_custom_media_license_link', $custom_media_license_link);
    }
}

add_action('edit_attachment', 'image_attribution_save_custom_media_fields');
