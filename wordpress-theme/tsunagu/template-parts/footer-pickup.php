<?php
/**
 * フッターの広告エリア（管理メニュー「広告」で登録）
 * PC 3枚／スマホ 1枚を表示し、超える分は main.js が自動スライドで入れ替える。
 * 広告が1件もなければ何も出力しない。
 * ※ 広告ブロッカーで消されないよう、クラス名に ad / banner を使わない。
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$items = tsunagu_get_ads();
if ( ! $items ) return;
?>
<section class="tsg-pickup" aria-label="広告">
	<div class="tsg-pickup__inner">
		<p class="tsg-pickup__label">PR</p>
		<div class="tsg-pickup__viewport" data-interval="4000">
			<ul class="tsg-pickup__track">
				<?php foreach ( $items as $item ) :
					$alt = trim( (string) get_post_meta( $item['image'], '_wp_attachment_image_alt', true ) );
					$img = wp_get_attachment_image( $item['image'], 'medium_large', false, array(
						'class'    => 'tsg-pickup__img',
						'alt'      => $alt !== '' ? $alt : '広告',
						'sizes'    => '300px',
						'loading'  => 'lazy',
						'decoding' => 'async',
					) );
					?>
					<li class="tsg-pickup__item">
						<?php if ( $item['url'] ) : ?>
							<a class="tsg-pickup__link" href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="sponsored noopener"><?php echo $img; // phpcs:ignore ?></a>
						<?php else : ?>
							<span class="tsg-pickup__link"><?php echo $img; // phpcs:ignore ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
