<?php
/** 固定ページ（専用テンプレート未使用時）のフォールバック */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main class="container" style="padding:64px 0; min-height:52vh;">
	<?php while ( have_posts() ) : the_post(); ?>
		<h1 style="font-size:28px; color:#11295a; margin-bottom:18px;"><?php the_title(); ?></h1>
		<div style="font-size:15px; line-height:1.9; color:#233244;"><?php the_content(); ?></div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
