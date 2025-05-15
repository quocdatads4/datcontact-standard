<?php
/*
Plugin Name: DATContact Standard
Description: Standard button contact plugin for WordPress.
Version: 1.0
Author: DATMarketing
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add settings menu
add_action('admin_menu', 'datcontact_standard_create_menu');

function datcontact_standard_create_menu() {
    add_menu_page(
        'DATContact Standard Settings',
        'DATContact Standard',
        'administrator',
        __FILE__,
        'datcontact_standard_settings_page',
        'dashicons-admin-generic'
    );
    add_action('admin_init', 'datcontact_standard_register_settings');
}

function datcontact_standard_register_settings() {
    register_setting('datcontact-standard-settings-group', 'link_order_standard');
    register_setting('datcontact-standard-settings-group', 'link_zalo_standard');
    register_setting('datcontact-standard-settings-group', 'link_phone_standard');
    register_setting('datcontact-standard-settings-group', 'link_messenger_standard');
    register_setting('datcontact-standard-settings-group', 'link_sms_standard');
}

// Settings page layout
function datcontact_standard_settings_page() {
?>
    <div class="wrap">
        <h1>DATContact Standard Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('datcontact-standard-settings-group'); ?>
            <?php do_settings_sections('datcontact-standard-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Link đặt hàng</th>
                    <td><input type="text" name="link_order_standard" value="<?php echo esc_attr(get_option('link_order_standard')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Link chat Zalo</th>
                    <td><input type="text" name="link_zalo_standard" value="<?php echo esc_attr(get_option('link_zalo_standard')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Gọi điện</th>
                    <td><input type="text" name="link_phone_standard" value="<?php echo esc_attr(get_option('link_phone_standard')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Link Messenger</th>
                    <td><input type="text" name="link_messenger_standard" value="<?php echo esc_attr(get_option('link_messenger_standard')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Nhắn tin SMS</th>
                    <td><input type="text" name="link_sms_standard" value="<?php echo esc_attr(get_option('link_sms_standard')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Enqueue styles
function datcontact_standard_enqueue_styles() {
    wp_enqueue_style(
        'datcontact-standard-style',
        plugin_dir_url(__FILE__) . 'css/datcontact-standard-styles.css',
        array(),
        '1.0' // Version number
    );
}
add_action('wp_enqueue_scripts', 'datcontact_standard_enqueue_styles');

// Add button contact bar to the front-end
add_action('wp_footer', 'datcontact_standard_button_contact_bar');

function datcontact_standard_button_contact_bar() {
    $link_order = esc_url(get_option('link_order_standard'));
    $link_zalo = esc_url(get_option('link_zalo_standard'));
    $link_phone = esc_url(get_option('link_phone_standard'));
    $link_messenger = esc_url(get_option('link_messenger_standard'));
    $link_sms = esc_url(get_option('link_sms_standard'));
?>
    <div class="giuseart-nav">
        <ul>
            <li><a href="<?php echo $link_order; ?>" rel="nofollow" target="_blank"><i class="ticon-heart"></i>Đặt hàng</a></li>
            <li><a href="<?php echo $link_zalo; ?>" rel="nofollow" target="_blank"><i class="ticon-zalo-circle2"></i>Chat Zalo</a></li>
            <li class="phone-mobile">
                <a href="<?php echo $link_phone; ?>" rel="nofollow" class="button">
                    <span class="phone_animation animation-shadow">
                        <i class="icon-phone-w" aria-hidden="true"></i>
                    </span>
                    <span class="btn_phone_txt">Gọi điện</span>
                </a>
            </li>
            <li><a href="<?php echo $link_messenger; ?>" rel="nofollow" target="_blank"><i class="ticon-messenger"></i>Messenger</a></li>
            <li><a href="<?php echo $link_sms; ?>" class="chat_animation">
            <i class="ticon-chat-sms" aria-hidden="true" title="Nhắn tin sms"></i>Nhắn tin SMS</a></li>
            <li class="to-top-pc">
                <a href="#" rel="nofollow">
                    <i class="ticon-angle-up" aria-hidden="true" title="Quay lên trên"></i>
                </a>
            </li>
        </ul>
    </div>
<?php
}
?>
