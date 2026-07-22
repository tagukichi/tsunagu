<?php
/**
 * Template Name: つなぐ フォームページ
 * 依頼カードの遷移先。ページ名＋説明＋ステップ（入力/確認/送信）＋CF7フォーム。
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
get_template_part( 'template-parts/content', 'form' );
get_footer();
