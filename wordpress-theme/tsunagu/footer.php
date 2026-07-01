<?php
/** 共通フッター */
if ( ! defined( 'ABSPATH' ) ) exit;
$d = tsunagu_defaults();
?>
<footer class="site-footer">
	<div class="container">
		<small class="site-footer__copy"><?php echo esc_html( tsunagu_field( 'footer_copyright', $d['footer_copyright'] ) ); ?></small>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
