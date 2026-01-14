# SWELL Child テーマ

## ログイン方法

### 方法1: WordPress標準ログインページ
```
http://localhost:8080/wp-login.php
```

### 方法2: 管理画面への直接アクセス
```
http://localhost:8080/wp-admin/
```
（未ログインの場合は自動的にログインページにリダイレクトされます）

### 方法3: カスタムログインページ（ショートコード使用）
固定ページを作成し、ショートコード `[custom_login]` を追加してください。

## トラブルシューティング

### Apacheの権限エラーが出る場合

1. **WordPressの標準ログインページを使用**
   - `http://localhost:8080/wp-login.php` にアクセス

2. **ファイル権限の確認**
   ```bash
   chmod 755 wordpress/wp-content/themes/swell_child/
   chmod 644 wordpress/wp-content/themes/swell_child/*.php
   ```

3. **.htaccessファイルの確認**
   - WordPressのルートディレクトリに`.htaccess`ファイルがあるか確認
   - ない場合は、WordPress管理画面の「設定 > パーマリンク設定」で保存すると作成されます

4. **Apacheの設定確認**
   - DocumentRootが正しく設定されているか確認
   - AllowOverrideがAllに設定されているか確認
