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
        <div class="fluent-mail-main-menu-items"><ul role="menubar" class="fluent-mail-navigation el-menu--horizontal el-menu"><li role="menuitem" tabindex="0" class="el-menu-item" style="border-bottom-color: transparent;"><p style="font-size: 18pt;"><a href="https://datmarketing.edu.vn" style="text-decoration: none;"><strong><span style="color: #000000;">DAT</span><span style="color: #ff0000;">Marketing</span></strong><span style="color: #000000;">™</span></a></p></li> <li role="menuitem" tabindex="0" class="el-menu-item is-active" style="">Cài đặt</li></ul></div>
        <!-- New structure based on fluent-mail-admin.css -->
        <div class="fluent-mail-body">
            <div class="el-row" style="margin-left: -10px; margin-right: -10px;">
                <div class="el-col el-col-24 el-col-sm-24 el-col-md-10" style="padding-left: 10px; padding-right: 10px;">
                    <div class="fss_content_box fss_box_action fss_box_active" style="margin-bottom: 0px;">
                        <div class="fss_header">
                            CÀI ĐẶT CHUNG
                        </div>
                        <div class="fss_content">
                            <div class="fss_general_settings">
                                <form method="post" action="options.php" class="el-form fss_compact_form el-form--label-top">
                                    <?php settings_fields('datcontact-standard-settings-group'); ?>
                                    <?php do_settings_sections('datcontact-standard-settings-group'); ?>
<div class="el-form-item">
                                        <label class="el-form-item__label">Link đặt hàng</label>
                                        <div class="el-form-item__content">
                                            <input type="text" name="link_order_standard" value="<?php echo esc_attr(get_option('link_order_standard')); ?>" placeholder="Ví dụ: https://example.com/order" />
                                        </div>
                                    </div>
                                    <div class="el-form-item">
                                        <label class="el-form-item__label">Số điện thoại Zalo</label>
                                        <div class="el-form-item__content">
                                            <input type="text" name="link_zalo_standard" value="<?php echo esc_attr(str_replace('https://zalo.me/', '', get_option('link_zalo_standard'))); ?>" placeholder="Ví dụ: 0569171809" />
                                        </div>
                                    </div>
                                    <div class="el-form-item">
                                        <label class="el-form-item__label">Số điện thoại</label>
                                        <div class="el-form-item__content">
                                            <input type="text" name="link_phone_standard" value="<?php echo esc_attr(str_replace('tel:', '', get_option('link_phone_standard'))); ?>" placeholder="Ví dụ: 0569171809" />
                                        </div>
                                    </div>
                                    <div class="el-form-item">
                                        <label class="el-form-item__label">Tên Messenger</label>
                                        <div class="el-form-item__content">
<input type="text" name="link_messenger_standard" value="<?php echo esc_attr(str_replace('https://m.me/', '', get_option('link_messenger_standard'))); ?>" placeholder="Ví dụ: tên-người-dùng" />
                                        </div>
                                    </div>
                                    <div class="el-form-item">
                                        <label class="el-form-item__label">Số điện thoại SMS</label>
                                        <div class="el-form-item__content">
                                            <input type="text" name="link_sms_standard" value="<?php echo esc_attr(str_replace('sms:', '', get_option('link_sms_standard'))); ?>" placeholder="Ví dụ: 0569171809" />
                                        </div>
                                    </div>
                                    <?php submit_button(); ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

// Enqueue frontend styles
function datcontact_standard_enqueue_frontend_styles() {
    wp_enqueue_style(
        'datcontact-standard-style',
        plugin_dir_url(__FILE__) . 'css/datcontact-standard-styles.css',
        array(),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'datcontact_standard_enqueue_frontend_styles');

// Enqueue admin styles
function datcontact_standard_enqueue_admin_styles($hook) {
    // Load only on the plugin settings page
    if (strpos($hook, 'datcontact-standard') === false) {
        return;
    }
    
    // Get the correct path to the CSS file
    $css_path = plugin_dir_url(__FILE__) . 'admin/datcontact-admin-styles.css';
    
    // Verify the CSS file exists
    if (file_exists(plugin_dir_path(__FILE__) . 'admin/datcontact-admin-styles.css')) {
        wp_enqueue_style(
            'datcontact-standard-admin-style',
            $css_path,
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'admin/datcontact-admin-styles.css') // Use filemtime for cache busting
        );
    } else {
        error_log('DATContact Standard: CSS file not found at ' . $css_path);
    }
}
add_action('admin_enqueue_scripts', 'datcontact_standard_enqueue_admin_styles');

// Add button contact bar to the front-end
add_action('wp_footer', 'datcontact_standard_button_contact_bar');

function datcontact_standard_button_contact_bar() {
    $link_order = esc_url(get_option('link_order_standard'));
    $zalo_number = get_option('link_zalo_standard');
    $link_zalo = esc_url('https://zalo.me/' . $zalo_number);
    $phone_number = get_option('link_phone_standard');
    $link_phone = esc_url('tel:' . $phone_number);
    
    $messenger_name = get_option('link_messenger_standard');
    $link_messenger = esc_url('https://m.me/' . $messenger_name);
    
    $sms_number = get_option('link_sms_standard');
    $link_sms = esc_url('sms:' . $sms_number);
    $plugin_url = plugin_dir_url(__FILE__) . 'assets/icons/';
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
                <a href="#" rel="nofollow">
                    <i class="ticon-angle-up" aria-hidden="true" title="Quay lên trên"></i>
                </a>
            </li>
        </ul>
    </div>
<?php
}
?>
