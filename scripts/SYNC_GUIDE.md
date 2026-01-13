# WordPress 同期ガイド

ローカル環境から開発環境（https://nichicoma.sakura.ne.jp/moriwp/）へのデータ同期手順と、過去に発生したエラーとその解決方法をまとめています。

## メインスクリプト

### `sync-everything-to-dev.sh` ⭐

ローカル環境から開発環境へ、記事以外の全てのデータを同期します。

**同期される内容:**
- ✅ テーマファイル（CSS、JavaScript、HTML、JSON含む）
- ✅ カスタマイザー設定
- ✅ ユーザー情報
- ✅ **固定ページ**（重要：記事データは除外されますが、固定ページは含まれます）
- ✅ コメント（既存記事に紐づくもののみ）
- ✅ カテゴリーとタグ
- ✅ 全ての設定オプション（記事関連を除く）
- ✅ ウィジェット設定
- ✅ メニュー設定
- ✅ 表示設定

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

## 過去に発生したエラーと解決方法

### 1. TOPページIDが取得できない問題

**症状:**
- ログに「TOPページIDが取得できませんでした」と表示される
- HOMEページが「ページが見つかりません」エラーになる

**原因:**
- ローカル環境のページID（例：105）をそのまま開発環境に設定しようとしていた
- 開発環境では異なるページID（例：10）が割り当てられている
- SSH接続が不安定でWP-CLIコマンドがタイムアウトする

**解決方法:**
- 固定ページ同期後に、PHPスクリプトで開発環境の実際のTOPページIDを取得
- 複数の方法でTOPページを検索（スラッグ、タイトル、最初の公開済みページ）
- 見つからない場合は自動で作成
- 設定オプションのコピー時に`page_on_front`と`show_on_front`を除外し、固定ページ同期後に設定

**実装箇所:**
`sync-everything-to-dev.sh`の固定ページ同期後（258行目付近）

### 2. フロントページ設定が上書きされる問題

**症状:**
- 固定ページ同期後に正しく設定したTOPページIDが、設定オプションのコピー時に上書きされる

**原因:**
- 設定オプションのコピー処理で、ローカル環境の`page_on_front`（105）が開発環境にコピーされる
- 開発環境にはID 105のページが存在しないため、404エラーになる

**解決方法:**
- 設定オプションのコピー時に`page_on_front`と`show_on_front`を除外
- 固定ページ同期後に、開発環境の実際のTOPページIDで設定を更新

**実装箇所:**
`sync-everything-to-dev.sh`の設定オプションコピー処理（387行目付近）

### 3. パーマリンクが正しく適用されない問題

**症状:**
- パーマリンク設定を更新しても、リライトルールが反映されない

**解決方法:**
- `wp rewrite flush --hard`でハードフラッシュを実行（.htaccessも更新）
- フロントページ設定更新時に`flush_rewrite_rules(true)`も実行

**実装箇所:**
`sync-everything-to-dev.sh`のパーマリンクフラッシュ処理（620行目付近）

### 4. Elementorプラグインのエラー

**症状:**
- 「重大なエラーが発生しました」と表示される
- `json_decode(): Argument #1 ($json) must be of type string, array given`

**原因:**
- Elementorの設定データ（`elementor_checklist`）が配列として保存されている
- Elementorプラグインは文字列を期待している

**解決方法:**
- プラグインディレクトリをリネームして一時的に無効化
- 問題のあるオプション（`elementor_checklist`）を削除
- プラグインを再有効化

**詳細:** `fix-elementor-manual.md`を参照

## 重要なポイント

### 同期順序が重要

1. **テーマファイル**をコピー
2. **カスタマイザー設定**をコピー
3. **固定ページ**を同期
4. **固定ページ同期後**にフロントページ設定を更新（開発環境の実際のTOPページIDを使用）
5. **設定オプション**をコピー（`page_on_front`と`show_on_front`は除外）
6. **パーマリンク**をフラッシュ

### ページIDの扱い

- ❌ **やってはいけない**: ローカル環境のページIDをそのまま開発環境に設定
- ✅ **正しい方法**: 開発環境で実際に存在するページIDを取得して設定

### サイトURL設定

- 開発環境のサイトURLは自動で`https://nichicoma.sakura.ne.jp/moriwp`に設定されます
- ローカル環境の`http://localhost:8080`はコピーされません

## トラブルシューティング

### HOMEページが404エラーになる場合

1. `scripts/fix-home-page-404.sh`を実行して手順を確認
2. 開発環境で直接WP-CLIコマンドを実行して確認
3. TOPページが存在するか確認: `wp post list --post_type=page --name=top --allow-root`
4. フロントページ設定を確認: `wp option get page_on_front --allow-root`
5. パーマリンクをフラッシュ: `wp rewrite flush --hard --allow-root`

### 同期が失敗する場合

1. SSH接続を確認: `ssh -F ~/.ssh/config sakura "echo 'test'"`
2. WordPressパスを確認: `ssh -F ~/.ssh/config sakura "cd /home/nichicoma/www/moriwp && pwd"`
3. WP-CLIが動作するか確認: `ssh -F ~/.ssh/config sakura "cd /home/nichicoma/www/moriwp && wp core version --allow-root"`

## 環境変数

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
