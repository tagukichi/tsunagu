<?php
/**
 * ACF フィールド定義（PHP登録）。
 * ACFが有効なら管理画面に自動で項目が現れます。未導入でもテーマは初期値で表示します。
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

	$F = function ( $name, $label, $type = 'text', $extra = array() ) {
		return array_merge( array(
			'key'   => 'field_tsg_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		), $extra );
	};
	$TAB = function ( $label ) {
		return array(
			'key'   => 'field_tsg_tab_' . sanitize_key( $label ),
			'label' => $label,
			'type'  => 'tab',
		);
	};

	/* ---------------- 依頼ページ ---------------- */
	$fields = array();

	$fields[] = $TAB( 'ヘッダー / 共通' );
	$fields[] = $F( 'site_logo', 'ロゴ画像（ヘッダー / フッター共通）', 'image', array( 'return_format' => 'array', 'instructions' => '未設定時はテーマ同梱ロゴを表示' ) );
	$fields[] = $F( 'header_btn_label', 'ヘッダーボタン ラベル', 'text', array( 'placeholder' => '利用規約・ダウンロード' ) );
	$fields[] = $F( 'header_btn_url', 'ヘッダーボタン リンクURL', 'url' );
	$fields[] = $F( 'footer_copyright', 'フッター コピーライト', 'text' );

	$fields[] = $TAB( 'ヒーロー' );
	$fields[] = $F( 'hero_badge', 'バッジ', 'text' );
	$fields[] = $F( 'hero_title', 'タイトル', 'text' );
	$fields[] = $F( 'hero_lead', 'リード文', 'textarea', array( 'rows' => 3 ) );

	$fields[] = $TAB( 'おすすめツール（調速）' );
	$fields[] = $F( 'featured_logo', 'ロゴ画像（調速 横ロゴ）', 'image', array( 'return_format' => 'array' ) );
	$fields[] = $F( 'featured_ribbon', 'リボン文言', 'text' );
	$fields[] = $F( 'featured_product', '製品名', 'text' );
	$fields[] = $F( 'featured_pill', 'ピル文言', 'text' );
	$fields[] = $F( 'featured_text', '説明文', 'textarea', array( 'rows' => 2 ) );
	$fields[] = $F( 'featured_btn_label', 'ボタン ラベル', 'text' );
	$fields[] = $F( 'featured_btn_url', 'ボタン URL', 'url' );

	foreach ( range( 1, 6 ) as $n ) {
		$fields[] = $TAB( "カード{$n}" );
		$fields[] = $F( "card_{$n}_tag", 'タグ', 'text' );
		$fields[] = $F( "card_{$n}_title", 'タイトル', 'text', array( 'instructions' => '改行は &lt;br&gt;、注釈は &lt;small&gt;〜&lt;/small&gt; が使えます' ) );
		$fields[] = $F( "card_{$n}_desc", '説明文', 'textarea', array( 'rows' => 2 ) );
		$fields[] = $F( "card_{$n}_btn1_label", '左ボタン ラベル', 'text' );
		$fields[] = $F( "card_{$n}_pdfs", '左ボタン PDF（1件＝直接表示 / 2件以上＝リスト）', 'repeater', array(
			'layout'       => 'table',
			'button_label' => 'PDFを追加',
			'sub_fields'   => array(
				array( 'key' => "field_tsg_card_{$n}_pdf_label", 'name' => 'pdf_label', 'label' => 'ラベル', 'type' => 'text' ),
				array( 'key' => "field_tsg_card_{$n}_pdf_file", 'name' => 'pdf_file', 'label' => 'PDF', 'type' => 'file', 'return_format' => 'array', 'mime_types' => 'pdf' ),
			),
		) );
		$fields[] = $F( "card_{$n}_btn2_label", '右ボタン ラベル', 'text' );
		$fields[] = $F( "card_{$n}_popup_title", '右ボタン ポップアップ 見出し', 'text' );
		if ( $n === 4 ) {
			$fields[] = $F( 'card_4_popup_pdf', '右ボタン ポップアップ PDF（料金表）', 'file', array( 'return_format' => 'array', 'mime_types' => 'pdf', 'instructions' => '「印刷を依頼する」で開くPDF。未設定はテーマ同梱の料金表PDF' ) );
		} else {
			$fields[] = $F( "card_{$n}_popup_text", '右ボタン ポップアップ 本文', 'wysiwyg', array( 'media_upload' => 1, 'tabs' => 'all' ) );
		}
	}

	$fields[] = $TAB( 'TOPIC' );
	$fields[] = $F( 'topics', 'TOPIC（「まとめて相談する」上に流れるお知らせ）', 'repeater', array(
		'layout'       => 'table',
		'button_label' => 'TOPICを追加',
		'sub_fields'   => array(
			array( 'key' => 'field_tsg_topic_text', 'name' => 'topic_text', 'label' => '文言', 'type' => 'text' ),
		),
	) );

	$fields[] = $TAB( '相談CTA' );
	$fields[] = $F( 'help_title', 'タイトル', 'text' );
	$fields[] = $F( 'help_desc', '説明文', 'textarea', array( 'rows' => 2 ) );
	$fields[] = $F( 'help_btn_label', 'ボタン ラベル', 'text' );
	$fields[] = $F( 'help_btn_url', 'ボタン URL', 'url' );

	acf_add_local_field_group( array(
		'key'      => 'group_tsunagu_page',
		'title'    => 'つなぐ依頼ページ',
		'fields'   => $fields,
		'location' => array(
			array( array( 'param' => 'post_template', 'operator' => '==', 'value' => 'template-tsunagu.php' ) ),
			array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ),
		),
		'position' => 'normal',
		'style'    => 'default',
		'active'   => true,
	) );

	/* ---------------- ログインページ ---------------- */
	acf_add_local_field_group( array(
		'key'      => 'group_tsunagu_login',
		'title'    => 'つなぐ ログイン',
		'fields'   => array(
			array( 'key' => 'field_tsg_login_site_logo', 'name' => 'site_logo', 'label' => 'ロゴ画像', 'type' => 'image', 'return_format' => 'array' ),
			array( 'key' => 'field_tsg_login_title', 'name' => 'login_title', 'label' => '見出し', 'type' => 'text' ),
			array( 'key' => 'field_tsg_login_lead', 'name' => 'login_lead', 'label' => 'リード文', 'type' => 'textarea', 'rows' => 2 ),
			array( 'key' => 'field_tsg_login_note', 'name' => 'login_note', 'label' => '下部の注記', 'type' => 'textarea', 'rows' => 2 ),
		),
		'location' => array(
			array( array( 'param' => 'post_template', 'operator' => '==', 'value' => 'template-login.php' ) ),
		),
		'active'   => true,
	) );
} );
