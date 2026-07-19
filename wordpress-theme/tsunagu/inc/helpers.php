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

/** ファイル/画像ACF値（array/URL/ID）からURL文字列を取り出す */
function tsunagu_extract_file_url( $v ) {
	if ( is_array( $v ) && ! empty( $v['url'] ) ) return $v['url'];
	if ( is_string( $v ) && $v !== '' )           return $v;
	if ( is_numeric( $v ) ) {
		$u = wp_get_attachment_url( $v );
		if ( $u ) return $u;
	}
	return '';
}

/**
 * カードの左ボタン用「フォームリンク」リスト取得
 * （ACF無料版対応：固定スロット card_{n}_form1..4 の label / url）
 * 返り値: [ ['label'=>..., 'url'=>...], ... ]。未入力なら既定配列。
 * 1件 = クリックで直接そのフォームへ遷移／2件以上 = プルダウン表示。
 */
function tsunagu_card_forms( $n, $default = array() ) {
	if ( function_exists( 'get_field' ) ) {
		$out = array();
		for ( $i = 1; $i <= 4; $i++ ) {
			$label = get_field( "card_{$n}_form{$i}_label" );
			$url   = get_field( "card_{$n}_form{$i}_url" );
			$has_label = ( $label !== '' && $label !== null );
			$has_url   = ( $url !== '' && $url !== null );
			if ( $has_label || $has_url ) {
				$out[] = array(
					'label' => $has_label ? $label : '依頼',
					'url'   => $has_url ? $url : '#',
				);
			}
		}
		if ( ! empty( $out ) ) return $out;
	}
	return $default;
}

/**
 * TOPIC リスト取得（ACF無料版対応：テキストエリア topics_text、1行＝1件）
 */
function tsunagu_topics( $default = array() ) {
	if ( function_exists( 'get_field' ) ) {
		$raw = get_field( 'topics_text' );
		if ( is_string( $raw ) && trim( $raw ) !== '' ) {
			$out = array();
			foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
				$line = trim( $line );
				if ( $line !== '' ) $out[] = $line;
			}
			if ( ! empty( $out ) ) return $out;
		}
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
