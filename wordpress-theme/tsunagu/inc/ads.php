<?php
/**
 * 広告（管理メニュー「広告」）
 * 6枠に「画像＋リンク先URL」を登録し、フッターに表示する。
 * ACF無料版にはオプションページが無いため、WordPress標準の設定APIで保存する。
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TSUNAGU_ADS_OPTION', 'tsunagu_ads' );
define( 'TSUNAGU_ADS_SLOTS', 6 );
define( 'TSUNAGU_ADS_PAGE', 'tsunagu-ads' );

/** 6枠分を常に同じ形（image / url）で返す */
function tsunagu_ads_slots() {
	$saved = get_option( TSUNAGU_ADS_OPTION, array() );
	$slots = array();
	for ( $i = 0; $i < TSUNAGU_ADS_SLOTS; $i++ ) {
		$row       = ( is_array( $saved ) && isset( $saved[ $i ] ) && is_array( $saved[ $i ] ) ) ? $saved[ $i ] : array();
		$slots[ $i ] = array(
			'image' => isset( $row['image'] ) ? absint( $row['image'] ) : 0,
			'url'   => isset( $row['url'] ) ? (string) $row['url'] : '',
		);
	}
	return $slots;
}

/** 表示対象の広告（画像が設定されている枠のみ、枠1→枠6の順） */
function tsunagu_get_ads() {
	$items = array();
	foreach ( tsunagu_ads_slots() as $slot ) {
		if ( $slot['image'] && wp_attachment_is_image( $slot['image'] ) ) {
			$items[] = $slot;
		}
	}
	return $items;
}

/** 保存値の無害化 */
function tsunagu_ads_sanitize( $input ) {
	$out = array();
	for ( $i = 0; $i < TSUNAGU_ADS_SLOTS; $i++ ) {
		$row   = ( is_array( $input ) && isset( $input[ $i ] ) && is_array( $input[ $i ] ) ) ? $input[ $i ] : array();
		$image = isset( $row['image'] ) ? absint( $row['image'] ) : 0;
		if ( $image && ! wp_attachment_is_image( $image ) ) {
			$image = 0;
		}
		$url = isset( $row['url'] ) ? esc_url_raw( trim( (string) $row['url'] ), array( 'http', 'https' ) ) : '';
		$out[ $i ] = array( 'image' => $image, 'url' => $url );
	}
	return $out;
}

add_action( 'admin_init', function () {
	register_setting( 'tsunagu_ads_group', TSUNAGU_ADS_OPTION, array(
		'type'              => 'array',
		'sanitize_callback' => 'tsunagu_ads_sanitize',
		'default'           => array(),
	) );
} );

/* 管理メニュー「広告」 */
add_action( 'admin_menu', function () {
	add_menu_page( '広告', '広告', 'manage_options', TSUNAGU_ADS_PAGE, 'tsunagu_ads_page', 'dashicons-megaphone', 26 );
} );

/* 広告ページでのみメディアライブラリと専用CSS/JSを読み込む */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( 'toplevel_page_' . TSUNAGU_ADS_PAGE !== $hook ) return;
	wp_enqueue_media();
	wp_enqueue_style( 'tsunagu-ads-admin', tsunagu_asset( 'css/admin-ads.css' ), array(), TSUNAGU_VER );
	wp_enqueue_script( 'tsunagu-ads-admin', tsunagu_asset( 'js/admin-ads.js' ), array( 'media-editor' ), TSUNAGU_VER, true );
} );

/** 管理画面「広告」 */
function tsunagu_ads_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$slots = tsunagu_ads_slots();
	?>
	<div class="wrap tsg-ads">
		<h1>広告</h1>
		<?php settings_errors(); ?>

		<div class="tsg-ads-help">
			<p><strong>画像を設定した枠だけが、サイト下部（フッター）に表示されます。</strong>すべて未設定のときは広告エリアごと表示されません。</p>
			<ul>
				<li>PCは3枚、スマホは1枚ずつ表示します。それより多く登録すると、自動でスライドして入れ替わります（約4秒ごと）。</li>
				<li>表示順は枠1→枠6です。空いている枠は詰めて表示します。</li>
				<li>推奨画像サイズは <strong>600×500px</strong>（表示サイズ300×250pxの2倍）です。比率が違う画像は余白を付けて全体を表示します。</li>
				<li>リンク先URLは新しいタブで開きます。未入力の場合は画像のみ表示します。</li>
				<li>画像の代替テキストには、メディアライブラリの「代替テキスト」が使われます（広告主名の入力をおすすめします）。</li>
				<li>ファイル名に「ad」「banner」などを含めると、閲覧者の広告ブロッカーで表示されない場合があります。</li>
			</ul>
		</div>

		<form method="post" action="options.php">
			<?php settings_fields( 'tsunagu_ads_group' ); ?>
			<div class="tsg-ads-grid">
				<?php foreach ( $slots as $i => $slot ) :
					$src = $slot['image'] ? wp_get_attachment_image_url( $slot['image'], 'medium_large' ) : '';
					$name = TSUNAGU_ADS_OPTION . '[' . $i . ']';
					?>
					<div class="tsg-ads-slot<?php echo $src ? '' : ' is-empty'; ?>">
						<h2>枠<?php echo (int) ( $i + 1 ); ?><span class="tsg-ads-status"><?php echo $src ? '表示中' : '未設定'; ?></span></h2>
						<div class="tsg-ads-preview">
							<?php if ( $src ) : ?>
								<img src="<?php echo esc_url( $src ); ?>" alt="">
							<?php else : ?>
								<span>画像未設定</span>
							<?php endif; ?>
						</div>
						<input type="hidden" class="tsg-ads-image-id" name="<?php echo esc_attr( $name ); ?>[image]" value="<?php echo $src ? (int) $slot['image'] : ''; ?>">
						<p class="tsg-ads-actions">
							<button type="button" class="button tsg-ads-select"><?php echo $src ? '画像を変更' : '画像を選択'; ?></button>
							<button type="button" class="button-link button-link-delete tsg-ads-remove"<?php echo $src ? '' : ' hidden'; ?>>画像を削除</button>
						</p>
						<label class="tsg-ads-url-label">リンク先URL
							<input type="url" class="tsg-ads-url" name="<?php echo esc_attr( $name ); ?>[url]" value="<?php echo esc_attr( $slot['url'] ); ?>" placeholder="https://example.com/">
						</label>
					</div>
				<?php endforeach; ?>
			</div>
			<?php submit_button( '保存' ); ?>
		</form>
	</div>
	<?php
}
