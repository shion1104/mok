<?php
/**
 * Template Name: ログインページ
 * 
 * カスタムログインページ
 */

// 既にログインしている場合はリダイレクト
if (is_user_logged_in()) {
	wp_redirect(home_url('/wp-admin/'));
	exit;
}

// ログイン処理
$login_error = '';
$login_username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wp-submit'])) {
	$username = isset($_POST['log']) ? sanitize_user($_POST['log']) : '';
	$password = isset($_POST['pwd']) ? $_POST['pwd'] : '';
	$remember = isset($_POST['rememberme']) ? true : false;
	
	if (empty($username) || empty($password)) {
		$login_error = 'ユーザー名とパスワードを入力してください。';
	} else {
		$creds = array(
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => $remember
		);
		
		$user = wp_signon($creds, false);
		
		if (is_wp_error($user)) {
			$login_error = $user->get_error_message();
			$login_username = $username;
		} else {
			$redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : home_url('/wp-admin/');
			wp_safe_redirect($redirect_to);
			exit;
		}
	}
}

get_header();
?>

<div class="login-page-wrapper" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px;">
	<div class="login-container" style="background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); padding: 40px; max-width: 400px; width: 100%;">
		<div class="login-header" style="text-align: center; margin-bottom: 30px;">
			<h1 style="margin: 0 0 10px 0; color: #333; font-size: 28px; font-weight: 600;">ログイン</h1>
			<p style="margin: 0; color: #666; font-size: 14px;">WordPress管理画面にログイン</p>
		</div>

		<?php if ($login_error): ?>
			<div class="login-error" style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
				<?php echo esc_html($login_error); ?>
			</div>
		<?php endif; ?>

		<form name="loginform" id="loginform" method="post" action="">
			<p style="margin-bottom: 20px;">
				<label for="user_login" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">
					ユーザー名またはメールアドレス
				</label>
				<input 
					type="text" 
					name="log" 
					id="user_login" 
					class="input" 
					value="<?php echo esc_attr($login_username); ?>" 
					size="20" 
					autocomplete="username" 
					required
					style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
				>
			</p>

			<p style="margin-bottom: 20px;">
				<label for="user_pass" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">
					パスワード
				</label>
				<input 
					type="password" 
					name="pwd" 
					id="user_pass" 
					class="input" 
					value="" 
					size="20" 
					autocomplete="current-password" 
					required
					style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
				>
			</p>

			<p style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
				<label for="rememberme" style="display: flex; align-items: center; color: #666; font-size: 14px; cursor: pointer;">
					<input 
						type="checkbox" 
						name="rememberme" 
						id="rememberme" 
						value="forever" 
						style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;"
					>
					ログイン状態を保持する
				</label>
			</p>

			<input type="hidden" name="redirect_to" value="<?php echo esc_attr(isset($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url('/wp-admin/')); ?>">

			<p style="margin: 0;">
				<button 
					type="submit" 
					name="wp-submit" 
					id="wp-submit" 
					class="button button-primary button-large" 
					style="width: 100%; padding: 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: opacity 0.3s;"
					onmouseover="this.style.opacity='0.9'" 
					onmouseout="this.style.opacity='1'"
				>
					ログイン
				</button>
			</p>
		</form>

		<div class="login-footer" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
			<p style="margin: 0; font-size: 13px; color: #999;">
				<a href="<?php echo esc_url(wp_lostpassword_url()); ?>" style="color: #667eea; text-decoration: none;">
					パスワードをお忘れですか？
				</a>
			</p>
			<?php if (get_option('users_can_register')): ?>
				<p style="margin: 10px 0 0 0; font-size: 13px; color: #999;">
					<a href="<?php echo esc_url(wp_registration_url()); ?>" style="color: #667eea; text-decoration: none;">
						新規登録
					</a>
				</p>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();
