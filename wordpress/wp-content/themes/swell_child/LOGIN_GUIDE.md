# ログイン方法ガイド

## Apacheの権限エラーが出る場合の対処法

### 1. WordPress標準ログインページにアクセス

以下のURLを試してください：

```
http://localhost:8080/wp-login.php
```

または

```
http://localhost:8080/wp-admin/
```

### 2. Docker環境の場合

Dockerコンテナ内でWordPressが動いている場合：

1. **コンテナの確認**
   ```bash
   docker ps
   ```

2. **コンテナ内のWordPressパスを確認**
   ```bash
   docker exec -it <コンテナ名> ls /var/www/html/
   ```

3. **wp-login.phpの場所を確認**
   ```bash
   docker exec -it <コンテナ名> find /var/www/html -name "wp-login.php"
   ```

### 3. Apacheの設定確認

`.htaccess`ファイルが正しく設定されているか確認：

```bash
ls -la wordpress/.htaccess
```

`.htaccess`がない場合は、WordPress管理画面で「設定 > パーマリンク設定」を保存すると作成されます。

### 4. ファイル権限の確認

```bash
# テーマディレクトリの権限
chmod 755 wordpress/wp-content/themes/swell_child/
chmod 644 wordpress/wp-content/themes/swell_child/*.php

# WordPressルートディレクトリの権限
chmod 755 wordpress/
```

### 5. 直接アクセスできない場合

テーマファイル（`login.php`）に直接アクセスしようとすると、セキュリティ上ブロックされる場合があります。

**推奨方法：**
- WordPressの標準ログインページ（`wp-login.php`）を使用
- または、WordPress管理画面で固定ページを作成し、ショートコード `[custom_login]` を使用

### 6. 緊急時のログイン方法

データベースに直接アクセスできる場合：

```sql
-- パスワードをリセット（例：パスワードを "newpassword" に変更）
UPDATE wp_users SET user_pass = MD5('newpassword') WHERE user_login = 'admin';
```

または、`wp-cli`を使用：

```bash
wp user update admin --user_pass=newpassword
```
