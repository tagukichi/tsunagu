<?php
/** フォームページ本体（ページ名 / 説明 / ステップ / CF7フォーム） */
if ( ! defined( 'ABSPATH' ) ) exit;

$d         = tsunagu_defaults();
$desc      = tsunagu_field( 'form_desc', $d['form_desc'] );
$shortcode = function_exists( 'get_field' ) ? get_field( 'form_shortcode' ) : '';
?>

<main class="formpage">
	<div class="container formpage__inner">

		<?php while ( have_posts() ) : the_post(); ?>
			<h1 class="formpage__title"><?php the_title(); ?></h1>
		<?php endwhile; ?>

		<?php if ( $desc ) : ?>
			<p class="formpage__desc"><?php echo nl2br( esc_html( $desc ) ); ?></p>
		<?php endif; ?>

		<!-- 入力 → 確認 → 送信 のステップ表示 -->
		<ol class="formsteps" aria-label="入力の流れ">
			<li class="formsteps__item is-current"><span class="formsteps__num">1</span>入力</li>
			<li class="formsteps__item"><span class="formsteps__num">2</span>確認</li>
			<li class="formsteps__item"><span class="formsteps__num">3</span>送信</li>
		</ol>

		<div class="formpage__form">
			<?php
			if ( $shortcode ) {
				echo do_shortcode( $shortcode ); // phpcs:ignore
			} else {
				echo '<p class="formpage__empty">フォームは準備中です。管理画面のカスタムフィールド「Contact Form 7 ショートコード」にフォームのショートコードを貼り付けてください。</p>';
			}
			?>
		</div>

	</div>
</main>
