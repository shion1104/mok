# ヘッダーメニュー項目の完全削除ガイド

## 削除方法

### 方法1: WordPress管理画面から削除（推奨）

1. WordPress管理画面にログイン
2. **外観** → **ヘッダーメニュー整理** を開く
3. 削除対象の項目が一覧表示されます
4. 「上記の項目を削除する」ボタンをクリック
5. 確認ダイアログで「OK」をクリック

### 方法2: メニュー管理画面から手動削除

1. WordPress管理画面にログイン
2. **外観** → **メニュー** を開く
3. ヘッダーメニューを選択
4. 以下の項目を探して削除：

#### 削除すべき項目の例：

**会員向けページ：**
- プロフィール
- パスワードのリセット
- 会員ログイン（メニュー項目として）
- 会員登録（メニュー項目として）
- 会員エリア
- Thank You
- Join / Join Us
- MITUS関連

**空の項目：**
- タイトルが空の項目
- URLが空の項目
- URLが`#`のみの項目

5. 各項目の右側にある「削除」リンクをクリック
6. **メニューを保存** をクリック

## 削除対象の完全リスト

### 会員向けページパス（URLに含まれる場合）
- `/member-login/`
- `/member-area/`
- `/member-profile/`
- `/profile/`
- `/password-reset/`
- `/reset-password/`
- `/wp-login.php`
- `/wp-register.php`
- `/register/`
- `/thank-you/`
- `/thankyou/`
- `/join/`
- `/join-us/`
- `/member/`
- `/members/`

### キーワード（URLまたはタイトルに含まれる場合）

**パスワード関連：**
- reset, リセット, パスワードのリセット, password, password-reset, reset-password

**プロフィール関連：**
- profile, プロフィール, member-profile, user-profile

**登録関連：**
- register, 登録, wp-register, registration

**Join関連：**
- join, JOIN, Join, join-us, joinus

**Thank You関連：**
- thank, thank you, thankyou, THANK, THANKYOU, THANK YOU, Thank, ThankYou, Thank You, thank-you

**メンバー関連：**
- member-area, member-profile, members, メンバーエリア

**MITUS関連：**
- mitus, MITUS, Mitus

## 注意事項

- 会員ログイン・登録ボタンは`swl_parts__gnav`関数で別途表示されるため、メニューから削除されても問題ありません
- 削除は取り消せません（削除前に確認してください）
- ヘッダーメニュー（`header_menu`）のみが対象です
