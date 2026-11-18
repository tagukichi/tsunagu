<?php
/** 共通フッター */
if ( ! defined( 'ABSPATH' ) ) exit;
$d = tsunagu_defaults();
?>
<footer class="site-footer">
	<?php get_template_part( 'template-parts/footer', 'pickup' ); ?>
	<div class="container">
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
