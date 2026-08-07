<?php
/** 共通フッター */
if ( ! defined( 'ABSPATH' ) ) exit;
$d = tsunagu_defaults();
?>
<footer class="site-footer">
	<?php if ( is_user_logged_in() ) : ?>
	<div class="site-footer__logout">
		<a class="logout-btn" href="<?php echo esc_url( wp_logout_url( tsunagu_login_page_url() ) ); ?>">
			<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
				<path d="M15 17l5-5-5-5" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M20 12H9" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
				<path d="M12 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<span>ログアウト</span>
		</a>
	</div>
	<?php endif; ?>
	<div class="container">
		<small class="site-footer__copy"><?php echo esc_html( tsunagu_field( 'footer_copyright', $d['footer_copyright'] ) ); ?></small>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
