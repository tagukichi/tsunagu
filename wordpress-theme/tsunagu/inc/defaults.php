<?php
/**
 * 既定コンテンツ（ACF未入力時のフォールバック＝現行の静的サイトと同じ内容）
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function tsunagu_defaults() {
	static $d = null;
	if ( $d !== null ) return $d;

	$pdf = tsunagu_asset( 'pdf/sample.pdf' );

	$d = array(
		// ヘッダー / 共通
		'header_btn_label' => '利用規約・ダウンロード',
		'footer_copyright' => '© TSUNAGU. All rights reserved.',

		// ヒーロー
		'hero_badge' => '会員限定ポータルサイト',
		'hero_title' => 'つなぐ依頼ページ',
		'hero_lead'  => 'つなぐ登録会社の皆様からの各種ご依頼・ご相談を受け付けるページです。',

		// おすすめツール（調速）
		'featured_ribbon'    => 'おすすめツール',
		'featured_product'   => '不動産調査AIツール',
		'featured_pill'      => '調査・相場確認を効率化',
		'featured_text'      => '「10秒検索」で物件概要を簡易調査、提案資料を「AI」が自動作成',
		'featured_btn_label' => '利用案内を見る',

		// TOPIC
		'topics' => array(
			'1棟案件アパート求む',
			'ボロ戸建て投資家に提案可能',
			'500万円以下の案件は是非！',
		),

		// ログイン
		'login_title' => '会員ログイン',
		'login_lead'  => 'つなぐ登録会社の会員専用ページです。<br>登録済みのID・パスワードでログインしてください。',
		'login_note'  => 'アカウントをお持ちでない場合は、つなぐ事務局までお問い合わせください。<br>会員登録は事務局にて承ります。',

		// サービスカード（固定6枚）
		'cards' => array(
			1 => array(
				'theme' => 'blue',
				'tag'   => '売却の相談・依頼',
				'title' => '売却物件の依頼',
				'desc'  => '売却したい物件のご相談・ご依頼を受け付けています。',
				'btn1'  => '依頼シート',
				'pdfs'  => array(
					array( 'label' => '自社物件の売却依頼', 'url' => $pdf ),
					array( 'label' => '仲介物件の売却依頼', 'url' => $pdf ),
				),
				'btn2'        => '相談する',
				'popup_title' => '売却のご相談について',
				'popup'       => '<p>自社物件の売却や、一般のお客様から預かった物件の売却依頼はこちらからご依頼ください。</p><ul><li>水面下で進めたい</li><li>まずは強気価格で出したい</li><li>仲介手数料を事前に定めた上で案件を出したい。</li></ul><p>など、柔軟なご依頼が可能です。</p>',
			),
			2 => array(
				'theme' => 'teal',
				'tag'   => '仕入・エンド情報',
				'title' => '仕入物件の依頼・<br>エンド情報の依頼',
				'desc'  => '仕入れたい物件や、エンド（購入希望者）情報のご提供をお願いします。',
				'btn1'  => '依頼する',
				'pdfs'  => array(
					array( 'label' => '自社購入の仕入基準を依頼', 'url' => $pdf ),
					array( 'label' => '一般買主の購入ニーズを依頼', 'url' => $pdf ),
				),
				'btn2'        => 'エンド情報を送る',
				'popup_title' => 'エンド情報の送付について',
				'popup'       => '<p>自社物件の仕入や、一般のお客様が希望されるニーズ情報の共有はこちらからご依頼ください。</p><p>案件情報のうち、約70%が未公開案件であり、値下げ案件も多くございます。仕入にも適した案件情報をご提供致します。</p>',
			),
			3 => array(
				'theme' => 'green',
				'tag'   => '工事・リフォーム・派遣',
				'title' => '工事の見積・相談・<br>派遣の依頼',
				'desc'  => 'リフォームや修繕、工事の見積・相談、職人・スタッフの派遣依頼を承ります。',
				'btn1'  => '依頼する',
				'pdfs'  => array(
					array( 'label' => '工事見積依頼シート', 'url' => $pdf ),
					array( 'label' => 'リフォーム見積依頼シート', 'url' => $pdf ),
				),
				'btn2'        => '派遣を相談する',
				'popup_title' => '職人・スタッフ派遣のご相談',
				'popup'       => '<p>つなぐ提携会社様をご紹介可能です。まずは実際の現場の見積からおつなぎ致します。</p><p>大切な弊社のパートナー企業様をおつなぎ致しますので、軽率な依頼はご遠慮ください。</p>',
			),
			4 => array(
				'theme' => 'purple',
				'tag'   => '印刷・制作',
				'title' => '名刺・チラシ印刷の依頼',
				'desc'  => '名刺やチラシなどの印刷物制作をサポートします。',
				'btn1'  => '依頼する',
				'pdfs'  => array(
					array( 'label' => '名刺作成パックの依頼', 'url' => $pdf ),
					array( 'label' => 'チラシ作成パックの依頼', 'url' => $pdf ),
					array( 'label' => '毎月販促パックの依頼', 'url' => $pdf ),
				),
				'btn2'        => '印刷を依頼する',
				'popup_title' => '印刷メニュー・料金表',
				// card4 はPDFポップアップ（popup は使わず popup_pdf を使用）
				'popup'       => '',
				'popup_pdf'   => tsunagu_asset( 'pdf/print-pricing.pdf' ),
			),
			5 => array(
				'theme' => 'orange',
				'tag'   => 'イベント・セミナー',
				'title' => 'イベント・セミナーの依頼',
				'desc'  => '勉強会や研修、イベントの企画・運営をサポートします。',
				'btn1'  => '相談の依頼をする',
				'pdfs'  => array(
					array( 'label' => 'セミナー企画シート', 'url' => $pdf ),
					array( 'label' => '研修プログラム一覧', 'url' => $pdf ),
				),
				'btn2'        => '依頼シートを見る',
				'popup_title' => 'イベント・セミナー依頼について',
				'popup'       => '<p>弊社では、イベントやセミナーの補助を行っております。不動産業界に関わらず、様々な業界・業種の企業様にご利用いただいております。大手企業様向け・中小企業様向けどちらも対応可能です。</p>',
			),
			6 => array(
				'theme' => 'cyan',
				'tag'   => 'その他のご相談',
				'title' => 'その他依頼<small>（営業代行・業務効率化相談など）</small>',
				'desc'  => '営業代行や業務効率化など、課題に合わせたご相談を承ります。',
				'btn1'  => '相談の依頼をする',
				'pdfs'  => array(
					array( 'label' => '業務効率化チェックシート', 'url' => $pdf ),
				),
				'btn2'        => '個別相談する',
				'popup_title' => '個別相談について',
				'popup'       => '<p>弊社のメイン事業は「営業代行事業」です。継続的なご依頼も、スポット対応でもご相談可能です。</p><p>また、様々な企業様への支援経験を活かし、業務の効率化ツールの紹介や、独自ツールの作成もご協力可能です。簡単なHP作成〜何でもご相談ください。</p>',
			),
		),
	);

	return $d;
}

/** カードの固定アイコン（インラインSVG）。テーマ側で固定。 */
function tsunagu_card_icon( $n ) {
	$icons = array(
		1 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 11l8-6 8 6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 10v9h12v-9" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><rect x="10.5" y="13" width="3" height="6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>',
		2 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="9.5" r="3.2" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="M5.5 19c0-3 2.6-5 5.5-5s5.5 2 5.5 5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="16.5" cy="15" r="2.6" fill="none" stroke="currentColor" stroke-width="1.5"/><line x1="18.4" y1="16.9" x2="20.5" y2="19" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
		3 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14 7a3 3 0 0 1 4 4l-9 9-2-2 9-9a1 1 0 0 0-1-1z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M6.5 4l3.5 3.5-2.5 2.5L4 6.5a3 3 0 0 1 2.5-2.5z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M13 13l5 5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		4 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7 9V4h10v5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><rect x="4" y="9" width="16" height="7" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><rect x="7" y="14" width="10" height="6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="16.5" cy="11.5" r="0.9" fill="currentColor"/></svg>',
		5 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="4" y="4" width="16" height="10" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M8.5 18.5L12 15l3.5 3.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9.5" cy="8.5" r="1.4" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M13 11c0-1.4 1-2.2 2.2-2.2S17.4 9.6 17.4 11" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>',
		6 => '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3 9l4-2 5 3 5-3 4 2v6l-4 2-3-2" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M12 10l-2.2 2.2a1.4 1.4 0 0 0 2 2l.7-.7.9.9a1.3 1.3 0 0 0 1.9-1.8" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	);
	return isset( $icons[ $n ] ) ? $icons[ $n ] : '';
}
