<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="section white"><div class="container"><h1><?php bloginfo('name'); ?></h1><?php if (have_posts()) { while (have_posts()) { the_post(); the_title('<h2>','</h2>'); the_content(); } } ?></div></main>
<?php get_footer();
