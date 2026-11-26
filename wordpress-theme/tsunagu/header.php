<?php
/** 共通ヘッダー（サイトヘッダー付き） */
if ( ! defined( 'ABSPATH' ) ) exit;
$d       = tsunagu_defaults();
$logo    = tsunagu_image_url( 'site_logo', tsunagu_asset( 'img/tsunaglogo.png' ) );
$title   = tsunagu_field( 'header_title', $d['header_title'] );
$co_l    = tsunagu_field( 'header_company_label', $d['header_company_label'] );
$co_u    = tsunagu_field( 'header_company_url', '' );
$forms_l = tsunagu_field( 'header_forms_label', $d['header_forms_label'] );
// 「指定書式」のメニュー（ファイル未設定でも、設定場所が分かるよう表示する）
$sheets = array();
foreach ( array( 1, 2 ) as $i ) {
	$label = tsunagu_field( "header_ext{$i}_label", $d[ "header_ext{$i}_label" ] );
	if ( $label ) {
		$sheets[] = array( 'label' => $label, 'url' => tsunagu_file_url( "header_ext{$i}_file", '' ) ?: '#' );
	}
}
// トップページではページ名を見出し（h1）に
$is_top    = ( is_front_page() || is_page_template( 'template-tsunagu.php' ) );
$title_tag = $is_top ? 'h1' : 'p';
// TOPIC：トップページのヘッダー内（ロゴとボタンの間）に表示。PCのみ（タブレット・スマホはカードの上に表示）
$header_topics = $is_top ? tsunagu_topics( $d['topics'] ) : array();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="container site-header__inner">
		<div class="site-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" title="TOPページへ戻る">
				<img class="site-logo__img" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="500" height="500">
				<span class="site-logo__caption">TOPページへ戻る</span>
			</a>
			<?php if ( $title ) : ?>
				<<?php echo $title_tag; ?> class="site-title"><?php echo esc_html( $title ); ?></<?php echo $title_tag; ?>>
			<?php endif; ?>
		</div>
		<?php if ( $header_topics ) : ?>
		<div class="topics topics--header">
			<span class="topics__label">TOPIC</span>
			<div class="topics__viewport">
				<ul class="topics__track">
					<?php foreach ( $header_topics as $t ) : ?>
						<li class="topics__item"><?php echo esc_html( $t ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<button type="button" class="header-toggle" aria-label="メニューを開く" aria-expanded="false" aria-controls="header-nav">
			<span class="header-toggle__bar"></span>
		</button>
		<nav class="header-actions" id="header-nav">
			<?php if ( $co_l ) : ?>
			<a href="<?php echo esc_url( $co_u ?: '#' ); ?>" class="btn-header btn-header--ghost" target="_blank" rel="noopener">
				<span><?php echo esc_html( $co_l ); ?></span>
			</a>
			<?php endif; ?>
			<?php if ( $sheets ) : ?>
			<div class="pdf-dropdown header-dropdown">
				<button type="button" class="btn-header btn-header--ghost pdf-trigger" aria-haspopup="true" aria-expanded="false">
					<span><?php echo esc_html( $forms_l ); ?></span>
					<svg class="pdf-caret" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>
				<ul class="pdf-menu" role="menu" hidden>
					<?php foreach ( $sheets as $sheet ) : ?>
						<li role="none"><a class="pdf-menu__item" role="menuitem" href="<?php echo esc_url( $sheet['url'] ); ?>" download><?php echo esc_html( $sheet['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wp_logout_url( tsunagu_login_page_url() ) ); ?>" class="btn-header btn-header--logout">
				<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M15 17l5-5-5-5" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M20 12H9" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
					<path d="M12 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span>ログアウト</span>
			</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
