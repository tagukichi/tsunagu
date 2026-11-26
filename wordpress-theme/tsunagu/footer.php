<?php
/** 共通フッター */
if ( ! defined( 'ABSPATH' ) ) exit;
$d = tsunagu_defaults();
// TOPIC：トップページのみ。PC・タブレットはフッターに表示（スマホはカードの上に表示）
$is_top = ( is_front_page() || is_page_template( 'template-tsunagu.php' ) );
$topics = $is_top ? tsunagu_topics( $d['topics'] ) : array();
?>
<footer class="site-footer">
	<?php if ( $topics ) : ?>
	<div class="site-footer__topics">
		<div class="topics topics--footer">
			<span class="topics__label">TOPIC</span>
			<div class="topics__viewport">
				<ul class="topics__track">
					<?php foreach ( $topics as $t ) : ?>
						<li class="topics__item"><?php echo esc_html( $t ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php get_template_part( 'template-parts/footer', 'pickup' ); ?>
	<div class="container site-footer__bottom">
		<?php $mail = tsunagu_field( 'hero_contact_email', $d['hero_contact_email'] ); ?>
		<?php if ( $mail ) : ?>
		<p class="site-footer__contact">
			<span><?php echo esc_html( tsunagu_field( 'hero_contact_label', $d['hero_contact_label'] ) ); ?></span>
			<a href="mailto:<?php echo esc_attr( $mail ); ?>"><?php echo esc_html( $mail ); ?></a>
		</p>
		<?php endif; ?>
		<small class="site-footer__copy"><?php echo esc_html( tsunagu_field( 'footer_copyright', $d['footer_copyright'] ) ); ?></small>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
