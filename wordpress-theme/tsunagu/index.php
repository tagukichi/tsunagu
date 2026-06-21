<?php
/** 汎用フォールバックテンプレート */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main class="container" style="padding:64px 0; min-height:52vh;">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1 style="font-size:28px; color:#11295a; margin-bottom:18px;"><?php the_title(); ?></h1>
		<div style="font-size:15px; line-height:1.9; color:#233244;"><?php the_content(); ?></div>
	<?php endwhile; else : ?>
		<p>コンテンツが見つかりませんでした。</p>
	<?php endif; ?>
</main>
<?php
get_footer();
