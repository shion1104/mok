# Git Hooks

このディレクトリには、SWELL childテーマのデプロイ前チェック用のGit hooksが含まれています。

## セットアップ

ローカルリポジトリで以下のコマンドを実行してください：

```bash
git config core.hooksPath .githooks
```

これにより、以下のhooksが有効になります：

- **pre-commit**: コミット前にPHP構文チェック（変更されたファイルのみ）
- **pre-push**: プッシュ前に包括的なチェック（全PHPファイルの構文チェック、禁止パターン検知）

## チェック内容

### pre-push（プッシュ前）

- テーマディレクトリ存在チェック
- functions.php存在チェック
- 全PHPファイルの構文チェック（`php -l`）
- 禁止パターンチェック：
  - `<style>`タグの検出（警告）
  - `<?php`タグの欠如（エラー）

### pre-commit（コミット前）

- 変更されたPHPファイルのみ構文チェック

## 無効化する場合

```bash
git config --unset core.hooksPath
```

## 手動実行

```bash
# pre-pushを手動実行
.githooks/pre-push

# pre-commitを手動実行
.githooks/pre-commit
```
