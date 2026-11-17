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
 * カードの左ボタン用メニュー（リンク）リスト取得
 * （ACF無料版対応：固定スロット card_{n}_form1..4）
 * 遷移先は「PDF」→「外部URL」→「ページ選択」の順で優先。PDFと外部URLは新しいタブで開く。
 * 返り値: [ ['label'=>..., 'url'=>..., 'external'=>bool], ... ]。未入力なら既定配列。
 * 1件 = クリックで直接遷移／2件以上 = プルダウン表示。
 */
function tsunagu_card_forms( $n, $default = array() ) {
	if ( function_exists( 'get_field' ) ) {
		$out = array();
		for ( $i = 1; $i <= 4; $i++ ) {
			$label = get_field( "card_{$n}_form{$i}_label" );
			// ページ選択（Page Link, return_format=url）。配列で返る場合は先頭を採用。
			$page = get_field( "card_{$n}_form{$i}_page" );
			if ( is_array( $page ) ) $page = reset( $page );
			$page = is_string( $page ) ? $page : '';
			$url  = get_field( "card_{$n}_form{$i}_url" );
			$url  = is_string( $url ) ? trim( $url ) : '';
			$file = tsunagu_extract_file_url( get_field( "card_{$n}_form{$i}_file" ) );

			if ( $file !== '' )     { $href = $file; $external = true; }
			elseif ( $url !== '' )  { $href = $url;  $external = true; }
			elseif ( $page !== '' ) { $href = $page; $external = false; }
			else                    { $href = '';    $external = false; }

			$has_label = ( $label !== '' && $label !== null );
			if ( $has_label || $href !== '' ) {
				$out[] = array(
					'label'    => $has_label ? $label : '依頼',
					'url'      => $href !== '' ? $href : '#',
					'external' => $external,
				);
			}
		}
		if ( ! empty( $out ) ) return $out;
	}
	return $default;
}

/** 新しいタブで開くリンク用の属性 */
function tsunagu_link_target( $item ) {
	return ! empty( $item['external'] ) ? ' target="_blank" rel="noopener"' : '';
}

/**
 * カードの並び順（カード番号の配列）
 * 「6,3,4,5,1,2」のようなカンマ区切りを解釈。全角数字も可。抜けた番号は末尾に補う。
 */
function tsunagu_card_order( $name, $default ) {
	$raw = (string) tsunagu_field( $name, $default );
	if ( function_exists( 'mb_convert_kana' ) ) $raw = mb_convert_kana( $raw, 'n', 'UTF-8' );
	$order = array();
	foreach ( preg_split( '/[^0-9]+/', $raw ) as $v ) {
		$v = (int) $v;
		if ( $v >= 1 && $v <= 6 && ! in_array( $v, $order, true ) ) $order[] = $v;
	}
	foreach ( range( 1, 6 ) as $v ) {
		if ( ! in_array( $v, $order, true ) ) $order[] = $v;
	}
	return $order;
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
