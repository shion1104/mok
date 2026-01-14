# 桜サーバー v3 パス設定

## WordPressのURL
- URL: `https://nichicoma.sakura.ne.jp/v3/`
- wp-admin: `https://nichicoma.sakura.ne.jp/v3/wp-admin/`

## WP_PATHの確認方法

サーバーにSSH接続して確認：

```bash
ssh v3@nichicoma.sakura.ne.jp
cd ~/www/v3
pwd
ls -la wp-content
```

通常は以下のいずれかになります：
- `~/www/v3`
- `/home/v3/www/v3`

## GitHub Secrets設定

**WP_PATH** を以下のいずれかに設定：
- `~/www/v3`
- `/home/v3/www/v3`

## 確認コマンド

```bash
# サーバー上で実行
cd ~/www/v3
ls -la wp-content/themes/swell-child
```

テーマディレクトリが存在することを確認してください。
