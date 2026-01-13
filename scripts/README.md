# WordPress スクリプト一覧

このディレクトリには、ローカル開発環境と開発環境（Sakuraサーバー）間でのデータ同期と管理を行うスクリプトが含まれています。

## 主要スクリプト

### 1. データ同期

#### `sync-everything-to-dev.sh` ⭐ **メインスクリプト**
ローカル環境から開発環境へ、記事以外の全てのデータを同期します。

**同期される内容:**
- ✅ テーマファイル（CSS、JavaScript、HTML、JSON含む）
- ✅ カスタマイザー設定
- ✅ ユーザー情報
- ✅ **固定ページ**（記事データは除外されますが、固定ページは含まれます）
- ✅ コメント（既存記事に紐づくもののみ）
- ✅ カテゴリーとタグ
- ✅ 全ての設定オプション（記事関連を除く）
- ✅ ウィジェット設定
- ✅ メニュー設定
- ✅ 表示設定
- ✅ フロントページ設定（開発環境の実際のTOPページIDを使用）

**使用方法:**
```bash
export DEV_SSH_CONNECTION=sakura
export DEV_WP_PATH=/home/nichicoma/www/moriwp
./scripts/sync-everything-to-dev.sh
```

または引数で指定:
```bash
./scripts/sync-everything-to-dev.sh sakura /home/nichicoma/www/moriwp
```

**注意:** 
- 投稿記事データは除外されます（固定ページは含まれます）
- フロントページ設定は自動で開発環境のTOPページIDに設定されます

---

### 2. 開発・テスト用

#### `create-dummy-posts.sh`
ダミー記事を作成します。

**使用方法:**
```bash
./scripts/create-dummy-posts.sh [記事数] [作成者ID]
# 例: ./scripts/create-dummy-posts.sh 10 1
```

#### `add-categories-to-dummy-posts.sh`
既存のダミー記事にランダムにカテゴリーを割り当てます。

#### `add-tags-to-dummy-posts.sh`
既存のダミー記事にランダムにタグを割り当てます。

---

### 3. トラブルシューティング

#### `check-errors.sh`
開発環境のエラーを確認します。

**使用方法:**
```bash
export DEV_SSH_CONNECTION=sakura
export DEV_WP_PATH=/home/nichicoma/www/moriwp
./scripts/check-errors.sh
```

#### `check-site-status.sh`
開発環境のサイトの状態を確認します。

**使用方法:**
```bash
export DEV_SSH_CONNECTION=sakura
export DEV_WP_PATH=/home/nichicoma/www/moriwp
./scripts/check-site-status.sh
```

---

## 環境変数の設定

開発環境への接続情報を環境変数で設定できます:

```bash
export DEV_SSH_CONNECTION=sakura
export DEV_WP_PATH=/home/nichicoma/www/moriwp
```

SSH接続情報は `~/.ssh/config` に設定されている必要があります:

```
Host sakura
    HostName nichicoma.sakura.ne.jp
    User moriwp
    IdentityFile ~/.ssh/sakura/id_ecdsa.pem
```

---

## ドキュメント

- `SYNC_GUIDE.md` ⭐ **同期ガイド** - 同期手順と過去のエラー対応方法
- `MIGRATION_GUIDE.md` - 移行ガイド（旧方式の説明）
- `fix-elementor-manual.md` - Elementorエラー修正手順（参考用）
- `fix-dev-manual.md` - 開発環境の手動修正手順
- `fix-home-page-404.sh` - HOMEページ404エラー修正手順

---

## 重要な注意事項

### 同期されるデータ
- ✅ **固定ページは同期されます**（記事データは除外されます）
- ✅ テーマファイル、カスタマイザー設定、ユーザー情報、コメント、カテゴリー・タグ、設定オプション

### 同期されないデータ
- ❌ **投稿記事データ**（安全のため除外）
- ❌ メディアファイル（画像・動画など）

### エラー対応の記録
過去に発生したエラーとその解決方法は `SYNC_GUIDE.md` に記録されています。同じエラーが発生した場合は参照してください。

### 実行前の確認
- ✅ 実行前に必ずバックアップを取得してください
- ✅ 開発環境でのみ実行してください（本番環境では使用しないでください）
- ✅ SSH接続が正常に動作することを確認してください
