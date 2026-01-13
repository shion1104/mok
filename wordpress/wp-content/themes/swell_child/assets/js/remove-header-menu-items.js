/**
 * ページ上部のメニューから特定の項目を削除
 * パスワードのリセット、Thank You、Join Us、登録、メンバーログインなど
 */
(function() {
	'use strict';

	try {
		// 削除するキーワード（会員向け項目）
		const excludeKeywords = [
			'reset',
			'リセット',
			'パスワードのリセット',
			'password',
			'profile',
			'プロフィール',
			'member-login',
			'member-area',
			'member-profile',
			'メンバー',
			'メンバーログイン',
			'register',
			'登録',
			'join',
			'JOIN',
			'thank',
			'thank you',
			'thankyou',
			'THANK',
			'THANKYOU',
			'THANK YOU',
			'Thank',
			'ThankYou',
			'Thank You',
			'mitus',
			'MITUS',
			'Mitus'
		];
		
		// 削除する会員向けページパス
		const excludePaths = [
			'/member-login/',
			'/member-area/',
			'/member-profile/',
			'/profile/',
			'/password-reset/',
			'/reset-password/',
			'/wp-login.php',
			'/wp-register.php',
			'/register/',
			'/thank-you/',
			'/thankyou/',
			'/join/',
			'/join-us/',
			'/member/',
			'/members/'
		];

		function removeMenuItems() {
			try {
				// ヘッダーバー内のリンクをチェック
				const headerBar = document.querySelector('.l-header__bar');
				const headerBarInner = document.querySelector('.l-header__barInner');
				const header = document.querySelector('.l-header');
				const fixHeader = document.querySelector('.l-fixHeader');
				const gnav = document.querySelector('.c-gnav');

				// チェック対象のコンテナ
				const containers = [];
				if (headerBar) containers.push(headerBar);
				if (headerBarInner) containers.push(headerBarInner);
				if (header) containers.push(header);
				if (fixHeader) containers.push(fixHeader);
				if (gnav) containers.push(gnav);

				// 各コンテナ内のリンクをチェック
				containers.forEach(function(container) {
					if (!container) return;
					
					const links = container.querySelectorAll('a');
					links.forEach(function(link) {
						if (!link) return;
						
						try {
							// 会員ログインボタンと会員登録ボタンは除外
							if (link.classList.contains('c-gnav__loginBtn') || 
							    link.classList.contains('c-gnav__registerBtn') ||
							    link.closest('.c-gnav__login') ||
							    link.closest('.c-gnav__register')) {
								return; // このリンクはスキップ
							}
							
							// 検索ボタンと採用情報は除外（削除しない）
							if (link.closest('.c-gnav__s') || 
							    link.textContent.includes('採用') ||
							    link.getAttribute('href') && link.getAttribute('href').includes('recruit')) {
								return; // このリンクはスキップ
							}
							
							const href = (link.getAttribute('href') || '').toLowerCase();
							const text = (link.textContent || link.innerText || '').trim().toLowerCase();
							
							// 空のリンクや#のみのリンクを削除
							if (!href || href === '#' || !text) {
								const parentLi = link.closest('li');
								if (parentLi && parentLi.parentNode) {
									parentLi.parentNode.removeChild(parentLi);
								}
								return;
							}
							
							// MITUSへのリンクを優先的に削除
							if (href.includes('/mitus') || href.includes('mitus')) {
								const parentLi = link.closest('li');
								if (parentLi && parentLi.parentNode) {
									parentLi.parentNode.removeChild(parentLi);
								} else {
									if (link.parentElement) {
										link.parentElement.remove();
									} else {
										link.remove();
									}
								}
								return;
							}
							
							// 会員向けページパスをチェック
							for (let i = 0; i < excludePaths.length; i++) {
								if (href.includes(excludePaths[i])) {
									const parentLi = link.closest('li');
									if (parentLi && parentLi.parentNode) {
										parentLi.parentNode.removeChild(parentLi);
									} else {
										if (link.parentElement) {
											link.parentElement.remove();
										} else {
											link.remove();
										}
									}
									return;
								}
							}
							
							// キーワードをチェック
							for (let i = 0; i < excludeKeywords.length; i++) {
								const keyword = excludeKeywords[i].toLowerCase();
								if (href.includes(keyword) || text.includes(keyword)) {
									// 親要素（li）を削除
									const parentLi = link.closest('li');
									if (parentLi && parentLi.parentNode) {
										parentLi.parentNode.removeChild(parentLi);
									} else {
										// liがない場合はリンクの親要素を削除
										if (link.parentElement) {
											link.parentElement.remove();
										} else {
											link.remove();
										}
									}
									break;
								}
							}
						} catch (e) {
							console.error('Error removing menu item:', e);
						}
					});
				});

				// ウィジェットエリア内もチェック
				const widgets = document.querySelectorAll('.widget, .w-header');
				widgets.forEach(function(widget) {
					if (!widget) return;
					
					const links = widget.querySelectorAll('a');
					links.forEach(function(link) {
						if (!link) return;
						
						try {
							const href = (link.getAttribute('href') || '').toLowerCase();
							const text = (link.textContent || link.innerText || '').trim().toLowerCase();
							
							// MITUSへのリンクを優先的に削除
							if (href.includes('/mitus') || href.includes('mitus')) {
								const parentLi = link.closest('li');
								if (parentLi && parentLi.parentNode) {
									parentLi.parentNode.removeChild(parentLi);
								} else {
									link.remove();
								}
								return;
							}
							
							for (let i = 0; i < excludeKeywords.length; i++) {
								const keyword = excludeKeywords[i].toLowerCase();
								if (href.includes(keyword) || text.includes(keyword)) {
									const parentLi = link.closest('li');
									if (parentLi && parentLi.parentNode) {
										parentLi.parentNode.removeChild(parentLi);
									} else {
										link.style.display = 'none';
									}
									break;
								}
							}
						} catch (e) {
							console.error('Error removing widget menu item:', e);
						}
					});
				});
			} catch (e) {
				console.error('Error in removeMenuItems:', e);
			}
		}

		// DOMContentLoaded時に実行
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', removeMenuItems);
		} else {
			removeMenuItems();
		}

		// PJAX対応（SWELLテーマの場合）
		if (typeof window.SWELL !== 'undefined' && window.SWELL.pjax) {
			document.addEventListener('swell_pjax_after', removeMenuItems);
		}

		// 少し遅延して再実行（動的に追加されるメニューに対応）
		setTimeout(removeMenuItems, 100);
		setTimeout(removeMenuItems, 500);
	} catch (e) {
		console.error('Error initializing remove-menu-items script:', e);
	}
})();
