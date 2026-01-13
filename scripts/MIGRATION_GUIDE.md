# WordPress カスタマイズ内容移行ガイド

ローカル環境から開発環境へ、**記事データを除外して**テーマ・プラグイン設定・カスタマイズ内容のみを移行する方法です。

## 概要

この移行スクリプトは以下の内容をエクスポート/インポートします：

### ✅ 移行対象

- **テーマファイル** (`swell_child` テーマ全体)
- **プラグイン設定** (各プラグインの設定値)
- **テーマカスタマイザー設定** (外観 > カスタマイズの設定)
- **ウィジェット設定**
- **メニュー設定**
- **サイト基本設定** (パーマリンク構造、表示設定など)
- **テーマ・プラグイン固有設定** (SWELL、Elementor、Blocksyなど)

### ❌ 移行対象外（安全のため）

- **記事データ** (投稿記事)
- **固定ページの内容** (ページ本文)
- **コメント**
- **メディアファイル** (画像・動画など)
- **ユーザーデータ**
- **データベース全体**

## 使用方法

### ステップ1: ローカル環境でエクスポート

```bash
# プロジェクトルートで実行
./scripts/export-customizations.sh
```

エクスポートが完了すると、以下のディレクトリにファイルが作成されます：

```
exports/
└── wp-customizations-YYYYMMDD_HHMMSS/
    ├── themes/
    │   └── swell_child/
    │       ├── assets/
    │       ├── functions.php
    │       ├── style.css
    │       └── ...
    ├── options.txt          # データベースオプション
    ├── all_options.json     # 全オプション（JSON形式）
    ├── plugins.json         # プラグインリスト
    ├── themes.json          # テーマリスト
    ├── menus.json           # メニューリスト
    ├── widgets.json         # ウィジェットリスト
    └── README.md            # 詳細説明
    └── wp-customizations-YYYYMMDD_HHMMSS.tar.gz  # アーカイブ
```

### ステップ2: エクスポートファイルを開発環境に転送

エクスポートされたアーカイブファイル (`wp-customizations-YYYYMMDD_HHMMSS.tar.gz`) を開発環境のサーバーに転送します。

方法例：
- SCP: `scp exports/wp-customizations-*.tar.gz user@dev-server:/path/to/exports/`
- FTP/SFTPクライアント
- Gitリポジトリ（大きなファイルの場合は注意）

### ステップ3: 開発環境でインポート

開発環境のWordPressプロジェクトで以下を実行：

```bash
# アーカイブを展開（必要に応じて）
cd exports
tar -xzf wp-customizations-YYYYMMDD_HHMMSS.tar.gz
cd ..

# インポート実行
./scripts/import-customizations.sh wp-customizations-YYYYMMDD_HHMMSS
```

インポート処理の内容：
1. 既存テーマのバックアップ作成
2. テーマファイルのコピー
3. プラグイン設定の確認・インストール
4. データベースオプションの更新
5. テーマの有効化

## 手動でのインポート方法

スクリプトが使用できない場合や、部分的にインポートしたい場合は以下の手順で手動実行できます：

### テーマファイルの手動インポート

```bash
# 開発環境のテーマディレクトリにコピー
cp -r exports/wp-customizations-*/themes/swell_child \
   /path/to/dev/wp-content/themes/
```

### データベースオプションの手動インポート

WP-CLIを使用してオプションを個別に更新：

```bash
# 開発環境で実行
wp option update theme_mods_swell_child '{"key":"value"}' --format=json
wp option update sidebars_widgets '{"sidebar-1":["widget-id"]}' --format=json
# ... など
```

または、`all_options.json` から必要なオプションを抽出して更新します。

## 注意事項

### ⚠️ 重要な注意点

1. **記事データは影響を受けません**
   - 開発環境の既存記事は削除されません
   - 記事の上書きリスクはありません

2. **URL設定は変更されません**
   - `siteurl` と `home` オプションはインポート対象外
   - 開発環境のURLが維持されます

3. **プラグインの互換性**
   - プラグインがインストールされていない場合は、設定のみインポートされます
   - プラグイン固有の機能が動作しない場合があります

4. **バックアップ推奨**
   - インポート前に開発環境のバックアップを取得することを推奨します
   - テーマファイルは自動的にバックアップされます

5. **テーマの有効化**
   - インポート後、テーマが自動的に有効化されます
   - 手動で確認してください

### トラブルシューティング

#### テーマが表示されない
```bash
# テーマが正しくインストールされているか確認
wp theme list

# テーマを手動で有効化
wp theme activate swell_child
```

#### プラグイン設定が反映されない
- プラグインがインストール・有効化されているか確認
- プラグイン固有の設定画面から確認・再設定

#### カスタマイザー設定が反映されない
- 管理画面の「外観 > カスタマイズ」から確認
- 必要に応じて手動で再設定

## 開発環境の準備

開発環境でも同じスクリプトを使用する場合：

1. スクリプトファイルを開発環境にコピー
2. 実行権限を付与: `chmod +x scripts/*.sh`
3. WP-CLIが使用可能であることを確認

## 更新履歴

- 2025-01-XX: 初版作成
