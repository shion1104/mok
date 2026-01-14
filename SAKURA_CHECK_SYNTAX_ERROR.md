# 構文エラー確認手順

## 確認コマンド

### ステップ1: ファイルの最後の部分を確認
```bash
tail -15 wp-config.php
```

### ステップ2: ファイルの最初の部分を確認
```bash
head -5 wp-config.php
```

### ステップ3: 問題のある行を特定
```bash
php -l wp-config.php 2>&1 | head -20
```

### ステップ4: バックアップから復元（必要に応じて）
```bash
cp wp-config.php.backup.20260114_135045 wp-config.php
```
