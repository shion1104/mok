# 開発環境のWordPress設定を修正する手順

開発環境（https://nichicoma.sakura.ne.jp/moriwp/）のWordPressが正常に動作するように設定を修正します。

## 手動で実行するコマンド

開発環境のサーバーにSSH接続して、以下のコマンドを実行してください：

```bash
ssh nichicoma.sakura.ne.jp
cd /home/nichicoma/www/moriwp
```

### 1. サイトアドレス設定を確認・更新

```bash
# 現在の設定を確認
wp option get siteurl --allow-root
wp option get home --allow-root

# 設定を更新
wp option update siteurl 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root
wp option update home 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root
```

### 2. TOPページの確認・作成

```bash
# TOPページが存在するか確認
wp post list --post_type=page --name=top --format=table --fields=ID,post_title,post_name,post_status --allow-root

# TOPページが存在しない場合は作成
TOP_PAGE_ID=$(wp post list --post_type=page --name=top --format=ids --allow-root | head -1)
if [ -z "$TOP_PAGE_ID" ]; then
    wp post create --post_type=page --post_title='TOP' --post_name='top' --post_status=publish --allow-root
    TOP_PAGE_ID=$(wp post list --post_type=page --name=top --format=ids --allow-root | head -1)
fi
echo "TOPページID: $TOP_PAGE_ID"
```

### 3. フロントページ設定を更新

```bash
# フロントページ設定を更新
wp option update show_on_front 'page' --allow-root
wp option update page_on_front $TOP_PAGE_ID --allow-root

# 確認
wp option get show_on_front --allow-root
wp option get page_on_front --allow-root
```

### 4. パーマリンク設定を更新

```bash
# パーマリンク設定を確認
wp option get permalink_structure --allow-root

# パーマリンク設定を更新
wp option update permalink_structure '/%postname%/' --allow-root

# パーマリンクをフラッシュ
wp rewrite flush --allow-root
```

### 5. 最終確認

```bash
# 全ての設定を確認
echo "=== 設定確認 ==="
echo "サイトURL: $(wp option get siteurl --allow-root)"
echo "ホームURL: $(wp option get home --allow-root)"
echo "フロントページ: $(wp option get show_on_front --allow-root)"
echo "フロントページID: $(wp option get page_on_front --allow-root)"
echo "パーマリンク: $(wp option get permalink_structure --allow-root)"
```

## ワンライナーで実行（推奨）

以下のコマンドをローカル環境から実行できます：

```bash
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp && \
wp option update siteurl 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root && \
wp option update home 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root && \
TOP_ID=\$(wp post list --post_type=page --name=top --format=ids --allow-root | head -1) && \
if [ -z \"\$TOP_ID\" ]; then TOP_ID=\$(wp post create --post_type=page --post_title='TOP' --post_name='top' --post_status=publish --allow-root 2>&1 | grep -o 'Created post [0-9]*' | grep -o '[0-9]*' | head -1); fi && \
wp option update show_on_front 'page' --allow-root && \
wp option update page_on_front \$TOP_ID --allow-root && \
wp option update permalink_structure '/%postname%/' --allow-root && \
wp rewrite flush --allow-root && \
echo '✓ 設定完了: https://nichicoma.sakura.ne.jp/moriwp/'"
```

## 確認

設定完了後、以下のURLで確認してください：

- トップページ: https://nichicoma.sakura.ne.jp/moriwp/
- 管理画面: https://nichicoma.sakura.ne.jp/moriwp/wp-admin/
