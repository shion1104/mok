# GTNET WordPress

チーム開発用WordPress環境

## 必要なもの

- Docker Desktop
- Git

## セットアップ

```bash
# 1. リポジトリをクローン
git clone <repository-url>
cd gtnet-wp

# 2. 環境変数ファイルを作成
cp .env.example .env

# 3. Docker起動
docker compose up -d

# 4. WordPressにアクセス
open http://localhost:8080
```

## URL

| サービス | URL |
|----------|-----|
| WordPress | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |

## よく使うコマンド

```bash
# 起動
docker compose up -d

# 停止
docker compose down

# ログ確認
docker compose logs -f wordpress

# コンテナに入る
docker compose exec wordpress bash

# DB完全リセット（データ削除）
docker compose down -v
```

## デプロイ

```bash
# .envにKagoyaの接続情報を設定後
./scripts/deploy.sh
```

## ダミー記事の作成

WP-CLIを使用してテスト用のダミー記事を作成できます。

```bash
# デフォルト（10記事作成）
./scripts/create-dummy-posts.sh

# 記事数を指定（例: 20記事）
./scripts/create-dummy-posts.sh 20

# 記事数と作成者IDを指定（例: 15記事、作成者ID 2）
./scripts/create-dummy-posts.sh 15 2
```

作成された記事を確認するには：
```bash
docker compose exec wordpress wp post list
```

## ディレクトリ構成

```
gtnet-wp/
├── docker-compose.yml  # Docker設定
├── .env.example        # 環境変数テンプレート
├── .env                # 環境変数（Git管理外）
├── .gitignore
├── scripts/
│   ├── deploy.sh              # デプロイスクリプト
│   └── create-dummy-posts.sh  # ダミー記事作成スクリプト
├── wordpress/          # WordPressファイル（自動生成）
│   ├── wp-content/
│   │   ├── themes/     # テーマ ← ここを編集
│   │   └── plugins/    # プラグイン
│   └── ...
└── README.md
```

## 環境情報

### 本番環境（Kagoya）

| 項目 | バージョン |
|------|-----------|
| PHP | 8.0.30 |
| MySQL | 5.7.44 |
| Apache | 2.4.58 |

### ローカル環境（Docker）

| 項目 | バージョン | 備考 |
|------|-----------|------|
| PHP | 8.2+ (latest) | ARM Mac対応のため8.0は使用不可。互換性は問題なし |
| DB | MariaDB 10.5 | MySQL 5.7がARM Mac非対応のため。MySQL 5.7と互換性あり |

## テーマ

- **SWELL**（有料テーマ）を使用
- `wp-content/themes/swell/` - 親テーマ（編集しない）
- `wp-content/themes/swell_child/` - 子テーマ（カスタマイズはここ）

## 注意事項

- `wordpress/wp-content/uploads/` はGit管理外（容量が大きいため）
- `wp-config.php` はGit管理外（環境ごとに異なるため）
- `wordpress/wp-content/languages/` はGit管理外（自動ダウンロードされるため）
- 本番のDBとローカルのDBは別物。コンテンツは同期されない
