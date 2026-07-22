<?php
/** フォームページ本体（ページ名 / 説明 / ステップ / CF7フォーム） */
if ( ! defined( 'ABSPATH' ) ) exit;

$d            = tsunagu_defaults();
$desc         = tsunagu_field( 'form_desc', $d['form_desc'] );
$shortcode    = function_exists( 'get_field' ) ? get_field( 'form_shortcode' ) : '';
$thanks_title = tsunagu_field( 'form_thanks_title', $d['form_thanks_title'] );
$thanks_text  = tsunagu_field( 'form_thanks_text', $d['form_thanks_text'] );
$redirect     = function_exists( 'get_field' ) ? get_field( 'form_redirect_page' ) : '';
if ( is_array( $redirect ) ) $redirect = reset( $redirect );
?>

<main class="formpage" data-redirect="<?php echo esc_url( is_string( $redirect ) ? $redirect : '' ); ?>">
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

		<!-- サンクス画面（送信完了時にJSで表示。form_redirect_page が設定されていればそのページへ遷移） -->
		<div class="formpage__thanks" hidden>
			<span class="formpage__thanks-icon">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M5 12.5l4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</span>
			<h2 class="formpage__thanks-title"><?php echo esc_html( $thanks_title ); ?></h2>
			<div class="formpage__thanks-text"><?php echo wp_kses_post( wpautop( $thanks_text ) ); ?></div>
			<a class="btn btn--solid formpage__thanks-home" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップへ戻る</a>
		</div>

	</div>
</main>
