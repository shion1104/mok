# ヘッダーメニューで非表示にしている項目リスト

## 概要
ヘッダーメニュー（`header_menu`テーマロケーション）から自動的に削除される項目の一覧です。

## 1. 会員向けページパス（URLに含まれる場合に削除）

以下のパスがURLに含まれるメニュー項目は自動的に削除されます：

### 会員ログイン・登録関連
- `/member-login/` （メニュー項目として。ボタンは別途表示）
- `/member-area/`
- `/member-profile/`
- `/profile/`
- `/register/`
- `/wp-login.php`
- `/wp-register.php`

### パスワード関連
- `/password-reset/`
- `/reset-password/`

### その他会員向けページ
- `/thank-you/`
- `/thankyou/`
- `/join/`
- `/join-us/`
- `/member/`
- `/members/`

## 2. キーワードによる削除（URLまたはタイトルに含まれる場合）

### パスワード関連キーワード
- `reset`
- `リセット`
- `パスワードのリセット`
- `password`
- `password-reset`
- `reset-password`

### プロフィール関連キーワード
- `profile`
- `プロフィール`
- `member-profile`
- `user-profile`

### 登録関連キーワード
- `register`
- `登録`
- `wp-register`
- `registration`

### Join関連キーワード
- `join`
- `JOIN`
- `Join`
- `join-us`
- `joinus`

### Thank You関連キーワード
- `thank`
- `thank you`
- `thankyou`
- `THANK`
- `THANKYOU`
- `THANK YOU`
- `Thank`
- `ThankYou`
- `Thank You`
- `thank-you`

### メンバー関連キーワード（メニュー項目として）
- `member-area`
- `member-profile`
- `members`
- `メンバーエリア`

### MITUS関連キーワード
- `mitus`
- `MITUS`
- `Mitus`

## 3. その他の削除条件

### 空のメニュー項目
- タイトルが空の項目
- URLが空の項目
- URLが`#`のみの項目
- タイトルとURLの両方が空の項目

## 4. 除外される項目（削除されない項目）

以下の項目は削除されません：

### 会員ログイン・登録ボタン
- `swl_parts__gnav`関数で別途表示される会員ログインボタン
- `swl_parts__gnav`関数で別途表示される会員登録ボタン
- `swl_parts__gnav`関数で別途表示されるログアウトボタン

**注意**: これらはメニュー項目ではなく、ヘッダーの右端に表示されるボタンです。

### 検索アイコン
- 検索アイコン（`c-gnav__s`）は常に表示されます

## 5. 適用範囲

### 適用されるメニュー
- **ヘッダーメニュー**（`header_menu`テーマロケーション）のみ

### 適用されないメニュー
- フッターメニュー
- サイドバーメニュー
- その他のメニュー

## 6. 実装ファイル

### PHPフィルター
- `functions.php`:
  - `wp_nav_menu_objects`フィルター（優先度: 999）
  - `walker_nav_menu_start_el`フィルター（優先度: 999）

### JavaScript
- `assets/js/remove-header-menu-items.js`:
  - クライアント側での補完的な削除処理

## 7. 削除の優先順位

1. **空のメニュー項目** → 最優先で削除
2. **MITUS関連** → 優先的に削除
3. **会員向けページパス** → パスが一致する場合に削除
4. **キーワードマッチ** → URLまたはタイトルにキーワードが含まれる場合に削除

## 8. カスタマイズ方法

### 削除対象を追加する場合

#### PHP側（functions.php）
`swell_child_get_exclude_keywords()`関数にキーワードを追加：
```php
function swell_child_get_exclude_keywords() {
	return array(
		// 既存のキーワード...
		'新しいキーワード', // 追加
	);
}
```

`$member_pages`配列にパスを追加：
```php
$member_pages = array(
	// 既存のパス...
	'/new-member-page/', // 追加
);
```

#### JavaScript側（remove-header-menu-items.js）
`excludeKeywords`配列にキーワードを追加：
```javascript
const excludeKeywords = [
	// 既存のキーワード...
	'新しいキーワード', // 追加
];
```

`excludePaths`配列にパスを追加：
```javascript
const excludePaths = [
	// 既存のパス...
	'/new-member-page/', // 追加
];
```

### 削除対象から除外する場合

現在の実装では、会員ログイン・登録ボタンは`swl_parts__gnav`関数で別途表示されるため、メニュー項目として削除されても問題ありません。

特定の項目を削除対象から除外したい場合は、フィルター関数内で条件を追加してください。

## 9. トラブルシューティング

### 項目が削除されない場合
1. メニューが「ヘッダーメニュー」（`header_menu`）に割り当てられているか確認
2. URLやタイトルに上記のキーワードが正確に含まれているか確認
3. ブラウザのキャッシュをクリア
4. PHPフィルターとJavaScriptの両方が動作しているか確認

### 意図しない項目が削除される場合
1. メニュー項目のURLやタイトルを確認
2. 上記のキーワードが含まれていないか確認
3. 必要に応じてキーワードリストから該当項目を削除
