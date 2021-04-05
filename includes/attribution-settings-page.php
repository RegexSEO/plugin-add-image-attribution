<?php

// Register Settings
function image_attribution_register_settings()
{
    add_option('image_attribution_css', '');
    register_setting('image_attribution_options_group', 'image_attribution_css', 'myplugin_callback');
}
add_action('admin_init', 'image_attribution_register_settings');


// Register Settings Page
function image_attribution_register_settings_page()
{
    add_options_page('Image Attribution Settings', 'Image Attribution Settings', 'manage_options', 'image_attribution_settings', 'image_attribution_settings_content');
}
add_action('admin_menu', 'image_attribution_register_settings_page');


// Settins Page Content
function image_attribution_settings_content()
{
?>
    <div>
        <?php screen_icon(); ?>
        <h2>Image Attribution Settings</h2>
        <style>
            .image-attribution-form textarea {
                width: 100%;
                max-width: 500px;
                display: block;
            }
        </style>
        <form method="post" action="options.php" class="image-attribution-form">
            <?php settings_fields('image_attribution_options_group'); ?>
            <div class="form-control">
                <label>Edit Attribution Styles</label>
                <textarea type="text" id="image_attribution_css" name="image_attribution_css" rows="10"><?= get_option('image_attribution_css'); ?></textarea>
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
} ?>