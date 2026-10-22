<?php
if (!defined('ABSPATH')) {
    exit;
}

function rauma_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus(['primary' => 'Menu chính']);
}
add_action('after_setup_theme', 'rauma_setup');

function rauma_assets(): void {
    wp_enqueue_style('rauma-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
    wp_enqueue_script('rauma-script', get_template_directory_uri() . '/assets/site.js', [], wp_get_theme()->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'rauma_assets');

function rauma_register_content_types(): void {
    register_post_type('san_pham', [
        'labels' => [
            'name' => 'Sản phẩm',
            'singular_name' => 'Sản phẩm',
            'add_new_item' => 'Thêm sản phẩm',
            'edit_item' => 'Chỉnh sửa sản phẩm',
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-carrot',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'san-pham'],
    ]);

    register_post_type('lien_he_rauma', [
        'labels' => ['name' => 'Liên hệ khách hàng', 'singular_name' => 'Liên hệ'],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title', 'editor'],
        'capability_type' => 'post',
    ]);
}
add_action('init', 'rauma_register_content_types');

function rauma_handle_contact(): void {
    if (!isset($_POST['rauma_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rauma_nonce'])), 'rauma_contact')) {
        wp_die('Yêu cầu không hợp lệ.', 'Lỗi bảo mật', ['response' => 403]);
    }

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if ($name === '' || !is_email($email) || $message === '') {
        wp_safe_redirect(add_query_arg('contact', 'invalid', home_url('/#lien-he')));
        exit;
    }

    $ip_key = 'rauma_contact_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (get_transient($ip_key)) {
        wp_safe_redirect(add_query_arg('contact', 'slow', home_url('/#lien-he')));
        exit;
    }

    wp_insert_post([
        'post_type' => 'lien_he_rauma',
        'post_status' => 'private',
        'post_title' => sprintf('%s - %s', $name, current_time('d/m/Y H:i')),
        'post_content' => "Email: {$email}\nĐiện thoại: {$phone}\n\n{$message}",
    ]);
    set_transient($ip_key, 1, MINUTE_IN_SECONDS);

    wp_safe_redirect(add_query_arg('contact', 'success', home_url('/#lien-he')));
    exit;
}
add_action('admin_post_nopriv_rauma_contact', 'rauma_handle_contact');
add_action('admin_post_rauma_contact', 'rauma_handle_contact');
