<?php
/**
 * Template Name: つなぐ ログイン
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// すでにログイン済みならトップへ
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}

get_header( 'login' );
get_template_part( 'template-parts/content', 'login' );
get_footer( 'login' );
