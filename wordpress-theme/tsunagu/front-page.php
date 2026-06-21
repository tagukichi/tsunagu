<?php
/**
 * フロントページ（静的フロントページに設定した場合に使用）
 * ※ ページに「つなぐ 依頼ページ」テンプレートを割り当てた場合は template-tsunagu.php が優先されます。
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
get_template_part( 'template-parts/content', 'tsunagu' );
get_footer();
