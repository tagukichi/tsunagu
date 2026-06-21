<?php
/** 共通ヘッダー（サイトヘッダー付き） */
if ( ! defined( 'ABSPATH' ) ) exit;
$d    = tsunagu_defaults();
$logo = tsunagu_image_url( 'site_logo', tsunagu_asset( 'img/tsunaglogo.png' ) );
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
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
			<img class="site-logo__img" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="500" height="500">
		</a>
		<nav class="header-actions">
			<a href="<?php echo esc_url( tsunagu_url( 'header_btn_url' ) ); ?>" class="btn-header btn-header--solid">
				<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M12 4v10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
					<path d="M8 11l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M5 19h14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
				</svg>
				<span><?php echo esc_html( tsunagu_field( 'header_btn_label', $d['header_btn_label'] ) ); ?></span>
			</a>
		</nav>
	</div>
</header>
