# Elementorエラーの手動修正手順

## 問題
Elementorプラグインの設定データ（`elementor_checklist`）が配列として保存されており、プラグインが文字列を期待しているためエラーが発生しています。

## 解決方法

### 方法1: SSH経由で修正（推奨）

以下のコマンドを実行してください：

```bash
# 1. Elementorプラグインを無効化（ディレクトリをリネーム）
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp/wp-content/plugins && mv elementor elementor.disabled"

# 2. 問題のあるオプションを削除
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp && wp option delete elementor_checklist --allow-root"

# 3. WordPressの状態を確認
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp && wp core version --allow-root"

# 4. サイトが正常に表示されることを確認後、Elementorを再有効化
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp/wp-content/plugins && mv elementor.disabled elementor"
```

### 方法2: データベースから直接削除

phpMyAdminまたはデータベースクライアントを使用して、以下のSQLを実行：

```sql
DELETE FROM wp_options WHERE option_name = 'elementor_checklist';
```

その後、プラグインディレクトリをリネーム：

```bash
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp/wp-content/plugins && mv elementor elementor.disabled"
```

### 方法3: プラグインを完全に削除して再インストール

```bash
# 1. Elementorプラグインを削除
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp/wp-content/plugins && rm -rf elementor"

# 2. 問題のあるオプションを削除
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp && wp option delete elementor_checklist --allow-root"

# 3. WordPress管理画面からElementorを再インストール
```

## 確認

修正後、以下のURLでサイトが正常に表示されることを確認してください：
- https://nichicoma.sakura.ne.jp/moriwp/
