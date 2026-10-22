<?php
/**
 * Plugin Name: Rau Ma Moc Bootstrap
 * Description: Kich hoat giao dien De 03 va khoi tao noi dung mau mot lan.
 * Version: 1.0.1
 */
if (!defined('ABSPATH')) {
    exit;
}

function rauma_bootstrap_site(): void {
    if (get_option('rauma_bootstrap_version') === '1.0.1') {
        return;
    }

    $theme = wp_get_theme('rau-ma');
    if ($theme->exists()) {
        switch_theme('rau-ma');
    }

    update_option('blogname', 'Rau Má Mộc');
    update_option('blogdescription', 'Nước rau má tươi nguyên chất mỗi ngày');
    update_option('home', 'http://localhost:8082');
    update_option('siteurl', 'http://localhost:8082');
    update_option('rauma_bootstrap_version', '1.0.1');
    flush_rewrite_rules(false);
}
add_action('init', 'rauma_bootstrap_site', 20);
