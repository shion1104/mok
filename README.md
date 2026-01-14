# GT-NET WordPress

WordPressサイトのリポジトリです。

## デプロイ方法

### 自動デプロイ

**pushするだけで自動的に桜サーバー（v3）にSWELL childテーマがデプロイされます。**

```bash
# テーマファイルを変更後
git add wordpress/wp-content/themes/swell-child/
git commit -m "テーマを更新"
git push origin main
```

`main`ブランチにpushすると、GitHub Actionsが自動的に以下を実行します：

1. 事前チェック（テーマディレクトリ存在、functions.php存在、PHP構文チェック）
2. テーマをパッケージ化
3. サーバーにアップロード
4. サーバー側で既存テーマをバックアップ
5. 古いテーマを削除して新しいテーマを展開
6. 権限設定とデプロイ確認

### GitHub Secrets設定

GitHubリポジトリの **Settings** → **Secrets and variables** → **Actions** で以下を設定してください：

| Secret名 | 説明 | 値 |
|---------|------|-----|
| `SAKURA_SSH_KEY` | SSH秘密鍵の内容 | `-----BEGIN OPENSSH PRIVATE KEY-----...`（後述の手順で生成） |
| `SAKURA_HOST` | サーバーのホスト名 | `nichicoma.sakura.ne.jp` |
| `SAKURA_USER` | SSH接続ユーザー名 | `v3` |
| `WP_PATH` | WordPressのルートパス | `~/www` または `/home/v3/www` |

**SSH鍵の生成と設定：**

1. SSH鍵を生成：
   ```bash
   ssh-keygen -t ed25519 -C "github-actions-sakura" -f ~/.ssh/id_ed25519_sakura
   ```

2. 公開鍵をサーバーに追加：
   ```bash
   ssh-copy-id -i ~/.ssh/id_ed25519_sakura.pub v3@nichicoma.sakura.ne.jp
   ```
   パスワード `Adminmilk123!` を入力

3. 秘密鍵の内容をGitHub Secretsに設定：
   ```bash
   cat ~/.ssh/id_ed25519_sakura
   ```
   この内容全体を`SAKURA_SSH_KEY`に設定

### Git Hooks設定（ローカルチェック）

push前に自動的にチェックを実行するには、以下のコマンドを実行してください：

```bash
git config core.hooksPath .githooks
```

これにより、push前に以下のチェックが自動実行されます：

- PHP構文チェック（`php -l`）
- 禁止パターンチェック（`<style>`タグ、`<?php`タグの有無）
- エラーがある場合はpushが中止されます

### デプロイ対象

**デプロイされるもの：**
- `wordpress/wp-content/themes/swell-child/` のみ

**デプロイされないもの：**
- WordPress本体
- データベース（投稿、コメントなど）
- プラグイン
- アップロードファイル（`wp-content/uploads/`）

## よくある問題と対処法

### 1. WP_PATHが間違っている

**症状：** GitHub Actionsで「wp-content が存在しません」エラー

**対処法：**
1. サーバーにSSH接続してWordPressのルートパスを確認：
   ```bash
   ssh v3@nichicoma.sakura.ne.jp
   cd ~/www
   ls -la wp-content  # これが存在することを確認
   ```
2. GitHub Secretsの`WP_PATH`を正しいパスに更新（例: `~/www` または `/home/v3/www`）

### 2. テーマ名が違う

**症状：** 「swell-child が存在しません」エラー

**対処法：**
- ローカルのテーマディレクトリ名が `swell-child` であることを確認
- サーバー側のテーマ名も `swell-child` であることを確認

### 3. PHP構文エラー

**症状：** pre-pushチェックで構文エラーが検出される

**対処法：**
- エラーメッセージに表示されたファイルを修正
- `php -l ファイル名` で構文チェックを実行して確認

### 4. SSH接続エラー

**症状：** 「Permission denied」エラー

**対処法：**
1. GitHub Secretsの`SAKURA_SSH_KEY`が正しく設定されているか確認
2. SSH鍵がサーバーの`~/.ssh/authorized_keys`に追加されているか確認：
   ```bash
   ssh-copy-id -i ~/.ssh/id_ed25519_sakura.pub v3@nichicoma.sakura.ne.jp
   ```
3. SSH接続テスト：
   ```bash
   ssh -i ~/.ssh/id_ed25519_sakura v3@nichicoma.sakura.ne.jp
   ```
   パスワードなしで接続できればOK

## 開発環境

- WordPress: 最新版
- テーマ: SWELL child theme
- PHP: 8.0以上

## ディレクトリ構造

```
wordpress/
├── wp-content/
│   └── themes/
│       └── swell-child/    # デプロイ対象
├── wp-config.php           # デプロイ対象外（サーバー固有）
└── ...
```

## 注意事項

- **投稿やコメントはデータベースに保存されているため、デプロイの影響を受けません**
- `wp-config.php`はサーバー固有の設定のため、デプロイされません
- デプロイ前に自動バックアップが作成されます（`swell-child.backup.YYYYMMDD_HHMMSS`）
