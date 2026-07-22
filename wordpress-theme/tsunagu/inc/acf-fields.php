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
	$fields[] = $F( 'site_logo', 'ロゴ画像（ヘッダー共通）', 'image', array( 'return_format' => 'array', 'instructions' => '未設定時はテーマ同梱ロゴを表示' ) );
	$fields[] = array(
		'key'     => 'field_tsg_header_ext_msg',
		'label'   => 'ヘッダー左側の追加ボタン（Excel等をクリックでダウンロード）',
		'type'    => 'message',
		'message' => 'ラベルとファイル（メディアにアップしたExcel等）を設定するとヘッダーに表示され、クリックでダウンロードされます。ファイル未設定の場合はリンク先が # になります。',
	);
	$fields[] = $F( 'header_ext1_label', '追加ボタン1 ラベル', 'text', array( 'placeholder' => '買付証明書' ) );
	$fields[] = $F( 'header_ext1_file', '追加ボタン1 ファイル（Excel等）', 'file', array( 'return_format' => 'array' ) );
	$fields[] = $F( 'header_ext2_label', '追加ボタン2 ラベル', 'text', array( 'placeholder' => '売却合意書' ) );
	$fields[] = $F( 'header_ext2_file', '追加ボタン2 ファイル（Excel等）', 'file', array( 'return_format' => 'array' ) );
	$fields[] = $F( 'header_btn_label', 'ヘッダーメインボタン ラベル', 'text', array( 'placeholder' => '利用規約・ダウンロード' ) );
	$fields[] = $F( 'header_btn_url', 'ヘッダーメインボタン リンクURL', 'url' );
	$fields[] = $F( 'footer_copyright', 'フッター コピーライト', 'text' );

	$fields[] = $TAB( 'ヒーロー' );
	$fields[] = $F( 'hero_badge', 'バッジ', 'text' );
	$fields[] = $F( 'hero_title', 'タイトル', 'text' );
	$fields[] = $F( 'hero_contact_label', '問い合わせ先 ラベル', 'text', array( 'placeholder' => '問い合わせ先:' ) );
	$fields[] = $F( 'hero_contact_email', '問い合わせ先 メールアドレス', 'email', array( 'placeholder' => 'info@example.com', 'instructions' => 'ヒーローに表示するお問い合わせ用メールアドレス。クリックでメーラーが起動します。' ) );

	$fields[] = $TAB( 'おすすめツール（調速）' );
	$fields[] = $F( 'featured_logo', '調速 ロゴ画像（横ロゴ／PNG推奨）', 'image', array(
		'return_format' => 'array',
		'preview_size'  => 'medium',
		'instructions'  => 'ヒーロー右のおすすめツールバナーに表示される「調速」ロゴ画像です。未設定時はテーマ同梱のロゴを表示します。',
	) );
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
		$fields[] = $F( "card_{$n}_btn1_label", '左ボタン（大）ラベル', 'text', array( 'placeholder' => ( $n <= 3 ) ? '依頼' : '面談調整' ) );
		$fields[] = array(
			'key'     => "field_tsg_card_{$n}_formmsg",
			'label'   => '左ボタンの遷移先フォーム（最大4件）',
			'type'    => 'message',
			'message' => '「依頼」ボタンの遷移先です。「フォームページ」テンプレートで作成した固定ページを選んでください。1件だけ設定するとクリックで直接そのページへ遷移、2件以上でクリック時にリスト（プルダウン）が開き、選んだフォームへ遷移します。',
		);
		for ( $i = 1; $i <= 4; $i++ ) {
			$opt = ( $i === 1 ) ? '' : '（任意）';
			$fields[] = $F( "card_{$n}_form{$i}_label", "フォーム{$i} ラベル{$opt}", 'text', array( 'instructions' => ( $i === 1 ? 'プルダウンに表示する文言（例：自社物件の売却依頼）。1件のみ設定時はリストを出さず直接遷移します。' : '' ) ) );
			$fields[] = $F( "card_{$n}_form{$i}_page", "フォーム{$i} 遷移先ページ{$opt}", 'page_link', array(
				'post_type'     => array( 'page' ),
				'allow_null'    => 1,
				'multiple'      => 0,
				'return_format' => 'url',
				'instructions'  => '「フォームページ」テンプレートで作成した固定ページを選択してください。',
			) );
		}
		$fields[] = $F( "card_{$n}_btn2_label", '右ボタン（小）ラベル', 'text', array( 'placeholder' => ( $n === 4 ) ? 'ご料金' : '詳細' ) );
		$fields[] = $F( "card_{$n}_popup_title", '右ボタン ポップアップ 見出し', 'text' );
		if ( $n === 4 ) {
			$fields[] = $F( 'card_4_popup_pdf', '右ボタン ポップアップ PDF（料金表）', 'file', array( 'return_format' => 'array', 'mime_types' => 'pdf', 'instructions' => '「ご料金」で開くPDF。未設定はテーマ同梱の料金表PDF' ) );
		} else {
			$fields[] = $F( "card_{$n}_popup_text", '右ボタン ポップアップ 本文', 'wysiwyg', array( 'media_upload' => 1, 'tabs' => 'all' ) );
		}
	}

	$fields[] = $TAB( 'TOPIC' );
	$fields[] = $F( 'topics_text', 'TOPIC（1行に1件）', 'textarea', array(
		'rows'         => 4,
		'instructions' => '「まとめて相談する」の上に流れるお知らせ。1行＝1件。例）1棟案件アパート求む',
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
