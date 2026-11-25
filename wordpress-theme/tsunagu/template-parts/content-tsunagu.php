<?php
/** 依頼ページ本体（TOPIC / カード6枚 / モーダル） */
if ( ! defined( 'ABSPATH' ) ) exit;

$d        = tsunagu_defaults();
$topics   = tsunagu_topics( $d['topics'] );
$topics_in_header = ( tsunagu_field( 'topics_position', $d['topics_position'] ) === 'header' );
$order_pc = tsunagu_card_order( 'cards_order_pc', $d['cards_order_pc'] );
$order_sp = tsunagu_card_order( 'cards_order_sp', $d['cards_order_sp'] );
?>

<main>

	<!-- サービスメニュー（固定6枚） -->
	<section class="services" id="menu">
		<div class="container">

			<?php if ( ! empty( $topics ) ) : ?>
			<div class="topics<?php echo $topics_in_header ? ' topics--below' : ''; ?>" data-reveal>
				<span class="topics__label">TOPIC</span>
				<div class="topics__viewport">
					<ul class="topics__track">
						<?php foreach ( $topics as $t ) : ?>
							<li class="topics__item"><?php echo esc_html( $t ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php endif; ?>

			<div class="services__grid">
				<?php
				// HTMLはPCの並び順で出力し、スマホの並び順は CSS の order（--sp-order）で切り替える
				foreach ( $order_pc as $n ) :
					$c      = $d['cards'][ $n ];
					$theme  = $c['theme'];
					$tag    = tsunagu_field( "card_{$n}_tag", $c['tag'] );
					$title  = tsunagu_field( "card_{$n}_title", $c['title'] );
					$desc   = tsunagu_field( "card_{$n}_desc", $c['desc'] );
					$btn1   = tsunagu_field( "card_{$n}_btn1_label", $c['btn1'] );
					$forms  = tsunagu_card_forms( $n, $c['forms'] );
					$btn2   = tsunagu_field( "card_{$n}_btn2_label", $c['btn2'] );
					$ptitle = tsunagu_field( "card_{$n}_popup_title", $c['popup_title'] );
					$sp_pos = array_search( $n, $order_sp, true ) + 1;

					// 右ボタン：リンク先があればリンク、なければポップアップ
					$link2  = tsunagu_card_btn2_link( $n, isset( $c['btn2_link'] ) ? $c['btn2_link'] : null );
					$popup  = '';
					$pdfurl = $link2 ? '' : tsunagu_file_url( "card_{$n}_popup_pdf", isset( $c['popup_pdf'] ) ? $c['popup_pdf'] : '' );

					// ポップアップ：PDFがあればPDF（ACFで本文を入力していれば本文＋PDF）、なければ本文
					if ( $link2 ) {
						// リンクのときはポップアップを出さない
					} elseif ( $pdfurl ) {
						$text  = function_exists( 'get_field' ) ? get_field( "card_{$n}_popup_text" ) : '';
						$popup = ( is_string( $text ) ? $text : '' )
							. '<object class="popup-pdf" data="' . esc_url( $pdfurl ) . '" type="application/pdf" title="' . esc_attr( $ptitle ) . '"><p class="popup-note">PDFをこの場で表示できない環境のようです。下のリンクからご覧ください。</p></object>'
							. '<p class="popup-note"><a href="' . esc_url( $pdfurl ) . '" target="_blank" rel="noopener">PDFを新しいタブで開く →</a></p>';
					} else {
						$popup = tsunagu_field( "card_{$n}_popup_text", $c['popup'] );
					}
					?>
					<article class="service-card" data-theme="<?php echo esc_attr( $theme ); ?>" data-card="<?php echo (int) $n; ?>" style="--sp-order:<?php echo (int) $sp_pos; ?>" data-reveal>
						<span class="service-card__tag"><?php echo esc_html( $tag ); ?></span>
						<div class="service-card__head">
							<span class="service-card__icon"><?php echo tsunagu_card_icon( $n ); // phpcs:ignore ?></span>
							<h3 class="service-card__title"><?php echo wp_kses_post( $title ); ?></h3>
						</div>
						<p class="service-card__desc"><?php echo nl2br( esc_html( $desc ), false ); // 改行はそのまま改行として表示 ?></p>
						<div class="service-card__actions actions--split">
							<?php if ( count( $forms ) >= 2 ) : ?>
								<div class="pdf-dropdown actions__primary">
									<button type="button" class="btn btn--solid pdf-trigger" aria-haspopup="true" aria-expanded="false">
										<span><?php echo esc_html( $btn1 ); ?></span>
										<svg class="pdf-caret" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									</button>
									<ul class="pdf-menu" role="menu" hidden>
										<?php foreach ( $forms as $f ) : ?>
											<li role="none"><a class="pdf-menu__item" role="menuitem" href="<?php echo esc_url( $f['url'] ); ?>"<?php echo tsunagu_link_target( $f ); // phpcs:ignore ?>><?php echo esc_html( $f['label'] ); ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php else :
								$one = ! empty( $forms ) ? $forms[0] : array( 'url' => '#' ); ?>
								<a class="btn btn--solid actions__primary" href="<?php echo esc_url( $one['url'] ); ?>"<?php echo tsunagu_link_target( $one ); // phpcs:ignore ?>><?php echo esc_html( $btn1 ); ?></a>
							<?php endif; ?>
							<?php if ( $link2 ) : ?>
								<a class="btn btn--outline actions__secondary" href="<?php echo esc_url( $link2['url'] ); ?>"<?php echo tsunagu_link_target( $link2 ); // phpcs:ignore ?>><?php echo esc_html( $btn2 ); ?></a>
							<?php else : ?>
								<button type="button" class="btn btn--outline actions__secondary popup-trigger" data-popup="popup-c<?php echo (int) $n; ?>" data-popup-title="<?php echo esc_attr( $ptitle ); ?>"><?php echo esc_html( $btn2 ); ?></button>
							<?php endif; ?>
						</div>
						<?php if ( ! $link2 ) : ?>
						<div class="popup-content" id="popup-c<?php echo (int) $n; ?>" hidden><?php echo $popup; // phpcs:ignore ?></div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

</main>

<!-- 共通ポップアップ（右ボタン用） -->
<div class="modal" id="modal" hidden>
	<div class="modal__overlay" data-close></div>
	<div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="modal-title">
		<button type="button" class="modal__close" data-close aria-label="閉じる">&times;</button>
		<h3 class="modal__title" id="modal-title"></h3>
		<div class="modal__body"></div>
	</div>
</div>
