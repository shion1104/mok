# 桜サーバーSSH鍵設定手順

## サーバー情報
- ユーザー名: `v3`
- パスワード: `Adminmilk123!`
- ホスト: `nichicoma.sakura.ne.jp`

## SSH鍵認証の設定

### ステップ1: SSH鍵を生成（まだない場合）

```bash
ssh-keygen -t ed25519 -C "github-actions-sakura" -f ~/.ssh/id_ed25519_sakura
```

パスフレーズは空（Enterキーを2回押す）でOKです。

### ステップ2: 公開鍵をサーバーに追加

**方法1: ssh-copy-idを使用（推奨）**

```bash
ssh-copy-id -i ~/.ssh/id_ed25519_sakura.pub v3@nichicoma.sakura.ne.jp
```

パスワード `Adminmilk123!` を入力します。

**方法2: 手動で追加**

```bash
# 公開鍵の内容を表示
cat ~/.ssh/id_ed25519_sakura.pub

# サーバーにSSH接続
ssh v3@nichicoma.sakura.ne.jp
# パスワード: Adminmilk123!

# サーバー上で実行
mkdir -p ~/.ssh
chmod 700 ~/.ssh
echo "公開鍵の内容を貼り付け" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
exit
```

### ステップ3: SSH接続テスト

```bash
ssh -i ~/.ssh/id_ed25519_sakura v3@nichicoma.sakura.ne.jp
```

パスワードなしで接続できればOKです。

### ステップ4: GitHub Secretsに設定

1. GitHubリポジトリ（`shion1104/mok`）を開く
2. **Settings** → **Secrets and variables** → **Actions** を開く
3. 以下のSecretsを設定：

   - **SAKURA_SSH_KEY**: 
     ```bash
     cat ~/.ssh/id_ed25519_sakura
     ```
     この内容全体をコピーして設定

   - **SAKURA_HOST**: `nichicoma.sakura.ne.jp`
   
   - **SAKURA_USER**: `v3`
   
   - **WP_PATH**: WordPressのルートパス（例: `~/www` または `/home/v3/www`）

## 注意事項

- **パスワードはGitHub Secretsに設定しないでください**（SSH鍵のみ使用）
- SSH鍵は機密情報なので、GitHub Secrets以外には保存しないでください
- 公開鍵（`.pub`）は他人に見られても問題ありませんが、秘密鍵（`.pub`なし）は絶対に公開しないでください
