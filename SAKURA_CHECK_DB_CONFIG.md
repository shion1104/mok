# データベース接続エラー確認手順

## 問題
データベース接続確立エラーが発生している

## 確認手順

### ステップ1: サーバー上のwp-config.phpのデータベース設定を確認

```bash
grep -E "DB_NAME|DB_USER|DB_PASSWORD|DB_HOST" wp-config.php
```

### ステップ2: データベース設定が正しいか確認

サーバー上で以下の設定が正しいか確認：
- DB_NAME: 'nichicoma_moriwp'
- DB_USER: 'nichicoma_moriwp'
- DB_PASSWORD: 'bananamilk1104'
- DB_HOST: 'mysql80.nichicoma.sakura.ne.jp'

### ステップ3: データベース接続をテスト

```bash
php -r "
define('DB_NAME', 'nichicoma_moriwp');
define('DB_USER', 'nichicoma_moriwp');
define('DB_PASSWORD', 'bananamilk1104');
define('DB_HOST', 'mysql80.nichicoma.sakura.ne.jp');
\$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (\$link) {
    echo 'データベース接続成功！\n';
    mysqli_close(\$link);
} else {
    echo 'データベース接続失敗: ' . mysqli_connect_error() . '\n';
}
"
```

### ステップ4: wp-config.phpのデータベース設定を修正（必要に応じて）

もし設定が間違っている場合は、正しい設定に修正する必要があります。
