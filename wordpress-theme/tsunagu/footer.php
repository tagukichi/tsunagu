<?php
/** 共通フッター */
if ( ! defined( 'ABSPATH' ) ) exit;
$d    = tsunagu_defaults();
$logo = tsunagu_image_url( 'site_logo', tsunagu_asset( 'img/tsunaglogo.png' ) );
?>
<footer class="site-footer">
	<div class="container">
		<img class="site-footer__logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="500" height="500">
		<small class="site-footer__copy"><?php echo esc_html( tsunagu_field( 'footer_copyright', $d['footer_copyright'] ) ); ?></small>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
