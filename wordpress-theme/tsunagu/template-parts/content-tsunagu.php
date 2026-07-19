<?php
/** 依頼ページ本体（ヒーロー / 調速 / カード6枚 / TOPIC / 相談CTA / モーダル） */
if ( ! defined( 'ABSPATH' ) ) exit;

$d            = tsunagu_defaults();
$featured_img = tsunagu_image_url( 'featured_logo', tsunagu_asset( 'img/cho-sokulogo_yoko.png' ) );
$topics       = tsunagu_topics( $d['topics'] );
?>

<main>

	<!-- ヒーロー（右に調速プロモを配置） -->
	<section class="hero">
		<div class="container hero__inner">
			<div class="hero__main" data-reveal>
				<span class="hero__badge">
					<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
						<path d="M9 12l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<?php echo esc_html( tsunagu_field( 'hero_badge', $d['hero_badge'] ) ); ?>
				</span>
				<h1 class="hero__title"><?php echo esc_html( tsunagu_field( 'hero_title', $d['hero_title'] ) ); ?></h1>
				<?php $mail = tsunagu_field( 'hero_contact_email', $d['hero_contact_email'] ); ?>
				<?php if ( $mail ) : ?>
				<p class="hero__contact">
					<span class="hero__contact-label"><?php echo esc_html( tsunagu_field( 'hero_contact_label', $d['hero_contact_label'] ) ); ?></span>
					<a class="hero__contact-mail" href="mailto:<?php echo esc_attr( $mail ); ?>"><?php echo esc_html( $mail ); ?></a>
				</p>
				<?php endif; ?>
			</div>

			<aside class="hero__promo" data-reveal>
				<span class="featured__ribbon"><?php echo esc_html( tsunagu_field( 'featured_ribbon', $d['featured_ribbon'] ) ); ?></span>
				<div class="featured__box">
					<div class="featured__brand">
						<img class="featured__logo" src="<?php echo esc_url( $featured_img ); ?>" alt="調速（チョーソク） 不動産調査アプリ">
					</div>
					<div class="featured__body">
						<p class="featured__product"><?php echo esc_html( tsunagu_field( 'featured_product', $d['featured_product'] ) ); ?><span class="featured__pill"><?php echo esc_html( tsunagu_field( 'featured_pill', $d['featured_pill'] ) ); ?></span></p>
						<p class="featured__text"><?php echo wp_kses_post( tsunagu_field( 'featured_text', $d['featured_text'] ) ); ?></p>
					</div>
					<a class="btn btn--white featured__btn" href="<?php echo esc_url( tsunagu_url( 'featured_btn_url' ) ); ?>">
						<span><?php echo esc_html( tsunagu_field( 'featured_btn_label', $d['featured_btn_label'] ) ); ?></span>
					</a>
				</div>
			</aside>
		</div>
		<div class="hero__skyline" aria-hidden="true"></div>
	</section>

	<!-- サービスメニュー（固定6枚） -->
	<section class="services" id="menu">
		<div class="container">

			<?php if ( ! empty( $topics ) ) : ?>
			<div class="topics" data-reveal>
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
				foreach ( range( 1, 6 ) as $n ) :
					$c      = $d['cards'][ $n ];
					$theme  = $c['theme'];
					$tag    = tsunagu_field( "card_{$n}_tag", $c['tag'] );
					$title  = tsunagu_field( "card_{$n}_title", $c['title'] );
					$desc   = tsunagu_field( "card_{$n}_desc", $c['desc'] );
					$btn1   = tsunagu_field( "card_{$n}_btn1_label", $c['btn1'] );
					$forms  = tsunagu_card_forms( $n, $c['forms'] );
					$btn2   = tsunagu_field( "card_{$n}_btn2_label", $c['btn2'] );
					$ptitle = tsunagu_field( "card_{$n}_popup_title", $c['popup_title'] );

					if ( $n === 4 ) {
						$pdfurl = tsunagu_file_url( 'card_4_popup_pdf', $c['popup_pdf'] );
						$popup  = '<object class="popup-pdf" data="' . esc_url( $pdfurl ) . '" type="application/pdf" title="' . esc_attr( $ptitle ) . '"><p class="popup-note">PDFをこの場で表示できない環境のようです。下のリンクからご覧ください。</p></object>'
							. '<p class="popup-note"><a href="' . esc_url( $pdfurl ) . '" target="_blank" rel="noopener">料金表PDFを新しいタブで開く →</a></p>';
					} else {
						$popup = tsunagu_field( "card_{$n}_popup_text", $c['popup'] );
					}
					?>
					<article class="service-card" data-theme="<?php echo esc_attr( $theme ); ?>" data-reveal>
						<span class="service-card__tag"><?php echo esc_html( $tag ); ?></span>
						<div class="service-card__head">
							<span class="service-card__icon"><?php echo tsunagu_card_icon( $n ); // phpcs:ignore ?></span>
							<h3 class="service-card__title"><?php echo wp_kses_post( $title ); ?></h3>
						</div>
						<p class="service-card__desc"><?php echo esc_html( $desc ); ?></p>
						<div class="service-card__actions actions--split">
							<?php if ( count( $forms ) >= 2 ) : ?>
								<div class="pdf-dropdown actions__primary">
									<button type="button" class="btn btn--solid pdf-trigger" aria-haspopup="true" aria-expanded="false">
										<span><?php echo esc_html( $btn1 ); ?></span>
										<svg class="pdf-caret" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									</button>
									<ul class="pdf-menu" role="menu" hidden>
										<?php foreach ( $forms as $f ) : ?>
											<li role="none"><a class="pdf-menu__item" role="menuitem" href="<?php echo esc_url( $f['url'] ); ?>"><?php echo esc_html( $f['label'] ); ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php else :
								$one_url = ! empty( $forms ) ? $forms[0]['url'] : '#'; ?>
								<a class="btn btn--solid actions__primary" href="<?php echo esc_url( $one_url ); ?>"><?php echo esc_html( $btn1 ); ?></a>
							<?php endif; ?>
							<button type="button" class="btn btn--outline actions__secondary popup-trigger" data-popup="popup-c<?php echo $n; ?>" data-popup-title="<?php echo esc_attr( $ptitle ); ?>"><?php echo esc_html( $btn2 ); ?></button>
						</div>
						<div class="popup-content" id="popup-c<?php echo $n; ?>" hidden><?php echo $popup; // phpcs:ignore ?></div>
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
