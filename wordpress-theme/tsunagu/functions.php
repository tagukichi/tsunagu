<?php
/**
 * つなぐ依頼ページ テーマ functions
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TSUNAGU_VER', '1.0.0' );

require_once get_theme_file_path( 'inc/helpers.php' );
require_once get_theme_file_path( 'inc/defaults.php' );
require_once get_theme_file_path( 'inc/acf-fields.php' );

/* テーマ基本設定 */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
} );

/* CSS / JS / フォント読み込み */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'tsunagu-fonts',
		'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'tsunagu-main', tsunagu_asset( 'css/style.css' ), array(), TSUNAGU_VER );
	wp_enqueue_script( 'tsunagu-main', tsunagu_asset( 'js/main.js' ), array(), TSUNAGU_VER, true );
} );

/* ログインページに body クラスを付与 */
add_filter( 'body_class', function ( $classes ) {
	if ( is_page_template( 'template-login.php' ) ) {
		$classes[] = 'login-page';
	}
	return $classes;
} );

/* 会員（記事編集権限のないユーザー＝購買者など）にはフロントの WP管理バーを非表示。
   管理者・編集者には従来どおり表示。 */
add_filter( 'show_admin_bar', function ( $show ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false;
	}
	return $show;
} );

/* =========================================================================
   会員限定アクセス：未ログインの訪問者はログインページへリダイレクト
   ========================================================================= */
add_action( 'template_redirect', function () {
	if ( is_user_logged_in() ) return;

	// ログインページ自身は除外
	if ( is_page_template( 'template-login.php' ) ) return;

	// 管理画面・ログイン・REST・AJAX・XML/robots などは除外
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$allow = array( 'wp-login.php', 'wp-register.php', '/wp-admin', '/wp-json', 'admin-ajax.php', 'wp-cron.php', '.xml', 'robots.txt', 'favicon.ico' );
	foreach ( $allow as $a ) {
		if ( strpos( $uri, $a ) !== false ) return;
	}

	$login  = tsunagu_login_page_url();
	$target = add_query_arg( 'redirect_to', rawurlencode( home_url( $uri ) ), $login );
	wp_safe_redirect( $target );
	exit;
}, 1 );

/* ログアウト後は全ユーザー共通でログインページへ */
add_action( 'wp_logout', function () {
	wp_safe_redirect( add_query_arg( 'loggedout', 'true', tsunagu_login_page_url() ) );
	exit;
}, 1 );

/* wp_logout_url() の戻り先もログインページに統一 */
add_filter( 'logout_url', function ( $logout_url ) {
	$url = remove_query_arg( 'redirect_to', $logout_url );
	return add_query_arg( 'redirect_to', rawurlencode( tsunagu_login_page_url() ), $url );
}, 10, 1 );

/* ログイン失敗時、カスタムログインページへエラー付きで戻す */
add_action( 'wp_login_failed', function ( $username ) {
	$referrer = wp_get_referer();
	if ( $referrer && strpos( $referrer, 'wp-login.php' ) === false && strpos( $referrer, 'wp-admin' ) === false ) {
		wp_safe_redirect( add_query_arg( 'login', 'failed', tsunagu_login_page_url() ) );
		exit;
	}
}, 10, 1 );

/* ACF 未導入時に管理画面で案内 */
add_action( 'admin_notices', function () {
	if ( ! class_exists( 'ACF' ) ) {
		echo '<div class="notice notice-warning"><p><strong>つなぐテーマ：</strong> プラグイン「Advanced Custom Fields（ACF）」を有効化すると、各種文言・リンク・PDF・ポップアップ・TOPICを管理画面から編集できます。未導入でも初期内容で表示されます。</p></div>';
	}
} );
