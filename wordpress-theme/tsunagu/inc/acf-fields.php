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
			// 日本語のタブ名でもキーが重複しないよう、名前のハッシュでキーを作る
			'key'   => 'field_tsg_tab_' . substr( md5( $label ), 0, 10 ),
			'label' => $label,
			'type'  => 'tab',
		);
	};

	/* ---------------- 依頼ページ ---------------- */
	$fields = array();
	$names  = array( 1 => '売却物件', 2 => '仕入・エンド情報', 3 => '工事', 4 => '名刺・チラシ', 5 => 'HP制作・DX支援', 6 => '調速サービス' );

	$fields[] = $TAB( 'ヘッダー / 共通' );
	$fields[] = $F( 'site_logo', 'ロゴ画像（ヘッダー共通）', 'image', array( 'return_format' => 'array', 'instructions' => '未設定時はテーマ同梱ロゴを表示' ) );
	$fields[] = $F( 'header_title', 'ページ名（ロゴの右に表示）', 'text', array( 'placeholder' => '会員限定依頼ページ' ) );
	$fields[] = $F( 'header_company_label', '「会社紹介」ボタン ラベル', 'text', array( 'placeholder' => '会社紹介', 'wrapper' => array( 'width' => '40' ) ) );
	$fields[] = $F( 'header_company_url', '「会社紹介」ボタン リンク先（LPのURL）', 'url', array( 'instructions' => '新しいタブで開きます。', 'wrapper' => array( 'width' => '60' ) ) );
	$fields[] = array(
		'key'     => 'field_tsg_header_ext_msg',
		'label'   => '「指定書式」ボタン（クリックで書式を選んでダウンロード）',
		'type'    => 'message',
		'message' => 'ボタンを押すと、下の2つの書式（Excel等）を選んでダウンロードできます。',
	);
	$fields[] = $F( 'header_forms_label', '「指定書式」ボタン ラベル', 'text', array( 'placeholder' => '指定書式' ) );
	$fields[] = $F( 'header_ext1_label', '書式1 ラベル', 'text', array( 'placeholder' => '買付証明書', 'wrapper' => array( 'width' => '40' ) ) );
	$fields[] = $F( 'header_ext1_file', '書式1 ファイル（Excel等）', 'file', array( 'return_format' => 'array', 'wrapper' => array( 'width' => '60' ) ) );
	$fields[] = $F( 'header_ext2_label', '書式2 ラベル', 'text', array( 'placeholder' => '売却合意書', 'wrapper' => array( 'width' => '40' ) ) );
	$fields[] = $F( 'header_ext2_file', '書式2 ファイル（Excel等）', 'file', array( 'return_format' => 'array', 'wrapper' => array( 'width' => '60' ) ) );
	$fields[] = $F( 'hero_contact_label', '問い合わせ先 ラベル（フッターに表示）', 'text', array( 'placeholder' => 'お問い合わせ：', 'wrapper' => array( 'width' => '40' ) ) );
	$fields[] = $F( 'hero_contact_email', '問い合わせ先 メールアドレス（フッターに表示）', 'email', array( 'placeholder' => 'info@example.com', 'wrapper' => array( 'width' => '60' ) ) );
	$fields[] = $F( 'footer_copyright', 'フッター コピーライト', 'text' );

	foreach ( range( 1, 6 ) as $n ) {
		$fields[] = $TAB( "カード{$n}：{$names[ $n ]}" );
		$fields[] = $F( "card_{$n}_tag", 'タグ', 'text' );
		$fields[] = $F( "card_{$n}_title", 'タイトル', 'text', array( 'instructions' => '改行は &lt;br&gt;、注釈は &lt;small&gt;〜&lt;/small&gt; が使えます' ) );
		$fields[] = $F( "card_{$n}_desc", '説明文', 'textarea', array( 'rows' => 2 ) );
		$fields[] = $F( "card_{$n}_btn1_label", '左ボタン（大）ラベル', 'text' );
		$fields[] = array(
			'key'     => "field_tsg_card_{$n}_formmsg",
			'label'   => '左ボタンのメニュー（最大4件）',
			'type'    => 'message',
			'message' => '1件だけ設定するとクリックで直接その先へ、2件以上でクリック時にメニュー（プルダウン）が開きます。<br>遷移先は「ページ」「外部URL」「PDF」のどれか1つを設定してください（複数ある場合は PDF → 外部URL → ページ の順で優先）。外部URLとPDFは新しいタブで開きます。',
		);
		for ( $i = 1; $i <= 4; $i++ ) {
			$opt = ( $i === 1 ) ? '' : '（任意）';
			$fields[] = $F( "card_{$n}_form{$i}_label", "メニュー{$i} ラベル{$opt}", 'text', array( 'wrapper' => array( 'width' => '25' ) ) );
			$fields[] = $F( "card_{$n}_form{$i}_page", "メニュー{$i} ページ", 'page_link', array(
				'post_type'     => array( 'page' ),
				'allow_null'    => 1,
				'multiple'      => 0,
				'return_format' => 'url',
				'instructions'  => ( $i === 1 ? 'サイト内のフォームページなど' : '' ),
				'wrapper'       => array( 'width' => '25' ),
			) );
			$fields[] = $F( "card_{$n}_form{$i}_url", "メニュー{$i} 外部URL", 'url', array( 'instructions' => ( $i === 1 ? '他サイト（ログイン画面など）' : '' ), 'wrapper' => array( 'width' => '25' ) ) );
			$fields[] = $F( "card_{$n}_form{$i}_file", "メニュー{$i} PDF", 'file', array( 'return_format' => 'array', 'mime_types' => 'pdf', 'instructions' => ( $i === 1 ? '資料・料金表など' : '' ), 'wrapper' => array( 'width' => '25' ) ) );
		}
		$fields[] = $F( "card_{$n}_btn2_label", '右ボタン（小）ラベル', 'text' );
		$fields[] = array(
			'key'     => "field_tsg_card_{$n}_btn2msg",
			'label'   => '右ボタンのリンク先（任意）',
			'type'    => 'message',
			'message' => ( in_array( $n, array( 5, 6 ), true )
				? 'このカードの右ボタンは「申し込み」として、リンク先を開きます。サイト内の申込フォームはページ欄、外部のフォームはURL欄（新しいタブで開く）に設定してください（URL欄が優先）。'
				: 'リンク先を設定すると、右ボタンはポップアップではなくリンクになります（URL欄は新しいタブで開きます。URL欄が優先）。未設定ならポップアップを表示します。' ),
		);
		$fields[] = $F( "card_{$n}_btn2_url", '右ボタン 外部URL（Googleフォーム等）', 'url', array( 'wrapper' => array( 'width' => '50' ) ) );
		$fields[] = $F( "card_{$n}_btn2_page", '右ボタン ページ（サイト内フォーム等）', 'page_link', array(
			'post_type'     => array( 'page' ),
			'allow_null'    => 1,
			'multiple'      => 0,
			'return_format' => 'url',
			'wrapper'       => array( 'width' => '50' ),
		) );
		$fields[] = $F( "card_{$n}_popup_title", '右ボタン ポップアップ 見出し', 'text' );
		$fields[] = $F( "card_{$n}_popup_pdf", '右ボタン ポップアップ PDF（料金表など・任意）', 'file', array(
			'return_format' => 'array',
			'mime_types'    => 'pdf',
			'instructions'  => ( $n === 4 ? 'PDFを設定するとポップアップ内にPDFを表示します。未設定時はテーマ同梱の料金表PDFを表示します。' : 'PDFを設定するとポップアップ内にPDFを表示します（下の本文を入力した場合は、本文の下にPDFを表示）。' ),
		) );
		$fields[] = $F( "card_{$n}_popup_text", '右ボタン ポップアップ 本文', 'wysiwyg', array( 'media_upload' => 1, 'tabs' => 'all' ) );
	}

	$fields[] = $TAB( 'カードの並び順' );
	$fields[] = array(
		'key'     => 'field_tsg_cards_order_msg',
		'label'   => '並び順の指定方法',
		'type'    => 'message',
		'message' => 'カード番号（各タブ名の番号）をカンマ区切りで入力します。PCは左上から右へ、スマホは上から順に並びます。<br>カード番号：1 売却物件／2 仕入・エンド情報／3 工事／4 名刺・チラシ／5 HP制作・DX支援／6 調速サービス<br>入力しなかった番号は末尾に並びます。',
	);
	$fields[] = $F( 'cards_order_pc', 'PCの並び順', 'text', array( 'placeholder' => '6,3,4,5,1,2', 'wrapper' => array( 'width' => '50' ) ) );
	$fields[] = $F( 'cards_order_sp', 'スマホの並び順', 'text', array( 'placeholder' => '1,2,6,3,4,5', 'wrapper' => array( 'width' => '50' ) ) );

	$fields[] = $TAB( 'TOPIC' );
	$fields[] = $F( 'topics_text', 'TOPIC（1行に1件）', 'textarea', array(
		'rows'         => 4,
		'instructions' => 'カードの上に流れるお知らせ。1行＝1件。例）1棟案件アパート求む',
		'placeholder'  => "1棟案件アパート求む\nボロ戸建て投資家に提案可能\n500万円以下の案件は是非！",
	) );

	acf_add_local_field_group( array(
		'key'      => 'group_tsunagu_page',
		'title'    => 'つなぐ依頼ページ',
		'fields'   => $fields,
		// 依頼ページテンプレート、フロントページ、またはログイン/フォーム以外の任意の固定ページで表示
		'location' => array(
			array( array( 'param' => 'post_template', 'operator' => '==', 'value' => 'template-tsunagu.php' ) ),
			array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ),
			array(
				array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
				array( 'param' => 'post_template', 'operator' => '!=', 'value' => 'template-login.php' ),
				array( 'param' => 'post_template', 'operator' => '!=', 'value' => 'template-form.php' ),
			),
		),
		'position' => 'normal',
		'style'    => 'default',
		'active'   => true,
	) );

	/* ---------------- フォームページ ---------------- */
	acf_add_local_field_group( array(
		'key'      => 'group_tsunagu_form',
		'title'    => 'フォームページ',
		'fields'   => array(
			array(
				'key'          => 'field_tsg_form_desc',
				'name'         => 'form_desc',
				'label'        => '説明文（フォーム上部）',
				'type'         => 'textarea',
				'rows'         => 3,
				'instructions' => 'ページ名の下に表示する案内文。未入力時は既定文を表示します。',
			),
			array(
				'key'          => 'field_tsg_form_shortcode',
				'name'         => 'form_shortcode',
				'label'        => 'Contact Form 7 ショートコード',
				'type'         => 'textarea',
				'rows'         => 3,
				'instructions' => 'CF7で作成したフォームのショートコード（例： [contact-form-7 id="123" title="お問い合わせ"] ）をそのまま貼り付けてください。',
				'placeholder'  => '[contact-form-7 id="123" title="フォーム"]',
			),
			array(
				'key'          => 'field_tsg_form_thanks_title',
				'name'         => 'form_thanks_title',
				'label'        => 'サンクス（送信完了）見出し',
				'type'         => 'text',
				'instructions' => '送信完了時に表示する見出し。',
			),
			array(
				'key'          => 'field_tsg_form_thanks_text',
				'name'         => 'form_thanks_text',
				'label'        => 'サンクス（送信完了）本文',
				'type'         => 'textarea',
				'rows'         => 3,
				'instructions' => '送信完了時に画面に表示するお礼メッセージ。',
			),
			array(
				'key'          => 'field_tsg_form_redirect_page',
				'name'         => 'form_redirect_page',
				'label'        => 'サンクスページへ遷移（任意）',
				'type'         => 'page_link',
				'post_type'    => array( 'page' ),
				'allow_null'   => 1,
				'multiple'     => 0,
				'instructions' => '別の固定ページをサンクス画面にしたい場合はここで選択（GA計測などで別URLが必要な場合）。未設定なら上記メッセージをこの画面に表示します。',
			),
		),
		'location' => array(
			array( array( 'param' => 'post_template', 'operator' => '==', 'value' => 'template-form.php' ) ),
		),
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
