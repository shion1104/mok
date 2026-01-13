# WP-CLI コマンド集

## 基本コマンド形式

```bash
cd /Users/morishion/gtnet-wp && docker run --rm --volumes-from gtnet-wp --network container:gtnet-wp -e WORDPRESS_DB_HOST=db -e WORDPRESS_DB_USER=wordpress -e WORDPRESS_DB_PASSWORD=wordpress -e WORDPRESS_DB_NAME=wordpress wordpress:cli [コマンド]
```

## よく使うコマンド

### ページ管理
- ページ一覧: `post list --post_type=page --format=table`
- ページ取得: `post get [ID] --field=post_content`
- ページ更新: `post update [ID] --post_content="[内容]"`
- ページ作成: `post create --post_type=page --post_title="タイトル" --post_status=publish`

### オプション管理
- オプション取得: `option get [option_name]`
- オプション更新: `option update [option_name] [value]`

### キャッシュ
- キャッシュクリア: `cache flush`

### テーマ
- テーマ一覧: `theme list`
- テーマ有効化: `theme activate [theme_name]`

### メディア
- メディア一覧: `media list --format=table`
