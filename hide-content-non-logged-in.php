<?php
/*
Plugin Name: Hide Content from Non-Logged In Users using a Shortcode
Description: Hide Content with shortcode: [hide_content] THIS CONTENT [/hide_content] - [hide_content message="Custom notifications"]THIS CONTENT [/hide_content] The plugin can hide any content from non-logged in users. The code is simple, compact, does not take up storage space or system resources. Works with shortcodes like CF7.
Version: 1.1
Author: Hoàng Giang Nam
Author URI: https://hoanggiangnam.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function hide_content_for_non_logged_in_users($atts, $content = null) {
    // Lấy các thuộc tính từ shortcode, bao gồm các thuộc tính CSS tùy chỉnh và URL đăng nhập
    // You must Sign in / Sign up to use the contact form and view this hidden content.
    $atts = shortcode_atts(
        array(
            'message' => 'You must <a href="https://yourdomain.com/my-account/">log-in</a> to view this content.',
            'bg_color' => 'yellow',
            'padding' => '10px',
            'border_radius' => '5px',
            'login_url' => 'https://yourdomain.com/my-account/'
        ),
        $atts,
        'hide_content'
    );

    if (is_user_logged_in()) {
        return do_shortcode($content);
    } else {
        // Áp dụng các thuộc tính CSS tùy chỉnh vào phần thông báo
        $style = 'background-color: ' . esc_attr($atts['bg_color']) . '; ';
        $style .= 'padding: ' . esc_attr($atts['padding']) . '; ';
        $style .= 'border-radius: ' . esc_attr($atts['border_radius']) . ';';

        // Thay thế URL đăng nhập trong thông báo
        $message = str_replace('https://yourdomain.com/my-account/', esc_url($atts['login_url']), $atts['message']);

        return '<div style="' . $style . '">' . wp_kses_post($message) . '</div>';
    }
}
add_shortcode('hide_content', 'hide_content_for_non_logged_in_users');


function hide_content_plugin_menu() {
    add_options_page(
        'Hide Content Plugin Settings',
        'Hide Content Settings',
        'manage_options',
        'hide-content-plugin',
        'hide_content_plugin_settings_page'
    );
}
add_action('admin_menu', 'hide_content_plugin_menu');

function hide_content_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Hide Content Plugin Settings</h1>
        <div style="background-color: yellow; padding: 10px; border-radius: 5px;">
            <p><strong>Shortcode Notifications defause:</strong></p>
            <p><strong>[hide_content] THIS CONTENT [/hide_content]</strong> </p></br></br>
            <p><strong>Custom notifications:</strong></p>
            <p><strong>[hide_content message="Custom notifications"]THIS CONTENT [/hide_content]</strong></p>
            <p><strong>[hide_content message="" bg_color="unset" padding="0px" border_radius="0px"]THIS CONTENT [/hide_content]</strong></p>
            <p><strong>[hide_content message="You must <a href='https://yourdomain.com/my-account/'>log-in</a> to view this content." bg_color="red" padding="20px" border_radius="10px" login_url="https://yourdomain.com/my-account/"]THIS CONTENT HIDE[/hide_content]
</strong></p>
        </div>
        <p>This plugin allows you to hide content from non-logged in users using a shortcode.</p>
    </div>
    <?php
}
?>