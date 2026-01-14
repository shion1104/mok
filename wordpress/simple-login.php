<?php
/**
 * シンプルなログインページ（WordPressコアファイルに依存しない）
 * 
 * 使用方法:
 * 1. このファイルをWordPressのルートディレクトリにコピー
 * 2. http://localhost:8080/simple-login.php にアクセス
 */

// WordPressのパスを設定（必要に応じて変更）
$wp_load_path = dirname(dirname(dirname(__FILE__))) . '/wp-load.php';

if (file_exists($wp_load_path)) {
	require_once($wp_load_path);
	
	// 既にログインしている場合はリダイレクト
	if (function_exists('is_user_logged_in') && is_user_logged_in()) {
		header('Location: ' . admin_url());
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
				$redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : admin_url();
				wp_safe_redirect($redirect_to);
				exit;
			}
		}
	}
} else {
	// WordPressが見つからない場合のエラーメッセージ
	$wp_not_found = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ログイン</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 20px;
		}
		.login-container {
			background: white;
			border-radius: 12px;
			box-shadow: 0 10px 40px rgba(0,0,0,0.1);
			padding: 40px;
			max-width: 400px;
			width: 100%;
		}
		.login-header {
			text-align: center;
			margin-bottom: 30px;
		}
		.login-header h1 {
			margin: 0 0 10px 0;
			color: #333;
			font-size: 28px;
			font-weight: 600;
		}
		.login-header p {
			margin: 0;
			color: #666;
			font-size: 14px;
		}
		.error-message {
			background: #fee;
			border: 1px solid #fcc;
			color: #c33;
			padding: 12px;
			border-radius: 6px;
			margin-bottom: 20px;
			font-size: 14px;
		}
		.login-form label {
			display: block;
			margin-bottom: 8px;
			color: #333;
			font-weight: 500;
			font-size: 14px;
		}
		.login-form input[type="text"],
		.login-form input[type="password"] {
			width: 100%;
			padding: 12px;
			border: 1px solid #ddd;
			border-radius: 6px;
			font-size: 14px;
			margin-bottom: 20px;
		}
		.login-form input[type="checkbox"] {
			width: 18px;
			height: 18px;
			margin-right: 8px;
			cursor: pointer;
		}
		.login-form .remember-me {
			display: flex;
			align-items: center;
			margin-bottom: 20px;
			color: #666;
			font-size: 14px;
			cursor: pointer;
		}
		.login-form button {
			width: 100%;
			padding: 14px;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			border: none;
			border-radius: 6px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: opacity 0.3s;
		}
		.login-form button:hover {
			opacity: 0.9;
		}
		.login-footer {
			margin-top: 30px;
			padding-top: 20px;
			border-top: 1px solid #eee;
			text-align: center;
		}
		.login-footer a {
			color: #667eea;
			text-decoration: none;
			font-size: 13px;
		}
		.login-footer a:hover {
			text-decoration: underline;
		}
		.wp-not-found {
			text-align: center;
			color: #c33;
		}
	</style>
</head>
<body>
	<div class="login-container">
		<div class="login-header">
			<h1>ログイン</h1>
			<p>WordPress管理画面にログイン</p>
		</div>

		<?php if (isset($wp_not_found) && $wp_not_found): ?>
			<div class="error-message wp-not-found">
				<p><strong>エラー:</strong> WordPressが見つかりません。</p>
				<p style="margin-top: 10px; font-size: 12px;">
					WordPressのコアファイルがインストールされていないか、パスが正しくありません。<br>
					以下のURLを試してください：
				</p>
				<ul style="margin-top: 10px; text-align: left; font-size: 12px;">
					<li>http://localhost:8080/wp-login.php</li>
					<li>http://localhost:8080/wp-admin/</li>
				</ul>
			</div>
		<?php else: ?>
			<?php if ($login_error): ?>
				<div class="error-message">
					<?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?>
				</div>
			<?php endif; ?>

			<form name="loginform" id="loginform" method="post" class="login-form">
				<p>
					<label for="user_login">ユーザー名またはメールアドレス</label>
					<input 
						type="text" 
						name="log" 
						id="user_login" 
						value="<?php echo htmlspecialchars($login_username, ENT_QUOTES, 'UTF-8'); ?>" 
						size="20" 
						autocomplete="username" 
						required
					>
				</p>

				<p>
					<label for="user_pass">パスワード</label>
					<input 
						type="password" 
						name="pwd" 
						id="user_pass" 
						value="" 
						size="20" 
						autocomplete="current-password" 
						required
					>
				</p>

				<p class="remember-me">
					<input 
						type="checkbox" 
						name="rememberme" 
						id="rememberme" 
						value="forever"
					>
					<label for="rememberme" style="margin: 0; cursor: pointer;">ログイン状態を保持する</label>
				</p>

				<?php if (function_exists('admin_url')): ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr(admin_url()); ?>">
				<?php endif; ?>

				<p>
					<button type="submit" name="wp-submit" id="wp-submit">
						ログイン
					</button>
				</p>
			</form>

			<div class="login-footer">
				<?php if (function_exists('wp_lostpassword_url')): ?>
					<p>
						<a href="<?php echo esc_url(wp_lostpassword_url()); ?>">
							パスワードをお忘れですか？
						</a>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</body>
</html>
