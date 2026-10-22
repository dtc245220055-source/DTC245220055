<?php
if (!defined('ABSPATH')) {
    exit;
}
$contact_status = sanitize_key(wp_unslash($_GET['contact'] ?? ''));
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="#trang-chu" aria-label="Rau Má Mộc - Trang chủ">
            <span class="brand-mark"><span>☘</span></span>
            <span>RAU MÁ MỘC<small>NGUYÊN CHẤT MỖI NGÀY</small></span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav">☰</button>
        <nav id="main-nav" class="main-nav" aria-label="Menu chính">
            <a href="#trang-chu">Trang chủ</a>
            <a href="#san-pham">Sản phẩm</a>
            <a href="#gioi-thieu">Giới thiệu</a>
            <a href="#quy-trinh">Quy trình</a>
            <a class="nav-cta" href="#lien-he">Liên hệ</a>
        </nav>
    </div>
</header>

<main>
    <section id="trang-chu" class="hero">
        <div class="container hero-grid">
            <div>
                <p class="eyebrow">Tươi mỗi ngày  Lành mỗi ngụm</p>
                <h1>Năng lượng xanh từ <em>rau má nguyên chất</em></h1>
                <p class="hero-lead">Rau Má Mộc mang đến thức uống thanh mát, ít ngọt, chế biến trong ngày từ nguồn rau má được chọn lọc kỹ.</p>
                <div class="button-row">
                    <a class="button" href="#san-pham">Khám phá sản phẩm</a>
                    <a class="button secondary" href="#gioi-thieu">Câu chuyện của chúng tôi</a>
                </div>
            </div>
            <div class="hero-card" aria-label="Banner rau má">
                <img class="hero-banner-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/banner-rauma.png'); ?>" alt="Banner sản phẩm rau má">
            </div>
        </div>
    </section>

    <section class="stats" aria-label="Điểm nổi bật">
        <div class="container stats-grid">
            <div class="stat"><strong>100%</strong>rau má chọn lọc</div>
            <div class="stat"><strong>0</strong>chất bảo quản</div>
            <div class="stat"><strong>24h</strong>chế biến trong ngày</div>
            <div class="stat"><strong>3 vị</strong>dễ chọn theo sở thích</div>
        </div>
    </section>

    <section id="san-pham" class="section white">
        <div class="container">
            <div class="section-head">
                <p class="eyebrow">Sản phẩm nổi bật</p>
                <h2>Chọn vị xanh của bạn</h2>
                <p>Ba lựa chọn được cân bằng giữa vị rau má tự nhiên, độ béo và hương thơm dịu nhẹ.</p>
            </div>
            <div class="products">
                <?php
                $products = new WP_Query(['post_type' => 'san_pham', 'posts_per_page' => 3]);
                if ($products->have_posts()) :
                    while ($products->have_posts()) : $products->the_post();
                        $price = get_post_meta(get_the_ID(), 'gia', true) ?: '25.000đ';
                        ?>
                        <article class="product-card">
                            <div class="product-visual"><?php if (has_post_thumbnail()) { the_post_thumbnail('medium_large'); } else { echo '☘'; } ?></div>
                            <div class="product-copy">
                                <span class="pill">Tươi trong ngày</span>
                                <h3><?php the_title(); ?></h3>
                                <p><?php echo esc_html(get_the_excerpt()); ?></p>
                                <div class="price"><span><?php echo esc_html($price); ?></span><a href="#lien-he">Đặt thử →</a></div>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata();
                else :
                    $fallback = [
                        ['icon' => '', 'image' => get_template_directory_uri() . '/assets/rau-ma-nguyen-chat.png', 'name' => 'Rau má nguyên chất', 'description' => 'Vị rau má thanh nhẹ, ít đường, phù hợp dùng mỗi ngày.', 'price' => '22.000đ'],
                        ['icon' => '', 'image' => get_template_directory_uri() . '/assets/rau-ma-sua-dua.png', 'name' => 'Rau má sữa dừa', 'description' => 'Rau má tươi kết hợp sữa dừa béo dịu và thơm tự nhiên.', 'price' => '28.000đ'],
                        ['icon' => '', 'image' => get_template_directory_uri() . '/assets/rau-ma-dau-xanh.png', 'name' => 'Rau má đậu xanh', 'description' => 'Bùi nhẹ từ đậu xanh, cân bằng với vị mát của rau má.', 'price' => '27.000đ'],
                    ];
                    foreach ($fallback as $item) : ?>
                        <article class="product-card">
                            <div class="product-visual">
                                <?php if ($item['image']) : ?>
                                    <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>">
                                <?php else : ?>
                                    <?php echo esc_html($item['icon']); ?>
                                <?php endif; ?>
                            </div>
                            <div class="product-copy"><span class="pill">Tươi trong ngày</span><h3><?php echo esc_html($item['name']); ?></h3><p><?php echo esc_html($item['description']); ?></p><div class="price"><span><?php echo esc_html($item['price']); ?></span><a href="#lien-he">Đặt thử →</a></div></div>
                        </article>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>
    </section>

    <section id="gioi-thieu" class="section">
        <div class="container about-grid">
            <div class="about-panel">
                <p class="eyebrow" style="color:#c5f0cb">Vì sao chọn Rau Má Mộc</p>
                <h3>Mộc mạc trong thành phần, chỉn chu trong từng chai</h3>
                <ul class="check-list">
                    <li>Rau má được rửa kỹ và chọn lọc trước khi chế biến.</li>
                    <li>Điều chỉnh độ ngọt theo yêu cầu của khách hàng.</li>
                    <li>Không dùng màu tổng hợp và chất bảo quản.</li>
                    <li>Chai được làm lạnh và giao trong ngày.</li>
                </ul>
            </div>
            <div>
                <p class="eyebrow">Câu chuyện thương hiệu</p>
                <h2>Từ thức uống quen thuộc đến lựa chọn sống xanh</h2>
                <p>Rau Má Mộc bắt đầu từ mong muốn làm mới một thức uống Việt Nam gần gũi. Chúng tôi giữ lại hương vị rau má tự nhiên, giảm độ ngọt và kết hợp với các nguyên liệu quen thuộc như đậu xanh, sữa dừa.</p>
                <p>Mỗi sản phẩm được làm theo mẻ nhỏ để giữ sự tươi mới. Thông tin thành phần, giá và cách liên hệ được công khai để khách hàng dễ dàng lựa chọn.</p>
            </div>
        </div>
    </section>

    <section id="quy-trinh" class="section white">
        <div class="container about-grid">
            <div>
                <p class="eyebrow">Quy trình trong ngày</p>
                <h2>Bốn bước tạo nên một chai rau má tươi</h2>
                <p>Quy trình ngắn, dễ kiểm soát và ưu tiên vệ sinh ở từng công đoạn.</p>
            </div>
            <div class="steps">
                <div class="step"><b>01</b><div><h3>Chọn nguyên liệu</h3><p>Loại bỏ lá già, úa và tạp chất trước khi sơ chế.</p></div></div>
                <div class="step"><b>02</b><div><h3>Rửa và ngâm</h3><p>Làm sạch rau má qua nhiều lần nước trước khi xay.</p></div></div>
                <div class="step"><b>03</b><div><h3>Xay và phối vị</h3><p>Cân chỉnh nguyên liệu theo công thức của từng sản phẩm.</p></div></div>
                <div class="step"><b>04</b><div><h3>Đóng chai lạnh</h3><p>Đóng chai, bảo quản mát và giao đến khách trong ngày.</p></div></div>
            </div>
        </div>
    </section>

    <section id="lien-he" class="section">
        <div class="container contact-grid">
            <div>
                <p class="eyebrow">Kết nối với chúng tôi</p>
                <h2>Đặt thử vị rau má bạn yêu thích</h2>
                <p>Gửi thông tin để Rau Má Mộc ghi nhận yêu cầu. Nội dung được lưu trong khu vực quản trị WordPress để người quản trị xử lý.</p>
                <div class="contact-info">
                    <div class="contact-item"><strong>Địa chỉ</strong>Thái Nguyên, Việt Nam</div>
                    <div class="contact-item"><strong>Điện thoại</strong>09xx xxx xxx</div>
                    <div class="contact-item"><strong>Thời gian</strong>08:00 – 21:00 mỗi ngày</div>
                </div>
            </div>
            <div class="contact-card">
                <?php if ($contact_status === 'success') : ?><p class="notice">Cảm ơn bạn! Yêu cầu đã được ghi nhận.</p><?php endif; ?>
                <?php if ($contact_status === 'invalid') : ?><p class="notice">Vui lòng nhập đúng họ tên, email và nội dung.</p><?php endif; ?>
                <?php if ($contact_status === 'slow') : ?><p class="notice">Bạn vừa gửi liên hệ. Vui lòng thử lại sau một phút.</p><?php endif; ?>
                <form class="form-grid" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="rauma_contact">
                    <?php wp_nonce_field('rauma_contact', 'rauma_nonce'); ?>
                    <label>Họ và tên<input type="text" name="name" maxlength="80" required></label>
                    <label>Email<input type="email" name="email" maxlength="120" required></label>
                    <label>Số điện thoại<input type="tel" name="phone" maxlength="20"></label>
                    <label>Nội dung<textarea name="message" maxlength="1000" required></textarea></label>
                    <button class="button" type="submit">Gửi yêu cầu</button>
                </form>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div><h3>Rau Má Mộc</h3><p>Thức uống rau má tươi được làm trong ngày, hướng đến sự mộc mạc, dễ uống và minh bạch thành phần.</p></div>
            <div><h3>Điều hướng</h3><a href="#san-pham">Sản phẩm</a><a href="#gioi-thieu">Giới thiệu</a><a href="#quy-trinh">Quy trình</a></div>
            <div><h3>Liên hệ</h3><a href="tel:0900000000">09xx xxx xxx</a><a href="mailto:contact@example.com">contact@example.com</a></div>
        </div>
        <div class="copyright">© <?php echo esc_html(wp_date('Y')); ?> Rau Má Mộc · Đề 03 · DTC245220055</div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
