<?php
/*
Plugin Name: Custom Topbar
Plugin URI: https://www.trinibuy.co.uk
Description: A simple plugin to add a topbar to your WordPress site.
Version: 1.0
Author: Hassen Ben Hadj Hassen
Author URI: https://trinibuy.co.uk
License: GPL2
*/

// Define constants
define('CUSTOM_TOPBAR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CUSTOM_TOPBAR_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Topbar display function
function add_custom_topbar() {
    $options = get_option('custom_topbar_options');
    $background_color = isset($options['background_color']) ? $options['background_color'] : '#333';
    $text_color = isset($options['text_color']) ? $options['text_color'] : '#fff';
    $text_font = isset($options['text_font']) ? $options['text_font'] : 'Arial, sans-serif';
    $font_size = isset($options['font_size']) ? $options['font_size'] : '16';
    $font_size_unit = isset($options['font_size_unit']) ? $options['font_size_unit'] : 'px';
    $height = isset($options['height']) ? $options['height'] : '40';
    $height_unit = isset($options['height_unit']) ? $options['height_unit'] : 'px';
    $position = isset($options['position']) ? $options['position'] : 'relative';
    $content_text = isset($options['content_text']) ? $options['content_text'] : 'This is a topbar!';
    $custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';

    ?>
    <style>
        .custom-topbar {
            background-color: <?php echo esc_attr($background_color); ?>;
            color: <?php echo esc_attr($text_color); ?>;
            font-family: <?php echo esc_attr($text_font); ?>;
            font-size: <?php echo esc_attr($font_size . $font_size_unit); ?>;
            text-align: center;
            padding: 0;
            height: <?php echo esc_attr($height . $height_unit); ?>;
            position: <?php echo esc_attr($position); ?>;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
        }
        <?php if (is_admin_bar_showing() && $position === 'fixed') : ?>
            .custom-topbar {
                top: 32px; /* Adjust for admin bar height */
            }
        <?php endif; ?>
        body {
            padding-top: 0; /* No padding to accommodate the topbar */
        }
        <?php echo $custom_css; ?>
    </style>
    <div class="custom-topbar">
        <?php echo esc_html($content_text); ?>
    </div>
    <?php
}
add_action('wp_head', 'add_custom_topbar');

// Admin menu function
function custom_topbar_add_admin_menu() {
    add_options_page(
        'Custom Topbar Settings', // Page title
        'Custom Topbar',          // Menu title
        'manage_options',         // Capability required
        'custom-topbar',          // Menu slug
        'custom_topbar_settings_page' // Callback function to render the settings page
    );
}
add_action('admin_menu', 'custom_topbar_add_admin_menu');

// Register settings and fields
function custom_topbar_settings_init() {
    // Register a new setting for "custom-topbar" page
    register_setting('custom_topbar_group', 'custom_topbar_options');

    // Register a new section in the "custom-topbar" page
    add_settings_section(
        'custom_topbar_section',
        __('Topbar Settings', 'custom-topbar'),
        'custom_topbar_section_callback',
        'custom-topbar'
    );

    // Add settings fields
    add_settings_field('position', __('Position', 'custom-topbar'), 'custom_topbar_position_render', 'custom-topbar', 'custom_topbar_section');
    add_settings_field('background_color', __('Background Color', 'custom-topbar'), 'custom_topbar_background_color_render', 'custom-topbar', 'custom_topbar_section');
    add_settings_field('text_color', __('Text Color', 'custom-topbar'), 'custom_topbar_text_color_render', 'custom-topbar', 'custom_topbar_section');
    add_settings_field('text_font', __('Text Font', 'custom-topbar'), 'custom_topbar_text_font_render', 'custom-topbar', 'custom_topbar_section');
    add_settings_field('font_size', __('Font Size', 'custom-topbar'), 'custom_topbar_font_size_render', 'custom-topbar', 'custom_topbar_section');
    add_settings_field('height', __('Height', 'custom-topbar'), 'custom_topbar_height_render', 'custom-topbar', 'custom_topbar_section');
    add_settings_field('content_text', __('Content Text', 'custom-topbar'), 'custom_topbar_content_text_render', 'custom-topbar', 'custom_topbar_section');
    add_settings_field('custom_css', __('Custom CSS', 'custom-topbar'), 'custom_topbar_custom_css_render', 'custom-topbar', 'custom_topbar_section');
}
add_action('admin_init', 'custom_topbar_settings_init');

function custom_topbar_section_callback() {
    echo '<p>' . __('Customize the appearance and content of your topbar.', 'custom-topbar') . '</p>';
}

// Render functions for each field
function custom_topbar_position_render() {
    $options = get_option('custom_topbar_options');
    ?>
    <select name="custom_topbar_options[position]">
        <option value="relative" <?php selected($options['position'], 'relative'); ?>>Standard (Scrolls with the Page)</option>
        <option value="fixed" <?php selected($options['position'], 'fixed'); ?>>Fixed</option>
    </select>
    <?php
}

function custom_topbar_background_color_render() {
    $options = get_option('custom_topbar_options');
    ?>
    <input type="text" name="custom_topbar_options[background_color]" value="<?php echo isset($options['background_color']) ? esc_attr($options['background_color']) : '#333'; ?>" class="my-color-field">
    <?php
}

function custom_topbar_text_color_render() {
    $options = get_option('custom_topbar_options');
    ?>
    <input type="text" name="custom_topbar_options[text_color]" value="<?php echo isset($options['text_color']) ? esc_attr($options['text_color']) : '#fff'; ?>" class="my-color-field">
    <?php
}

function custom_topbar_text_font_render() {
    $options = get_option('custom_topbar_options');
    $fonts = ['Arial, sans-serif', 'Helvetica, sans-serif', 'Georgia, serif', 'Times New Roman, serif', 'Courier New, monospace']; // Example list
    ?>
    <select name="custom_topbar_options[text_font]">
        <?php foreach ($fonts as $font): ?>
            <option value="<?php echo esc_attr($font); ?>" <?php selected($options['text_font'], $font); ?>><?php echo esc_html($font); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
}

function custom_topbar_font_size_render() {
    $options = get_option('custom_topbar_options');
    ?>
    <input type="number" name="custom_topbar_options[font_size]" value="<?php echo isset($options['font_size']) ? esc_attr($options['font_size']) : '16'; ?>" min="1">
    <select name="custom_topbar_options[font_size_unit]">
        <option value="px" <?php selected($options['font_size_unit'], 'px'); ?>>px</option>
        <option value="em" <?php selected($options['font_size_unit'], 'em'); ?>>em</option>
        <option value="rem" <?php selected($options['font_size_unit'], 'rem'); ?>>rem</option>
        <option value="%" <?php selected($options['font_size_unit'], '%'); ?>>%</option>
    </select>
    <?php
}

function custom_topbar_height_render() {
    $options = get_option('custom_topbar_options');
    ?>
    <input type="number" name="custom_topbar_options[height]" value="<?php echo isset($options['height']) ? esc_attr($options['height']) : '40'; ?>" min="1">
    <select name="custom_topbar_options[height_unit]">
        <option value="px" <?php selected($options['height_unit'], 'px'); ?>>px</option>
        <option value="em" <?php selected($options['height_unit'], 'em'); ?>>em</option>
        <option value="rem" <?php selected($options['height_unit'], 'rem'); ?>>rem</option>
        <option value="%" <?php selected($options['height_unit'], '%'); ?>>%</option>
    </select>
    <?php
}

function custom_topbar_content_text_render() {
    $options = get_option('custom_topbar_options');
    ?>
    <textarea cols="40" rows="2" name="custom_topbar_options[content_text]"><?php echo isset($options['content_text']) ? esc_textarea($options['content_text']) : 'This is a topbar!'; ?></textarea>
    <?php
}

function custom_topbar_custom_css_render() {
    $options = get_option('custom_topbar_options');
    ?>
    <textarea cols="40" rows="10" name="custom_topbar_options[custom_css]" placeholder="Enter your custom CSS here"><?php echo isset($options['custom_css']) ? esc_textarea($options['custom_css']) : ''; ?></textarea>
    <p class="description">Add your custom CSS rules here. For example, you can add styles to change the topbar's appearance. Use selectors like <code>.custom-topbar</code> to target the topbar.</p>
    <?php
}

// Settings page callback function
function custom_topbar_settings_page() {
    ?>
    <div class="wrap">
        <h1>Custom Topbar Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('custom_topbar_group');
            do_settings_sections('custom-topbar');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Enqueue color picker script and styles
function custom_topbar_enqueue_color_picker($hook_suffix) {
    if ('settings_page_custom-topbar' !== $hook_suffix) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('custom-topbar-color-picker', plugins_url('color-picker.js', __FILE__), array('wp-color-picker'), false, true);
}
add_action('admin_enqueue_scripts', 'custom_topbar_enqueue_color_picker');
?>
