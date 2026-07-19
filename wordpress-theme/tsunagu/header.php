<?php
/** 共通ヘッダー（サイトヘッダー付き） */
if ( ! defined( 'ABSPATH' ) ) exit;
$d      = tsunagu_defaults();
$logo   = tsunagu_image_url( 'site_logo', tsunagu_asset( 'img/tsunaglogo.png' ) );
$ext1_l = tsunagu_field( 'header_ext1_label', $d['header_ext1_label'] );
$ext1_u = tsunagu_file_url( 'header_ext1_file', '' );
$ext2_l = tsunagu_field( 'header_ext2_label', $d['header_ext2_label'] );
$ext2_u = tsunagu_file_url( 'header_ext2_file', '' );
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
			<?php if ( $ext1_l ) : ?>
			<a href="<?php echo esc_url( $ext1_u ?: '#' ); ?>" class="btn-header btn-header--ghost" download>
				<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<rect x="4" y="4" width="16" height="16" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/>
					<path d="M9 9l6 6M15 9l-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
				</svg>
				<span><?php echo esc_html( $ext1_l ); ?></span>
			</a>
			<?php endif; ?>
			<?php if ( $ext2_l ) : ?>
			<a href="<?php echo esc_url( $ext2_u ?: '#' ); ?>" class="btn-header btn-header--ghost" download>
				<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<rect x="4" y="4" width="16" height="16" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/>
					<path d="M9 9l6 6M15 9l-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
				</svg>
				<span><?php echo esc_html( $ext2_l ); ?></span>
			</a>
			<?php endif; ?>
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
