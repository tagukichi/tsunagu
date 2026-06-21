<?php
/** 会員ログイン本体 */
if ( ! defined( 'ABSPATH' ) ) exit;

$d        = tsunagu_defaults();
$logo     = tsunagu_image_url( 'site_logo', tsunagu_asset( 'img/tsunaglogo.png' ) );
$err      = isset( $_GET['login'] ) ? sanitize_key( $_GET['login'] ) : '';
$redirect = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : home_url( '/' );
?>

<div class="login__skyline" aria-hidden="true"></div>

<main class="login">
	<div class="login__card">
		<div class="login__head">
			<img class="login__logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="500" height="500">
			<h1 class="login__title"><?php echo esc_html( tsunagu_field( 'login_title', $d['login_title'] ) ); ?></h1>
			<p class="login__lead"><?php echo wp_kses_post( tsunagu_field( 'login_lead', $d['login_lead'] ) ); ?></p>
		</div>

		<?php if ( $err === 'failed' || $err === 'empty' ) : ?>
			<p class="login__error" role="alert">
				<?php echo $err === 'empty' ? 'ログインIDとパスワードを入力してください。' : 'ログインIDまたはパスワードが正しくありません。'; ?>
			</p>
		<?php endif; ?>

		<form class="login__form" method="post" action="<?php echo esc_url( wp_login_url() ); ?>" novalidate>
			<div class="field">
				<label for="user_login">ログインID（またはメールアドレス）</label>
				<input id="user_login" name="log" type="text" autocomplete="username" required placeholder="例）tsunagu_member">
			</div>

			<div class="field">
				<label for="user_pass">パスワード</label>
				<div class="field__pass">
					<input id="user_pass" name="pwd" type="password" autocomplete="current-password" required placeholder="パスワードを入力">
					<button type="button" class="pass-toggle" aria-label="パスワードを表示" aria-pressed="false">
						<svg class="icon icon-eye" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
							<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
							<circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.7"/>
						</svg>
						<svg class="icon icon-eye-off" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
							<path d="M4 4l16 16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
							<path d="M9.6 5.2A10 10 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3 3.7M6.2 6.7A18 18 0 0 0 2 12s3.5 7 10 7a10 10 0 0 0 3.3-.55" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" fill="none" stroke="currentColor" stroke-width="1.7"/>
						</svg>
					</button>
				</div>
			</div>

			<div class="login__row">
				<label class="checkbox">
					<input type="checkbox" name="rememberme" value="forever">
					<span>ログイン状態を保持</span>
				</label>
				<a class="login__forgot" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">パスワードをお忘れですか？</a>
			</div>

			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
			<button type="submit" class="btn btn--solid login__submit">ログイン</button>
		</form>

		<p class="login__note"><?php echo wp_kses_post( tsunagu_field( 'login_note', $d['login_note'] ) ); ?></p>
	</div>
</main>
