<?php
/**
 * ニュース詳細ページ
 * Next.jsのnews/[id]/page.tsxから変換
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
while ( have_posts() ) :
	the_post();
	
	$the_id = get_the_ID();
	$post_categories = get_the_category();
	$category_name = !empty($post_categories) ? $post_categories[0]->name : 'ニュース';
	$category_slug = !empty($post_categories) ? $post_categories[0]->slug : '';
	
	// カテゴリラベルの取得
	$category_labels = array(
		'gt-news' => 'GTニュース',
		'industry' => '業界ニュース',
		'victim' => '被害速報',
		'gt-mail' => 'GTメール',
		'topics' => 'トピックス',
		'column' => 'コラム'
	);
	$category_label = isset($category_labels[$category_slug]) ? $category_labels[$category_slug] : $category_name;
	
	// 会員限定フラグ
	$is_member_only = get_post_meta($the_id, 'member_only', true) === '1';
	$post_tags = get_the_tags();
	if (!$is_member_only && $post_tags) {
		foreach ($post_tags as $tag) {
			if (stripos($tag->name, '会員限定') !== false) {
				$is_member_only = true;
				break;
			}
		}
	}
	
	// 重要フラグ
	$is_important = false;
	if ($post_tags) {
		foreach ($post_tags as $tag) {
			if (stripos($tag->name, '重要') !== false || stripos($tag->name, '緊急') !== false) {
				$is_important = true;
				break;
			}
		}
	}
	
	// 記事の種類（タグから取得）
	$post_type_label = 'お知らせ';
	if ($post_tags) {
		foreach ($post_tags as $tag) {
			if (in_array($tag->name, array('レポート', '被害速報', 'トピックス', 'お知らせ', 'メール', '行政', '事件', '資料', 'コラム', '統計', 'ゴト情報', '噂未確認', '防護・対策', '話題'))) {
				$post_type_label = $tag->name;
				break;
			}
		}
	}
	
	// カテゴリの色クラス
	$category_color_class = 'bg-slate-100 text-slate-700 border-slate-200';
	switch ($category_slug) {
		case 'gt-news':
		case 'industry':
		case 'victim':
		case 'gt-mail':
		case 'topics':
		case 'column':
			$category_color_class = 'bg-slate-100 text-slate-700 border-slate-200';
			break;
	}
?>
<main id="main_content" class="l-mainContent news-detail-page">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-3xl mx-auto">
			<!-- パンくずリスト -->
			<div class="bg-white border-b border-slate-100 mb-6">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors py-3">
					<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
						<path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
					トップページに戻る
				</a>
			</div>
			
			<!-- 記事ヘッダー -->
			<article class="bg-white rounded-3xl shadow-xl overflow-hidden">
				<!-- カテゴリ & 日付 -->
				<div class="bg-slate-900 px-8 py-6">
					<div class="flex flex-wrap items-center gap-3 mb-4">
						<span class="px-4 py-1 text-xs font-black border rounded-full <?php echo esc_attr($category_color_class); ?>">
							<?php echo esc_html($category_label); ?>
						</span>
						<span class="px-4 py-1 text-xs font-black bg-white/10 text-white rounded-full">
							<?php echo esc_html($post_type_label); ?>
						</span>
						<?php if ($is_member_only) : ?>
							<span class="flex items-center gap-1 px-3 py-1 text-xs font-black text-amber-400 bg-amber-400/10 border border-amber-400/30 rounded-full">
								<svg width="12" height="12" viewBox="0 0 20 20" fill="none">
									<rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
									<path d="M7 8V5C7 3.34315 8.34315 2 10 2C11.6569 2 13 3.34315 13 5V8" stroke="currentColor" stroke-width="2"/>
								</svg>
								会員限定
							</span>
						<?php endif; ?>
					</div>
					<time class="text-slate-400 text-sm font-bold" datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('Y-m-d'); ?></time>
					<h1 class="text-2xl md:text-3xl font-black text-white mt-2 leading-tight">
						<?php the_title(); ?>
					</h1>
				</div>

				<!-- コンテンツ -->
				<div class="p-8 md:p-12">
					<?php if ($is_member_only) : ?>
						<!-- 会員限定コンテンツ表示 -->
						<div class="bg-slate-50 rounded-2xl p-8 md:p-12 text-center border-2 border-dashed border-slate-200">
							<div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
								<svg width="40" height="40" viewBox="0 0 20 20" fill="none" class="text-amber-600">
									<rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
									<path d="M7 8V5C7 3.34315 8.34315 2 10 2C11.6569 2 13 3.34315 13 5V8" stroke="currentColor" stroke-width="2"/>
								</svg>
							</div>
							<h2 class="text-2xl font-black text-slate-900 mb-4">
								会員専用コンテンツ
							</h2>
							<p class="text-slate-600 mb-8 leading-relaxed max-w-md mx-auto">
								このコンテンツは会員様専用となっております。<br />
								コンテンツをご覧になる場合は会員登録またはログインが必要です。
							</p>

							<div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
								<a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="flex items-center justify-center gap-2 bg-blue-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/20">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
										<path d="M10 2C7.79086 2 6 3.79086 6 6C6 8.20914 7.79086 10 10 10C12.2091 10 14 8.20914 14 6C14 3.79086 12.2091 2 10 2Z" stroke="currentColor" stroke-width="2"/>
										<path d="M4 16C4 13.7909 6.79086 12 10 12C13.2091 12 16 13.7909 16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
									</svg>
									会員ログイン
								</a>
								<a href="<?php echo esc_url(wp_registration_url()); ?>" class="flex items-center justify-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-slate-800 transition-all">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
										<path d="M10 2C7.79086 2 6 3.79086 6 6C6 8.20914 7.79086 10 10 10C12.2091 10 14 8.20914 14 6C14 3.79086 12.2091 2 10 2Z" stroke="currentColor" stroke-width="2"/>
										<path d="M15 7L17 9L15 11M13 7L11 9L13 11M4 16C4 13.7909 6.79086 12 10 12C13.2091 12 16 13.7909 16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
									</svg>
									新規会員登録
								</a>
							</div>

							<div class="pt-8 border-t border-slate-200">
								<p class="text-sm text-slate-500 mb-4">入会に関するお問い合わせ</p>
								<div class="flex items-center justify-center gap-3 text-slate-900">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-blue-500">
										<path d="M2 4H6L8 11L5.5 13.5C6.57096 15.6715 8.32854 17.429 10.5 18.5L13 16L20 18V22C20 22.5304 19.7893 23.0391 19.4142 23.4142C19.0391 23.7893 18.5304 24 18 24C7.61116 24 0 16.3888 0 6C0 5.46957 0.210714 4.96086 0.585786 4.58579C0.960859 4.21071 1.46957 4 2 4Z" stroke="currentColor" stroke-width="2"/>
									</svg>
									<span class="text-xl font-black">0120-189-510</span>
								</div>
								<p class="text-xs text-slate-400 mt-2">年中無休・10:00〜17:00</p>
							</div>
						</div>
					<?php else : ?>
						<!-- 公開コンテンツ表示 -->
						<div class="prose prose-slate max-w-none">
							<?php the_content(); ?>
							<div class="mt-8 p-6 bg-slate-50 rounded-xl border border-slate-200">
								<p class="text-sm text-slate-500">
									このコンテンツは公開コンテンツです。どなたでもご覧いただけます。
								</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</article>

			<!-- 関連情報 -->
			<div class="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
				<div class="flex items-start gap-4">
					<div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-blue-600">
							<path d="M10 2L12 6H16L13 9L14 13L10 11L6 13L7 9L4 6H8L10 2Z" stroke="currentColor" stroke-width="2"/>
						</svg>
					</div>
					<div>
						<h3 class="font-bold text-slate-900 mb-1">GT-NET会員サービスについて</h3>
						<p class="text-sm text-slate-600 leading-relaxed">
							GT-NETの会員サービスでは、最新のゴト情報、被害速報、検証動画、各種テンプレートなど、
							ホール経営に役立つ情報をいち早くお届けしています。
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php 
endwhile;
get_footer();
?>
