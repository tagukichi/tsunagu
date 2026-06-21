<?php
/**
 * ヘルパー関数群
 * ACFがあればその値を、無ければ既定値（現行の表示内容）を返す。
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/** テキスト/HTML系：ACF値が空なら既定値を返す */
function tsunagu_field( $name, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$v = get_field( $name );
		if ( $v !== null && $v !== false && $v !== '' ) {
			return $v;
		}
	}
	return $default;
}

/** URL系：空なら # にフォールバック */
function tsunagu_url( $name, $default = '#' ) {
	$v = tsunagu_field( $name, '' );
	return ( $v !== '' ) ? $v : $default;
}

/** 画像系：ACF画像（array/URL/ID）→URL文字列。空なら既定URL */
function tsunagu_image_url( $name, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$v = get_field( $name );
		if ( is_array( $v ) && ! empty( $v['url'] ) ) return $v['url'];
		if ( is_string( $v ) && $v !== '' )           return $v;
		if ( is_numeric( $v ) ) {
			$u = wp_get_attachment_image_url( $v, 'full' );
			if ( $u ) return $u;
		}
	}
	return $default;
}

/** ファイル系（PDF等）：ACFファイル（array/URL/ID）→URL文字列。空なら既定URL */
function tsunagu_file_url( $name, $default = '' ) {
	return tsunagu_image_url( $name, $default ); // 取り出しロジックは画像と同じ
}

/** テーマ内アセットのURL */
function tsunagu_asset( $path ) {
	return get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) );
}

/**
 * カードの左ボタン用 PDFリスト取得
 * ACFリピーター card_{n}_pdfs があればそれを、無ければ既定配列を返す
 * 返り値: [ ['label'=>..., 'url'=>...], ... ]
 */
function tsunagu_card_pdfs( $n, $default = array() ) {
	$name = "card_{$n}_pdfs";
	if ( function_exists( 'have_rows' ) && have_rows( $name ) ) {
		$out = array();
		while ( have_rows( $name ) ) {
			the_row();
			$file = function_exists( 'get_sub_field' ) ? get_sub_field( 'pdf_file' ) : '';
			$url  = '#';
			if ( is_array( $file ) && ! empty( $file['url'] ) ) {
				$url = $file['url'];
			} elseif ( is_string( $file ) && $file !== '' ) {
				$url = $file;
			} elseif ( is_numeric( $file ) ) {
				$u = wp_get_attachment_url( $file );
				if ( $u ) $url = $u;
			}
			$out[] = array(
				'label' => get_sub_field( 'pdf_label' ),
				'url'   => $url,
			);
		}
		if ( ! empty( $out ) ) return $out;
	}
	return $default;
}

/**
 * TOPIC リスト取得
 * ACFリピーター topics（サブ topic_text）→ 文字列配列。無ければ既定配列
 */
function tsunagu_topics( $default = array() ) {
	if ( function_exists( 'have_rows' ) && have_rows( 'topics' ) ) {
		$out = array();
		while ( have_rows( 'topics' ) ) {
			the_row();
			$t = get_sub_field( 'topic_text' );
			if ( $t !== '' && $t !== null ) $out[] = $t;
		}
		if ( ! empty( $out ) ) return $out;
	}
	return $default;
}

/** ログインページのURL（template-login.php を割り当てた固定ページ）。無ければ wp-login.php */
function tsunagu_login_page_url() {
	$ids = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'template-login.php',
	) );
	if ( ! empty( $ids ) ) return get_permalink( $ids[0] );
	return wp_login_url();
}
