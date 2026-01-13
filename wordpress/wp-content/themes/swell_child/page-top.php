<?php
/**
 * Template Name: トップページ
 * 
 * パチンコ不正対策会社向けトップページテンプレート
 * モックプレビューのデザインを適用
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

// カルーセルスライドデータ
$carousel_slides = array(
	array(
		'id' => 1,
		'title' => "パチンコホールの\n不正を未然に防ぐ",
		'subtitle' => 'リスクマネジメント',
		'desc' => 'ゴト対策からセキュリティ監査まで、現場主義のコンサルティングでホール経営を守ります。',
		'bg_class' => 'bg-slate-900',
		'accent_class' => 'text-blue-400'
	),
	array(
		'id' => 2,
		'title' => "データが語る\n真実の防犯対策",
		'subtitle' => 'データ分析',
		'desc' => '全国の被害発生状況をリアルタイムに収集。統計に基づいた的確な対策を提案します。',
		'bg_class' => 'bg-blue-900',
		'accent_class' => 'text-blue-300'
	),
	array(
		'id' => 3,
		'title' => "教育こそが\n最大の防御策",
		'subtitle' => 'スタッフ教育',
		'desc' => 'スタッフの意識を変え、有事に強い組織を作る。独自のカリキュラムで防犯スキルを向上。',
		'bg_class' => 'bg-slate-800',
		'accent_class' => 'text-blue-400'
	)
);

// 大カテゴリ（メインカテゴリ）の定義
$main_categories = array(
	'all' => 'すべて',
	'gt-news' => 'GTニュース',
	'industry' => '業界',
	'victim' => '被害速報',
	'gt-mail' => 'GTメール',
	'topics' => 'トピックス',
	'column' => 'コラム'
);

// GTニュースのサブカテゴリ
$gt_news_subcategories = array(
	'all' => 'すべて',
	'ゴト情報' => 'ゴト情報',
	'噂未確認' => '噂未確認',
	'防護・対策' => '防護・対策',
	'話題' => '話題',
	'統計' => '統計',
	'お知らせ' => 'お知らせ'
);

// ダミーキャンペーン記事データ
$dummy_campaign_posts = array(
	array(
		'title' => '新規会員登録キャンペーン実施中！初月無料でお試しください',
		'date' => '2026年01月10日',
		'excerpt' => '新規会員登録いただいた方に、初月無料でGT-NETの全機能をお試しいただけます。不正事例データベースから実務マニュアルまで、すべてのコンテンツにアクセス可能です。',
		'image' => 'https://images.unsplash.com/photo-1556761175-4b46a572b786?w=1200&h=500&fit=crop',
		'badge' => '注目',
		'badge_color' => 'bg-blue-600',
		'link' => '/news/featured-1',
		'featured' => true
	),
	array(
		'title' => '2026年度 防犯セミナー開催のお知らせ',
		'date' => '2026年01月08日',
		'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&h=600&fit=crop',
		'badge' => 'お知らせ',
		'badge_color' => 'bg-blue-50 text-blue-700',
		'link' => '/news/seminar-2026',
		'featured' => false
	),
	array(
		'title' => '年間契約で最大20%OFF！お得なプラン',
		'date' => '2026年01月03日',
		'image' => 'https://images.unsplash.com/photo-1556740758-90de374c12ad?w=800&h=600&fit=crop',
		'badge' => 'キャンペーン',
		'badge_color' => 'bg-green-50 text-green-700',
		'link' => '/news/campaign-annual',
		'featured' => false
	),
	array(
		'title' => 'データベース機能を大幅に強化しました',
		'date' => '2026年01月05日',
		'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=600&fit=crop',
		'badge' => 'アップデート',
		'badge_color' => 'bg-purple-50 text-purple-700',
		'link' => '/news/update-database',
		'featured' => false
	),
	array(
		'title' => '年末年始休業のご案内',
		'date' => '2026年01月05日',
		'image' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=800&h=600&fit=crop',
		'badge' => 'お知らせ',
		'badge_color' => 'bg-slate-100 text-slate-700',
		'link' => '/news/holiday-notice',
		'featured' => false
	)
);

// 投稿記事を取得（7件まで）
$all_posts_query = new WP_Query(array(
	'post_type' => 'post',
	'posts_per_page' => 7,
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
));

// 事業紹介カード（5カード）
$business_cards = array(
	array(
		'title' => '検査事業',
		'desc' => '不正改造の有無を徹底調査。',
		'href' => '/services/inspection/',
		'icon' => 'ShieldCheck'
	),
	array(
		'title' => '監査事業',
		'desc' => '適正なホール運営を第三者評価。',
		'href' => '/services/audit/',
		'icon' => 'FileText'
	),
	array(
		'title' => '巡回事業',
		'desc' => '現場の脆弱性をプロが診断。',
		'href' => '/services/patrol/',
		'icon' => 'BarChart3'
	),
	array(
		'title' => '教育事業',
		'desc' => '防犯意識を組織の文化へ。',
		'href' => '/services/education/',
		'icon' => 'PlayCircle'
	),
	array(
		'title' => '情報提供事業',
		'desc' => '業界動向と不正情報を配信。',
		'href' => '/services/information/',
		'icon' => 'Database'
	)
);
?>

<main id="main_content" class="l-mainContent top-page">
	
	<!-- カルーセルMV -->
	<section class="top-carousel-mv" id="carousel-mv">
		<?php foreach ($carousel_slides as $index => $slide) : ?>
			<div class="top-carousel-slide <?php echo esc_attr($slide['bg_class']); ?> <?php echo $index === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $index; ?>">
				<div class="top-carousel-slide__overlay"></div>
				<div class="container mx-auto px-4 h-full flex flex-col justify-center relative z-20">
					<div class="max-w-4xl">
						<p class="text-blue-400 font-bold tracking-wider mb-4 text-xs md:text-sm uppercase"><?php echo esc_html($slide['subtitle']); ?></p>
						<h2 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6 whitespace-pre-line">
							<?php 
							$title_parts = explode("\n", $slide['title']);
							if (count($title_parts) > 1) {
								echo esc_html($title_parts[0]) . '<br />';
								echo '<span class="text-blue-400">' . esc_html($title_parts[1]) . '</span>';
							} else {
								echo esc_html($slide['title']);
							}
							?>
						</h2>
						<p class="text-slate-200 text-base md:text-xl max-w-2xl leading-relaxed font-medium">
							<?php echo esc_html($slide['desc']); ?>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		
		<!-- カルーセルコントロール -->
		<div class="top-carousel-controls">
			<div class="container mx-auto px-4 flex justify-between items-center">
				<div class="flex gap-3">
					<button class="top-carousel-btn top-carousel-btn--prev" id="carousel-prev">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
					</button>
					<button class="top-carousel-btn top-carousel-btn--next" id="carousel-next">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
					</button>
				</div>
				<div class="flex gap-2" id="carousel-indicators">
					<?php foreach ($carousel_slides as $index => $slide) : ?>
						<button class="top-carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $index; ?>"></button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- 統計オーバーレイ -->
	<div class="top-stats-overlay">
		<div class="container mx-auto px-4">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
				<div class="top-stats-card">
					<div class="flex items-center gap-2 text-blue-600 mb-3">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M10 2L12 6H16L13 9L14 13L10 11L6 13L7 9L4 6H8L10 2Z" stroke="currentColor" stroke-width="2"/>
						</svg>
						<span class="text-xs font-bold tracking-wide text-slate-600">最新状況</span>
					</div>
					<p class="text-slate-600 text-sm font-medium mb-2">2026年 被害発生件数</p>
					<div class="flex items-baseline gap-2">
						<span class="text-4xl font-bold text-slate-900">0</span>
						<span class="text-slate-500 font-medium text-sm">件</span>
					</div>
				</div>
				<div class="top-stats-card">
					<div class="flex items-center gap-2 text-blue-600 mb-3">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M2 2H8V8H2V2Z M12 2H18V8H12V2Z M2 12H8V18H2V12Z M12 12H18V18H12V12Z" stroke="currentColor" stroke-width="2"/>
						</svg>
						<span class="text-xs font-bold tracking-wide text-slate-600">年間集計</span>
					</div>
					<p class="text-slate-600 text-sm font-medium mb-2">2025年 被害発生件数</p>
					<div class="flex items-baseline gap-2">
						<span class="text-4xl font-bold text-slate-900">65</span>
						<span class="text-slate-500 font-medium text-sm">件</span>
					</div>
				</div>
				<div class="top-stats-card top-stats-card--dark">
					<div class="flex items-center gap-2 text-blue-400 mb-3">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2"/>
							<path d="M10 5V10L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
						<span class="text-xs font-bold tracking-wide">更新情報</span>
					</div>
					<p class="text-base font-medium leading-relaxed">
						最終更新：<?php echo date('Y年m月d日'); ?><br />
						<span class="text-slate-400 text-sm">被害情報を随時更新中</span>
					</p>
				</div>
			</div>
		</div>
	</div>

	<!-- 「はじめての方へ」バナー -->
	<div class="top-first-time-banner">
		<div class="container mx-auto px-4">
			<a href="/firsttime/" class="top-first-time-banner__link group">
				<div class="flex items-center gap-5 md:gap-6 w-full md:w-auto">
					<div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-white/20 rounded-lg flex items-center justify-center">
						<svg width="28" height="28" viewBox="0 0 20 20" fill="none" class="text-white">
							<circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2"/>
							<path d="M10 6V10M10 14H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
					</div>
					<div class="flex flex-col gap-1">
						<h3 class="text-xl md:text-2xl font-bold text-white leading-tight">
							はじめての方へ
						</h3>
						<p class="text-sm md:text-base text-blue-50 font-medium leading-relaxed">
							GT-NETは不正事例から実務マニュアルまで網羅した会員制専門サイトです。<span class="hidden md:inline"> 新規会員登録で、すぐにご利用いただけます。</span>
						</p>
					</div>
				</div>
				<div class="flex items-center gap-2 bg-white px-5 py-3 rounded-lg group-hover:bg-blue-50 transition-colors flex-shrink-0 mt-4 md:mt-0">
					<span class="text-base font-bold text-blue-600 group-hover:text-blue-700">詳しく見る</span>
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-blue-600 group-hover:text-blue-700">
						<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</div>
			</a>
		</div>
	</div>

	<!-- お知らせ・キャンペーンセクション -->
	<section class="py-20 bg-slate-50">
		<div class="container mx-auto px-4 max-w-7xl">
			<div class="flex items-end justify-between mb-12">
				<div>
					<h3 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2 tracking-tight">お知らせ・キャンペーン</h3>
					<p class="text-slate-600 text-sm">GT-NETからのお知らせとキャンペーン情報</p>
				</div>
				<a href="/archives/" class="flex items-center gap-1.5 text-slate-700 hover:text-slate-900 font-medium text-sm transition-colors pb-1">
					すべて見る
					<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
						<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</a>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
				<?php
				// 左側の大きな記事（最初の1件 - ダミーデータ）
				$featured_post = $dummy_campaign_posts[0];
				?>
					<a href="<?php echo esc_url($featured_post['link']); ?>" class="lg:col-span-3 group block bg-white rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-lg transition-all duration-300 overflow-hidden">
						<div class="h-64 md:h-80 bg-slate-100 overflow-hidden relative">
							<img
								src="<?php echo esc_url($featured_post['image']); ?>"
								alt="<?php echo esc_attr($featured_post['title']); ?>"
								class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
							/>
							<div class="absolute top-4 left-4">
								<span class="px-3 py-1.5 <?php echo esc_attr($featured_post['badge_color']); ?> text-white text-xs font-bold rounded-full shadow-lg"><?php echo esc_html($featured_post['badge']); ?></span>
							</div>
						</div>
						<div class="p-6">
							<div class="mb-3">
								<time class="text-sm text-slate-500 font-medium"><?php echo esc_html($featured_post['date']); ?></time>
							</div>
							<h4 class="text-xl md:text-2xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors leading-tight">
								<?php echo esc_html($featured_post['title']); ?>
							</h4>
							<p class="text-slate-600 leading-relaxed text-sm md:text-base">
								<?php echo esc_html($featured_post['excerpt']); ?>
							</p>
						</div>
					</a>

					<!-- 右側の2x2グリッド（残り4件 - ダミーデータ） -->
					<div class="lg:col-span-2 grid grid-cols-2 gap-4">
						<?php
						// 残り4件のダミー記事
						for ($i = 1; $i < 5; $i++) :
							$post = $dummy_campaign_posts[$i];
						?>
							<a href="<?php echo esc_url($post['link']); ?>" class="group bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-300 overflow-hidden">
								<div class="h-32 bg-slate-100 overflow-hidden relative">
									<img
										src="<?php echo esc_url($post['image']); ?>"
										alt="<?php echo esc_attr($post['title']); ?>"
										class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
									/>
									<div class="absolute top-3 left-3">
										<span class="px-2.5 py-1 <?php echo esc_attr($post['badge_color']); ?> text-xs font-bold rounded-full backdrop-blur-sm"><?php echo esc_html($post['badge']); ?></span>
									</div>
								</div>
								<div class="p-3">
									<h4 class="text-sm font-bold text-slate-900 mb-1 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
										<?php echo esc_html($post['title']); ?>
									</h4>
									<time class="text-[10px] text-slate-500 font-medium"><?php echo esc_html($post['date']); ?></time>
								</div>
							</a>
						<?php endfor; ?>
					</div>
			</div>

			<div class="mt-10 text-center">
				<a href="/archives/" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-lg font-bold hover:bg-slate-800 transition-colors">
					すべてのお知らせを見る
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
						<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</a>
			</div>
		</div>
	</section>

	<!-- 事業紹介セクション（5カード） -->
	<section class="top-business-section py-24 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-t border-b border-slate-200 relative overflow-hidden">
		<div class="top-business-section__pattern"></div>
		<div class="container mx-auto px-4 relative z-10">
			<div class="text-center mb-16">
				<div class="inline-block mb-4">
					<div class="w-16 h-1 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto rounded-full"></div>
				</div>
				<h3 class="text-4xl md:text-5xl font-bold mb-4 text-slate-900 tracking-tight">事業紹介</h3>
				<p class="text-slate-600 max-w-2xl mx-auto font-medium text-base">
					30年以上の実績に基づく、パチンコ業界特化型のリスクマネジメント。
				</p>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
				<?php foreach ($business_cards as $card) : ?>
					<a href="<?php echo esc_url($card['href']); ?>" class="group p-6 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-slate-300 hover:shadow-md transition-all duration-200 cursor-pointer">
						<div class="w-12 h-12 rounded-lg bg-slate-900 text-white flex items-center justify-center mb-4 group-hover:bg-blue-600 transition-colors">
							<!-- Icon placeholder -->
							<span class="text-2xl"><?php echo esc_html(substr($card['icon'], 0, 1)); ?></span>
						</div>
						<h4 class="text-lg font-bold mb-3 text-slate-900"><?php echo esc_html($card['title']); ?></h4>
						<p class="text-slate-600 text-sm leading-relaxed mb-4">
							<?php echo esc_html($card['desc']); ?>ホール経営のあらゆるリスクに対応します。
						</p>
						<div class="flex items-center gap-2 text-xs font-bold text-blue-600">
							詳しく見る
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
								<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- 統合ニュースフィード + サイドバー -->
	<section class="top-news-feed-section py-24 bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800 relative overflow-hidden">
		<!-- Background Pattern -->
		<div class="top-news-feed-section__pattern"></div>
		
		<div class="container mx-auto px-4 relative z-10">
			<div class="flex flex-col lg:flex-row gap-12">
				
				<!-- メインニュースフィード -->
				<div class="lg:w-2/3">
					<div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
						<div>
							<h3 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
								最新情報
								<span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
							</h3>
							<p class="text-slate-200 mt-2 text-sm md:text-base font-medium">GT-NETが収集した最新の業界動向と不正情報</p>
						</div>

						<!-- 大カテゴリフィルタータブ -->
						<div class="flex gap-1 overflow-x-auto pb-2 -mb-2" id="main-category-tabs">
							<?php foreach ($main_categories as $cat_id => $cat_label) : ?>
								<button class="top-category-tab top-category-tab--dark <?php echo $cat_id === 'all' ? 'active' : ''; ?>" data-category="<?php echo esc_attr($cat_id); ?>">
									<?php echo esc_html($cat_label); ?>
								</button>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- GTニュース選択時のサブカテゴリフィルター -->
					<div class="top-subcategory-filter hidden mb-6 p-4 bg-white rounded-xl border border-slate-100" id="subcategory-filter">
						<div class="flex items-center gap-3 mb-3">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
								<path d="M3 3H17V17H3V3Z M7 7H13V9H7V7Z M7 11H13V13H7V11Z" stroke="currentColor" stroke-width="2"/>
							</svg>
							<span class="text-sm font-bold text-slate-600">サブカテゴリで絞り込み</span>
						</div>
						<div class="flex flex-wrap gap-2">
							<?php foreach ($gt_news_subcategories as $subcat_id => $subcat_label) : ?>
								<button class="top-subcategory-tab <?php echo $subcat_id === 'all' ? 'active' : ''; ?>" data-subcategory="<?php echo esc_attr($subcat_id); ?>">
									<?php echo esc_html($subcat_label); ?>
								</button>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- ニュースリスト -->
					<div class="space-y-4" id="news-list">
						<?php
						if ($all_posts_query->have_posts()) :
							while ($all_posts_query->have_posts()) : $all_posts_query->the_post();
								$post_categories = get_the_category();
								$category_slug = !empty($post_categories) ? $post_categories[0]->slug : '';
								$category_name = !empty($post_categories) ? $post_categories[0]->name : '';
								
								// 重要フラグ（タグから判定）
								$is_important = false;
								$post_tags = get_the_tags();
								if ($post_tags) {
									foreach ($post_tags as $tag) {
										if (stripos($tag->name, '重要') !== false || stripos($tag->name, '緊急') !== false) {
											$is_important = true;
											break;
										}
									}
								}
								
								// 会員限定フラグ（カスタムフィールドまたはタグから判定）
								$is_member_only = get_post_meta(get_the_ID(), 'member_only', true) === '1';
								if (!$is_member_only && $post_tags) {
									foreach ($post_tags as $tag) {
										if (stripos($tag->name, '会員限定') !== false) {
											$is_member_only = true;
											break;
										}
									}
								}
						?>
							<article class="top-news-item <?php echo $is_important ? 'top-news-item--important' : ''; ?>" data-category="<?php echo esc_attr($category_slug); ?>" data-main-category="<?php echo esc_attr($category_slug); ?>">
								<a href="<?php the_permalink(); ?>" class="group relative bg-white/95 backdrop-blur-sm p-5 rounded-xl border transition-all hover:shadow-lg hover:bg-white cursor-pointer flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 <?php echo $is_important ? 'border-l-4 border-l-red-500 border-white/30' : 'border-white/30'; ?>">
									<div class="flex-shrink-0 flex flex-col items-start sm:items-center w-24">
										<span class="text-xs font-black text-slate-500"><?php echo get_the_date('Y'); ?></span>
										<span class="text-lg font-black text-slate-900"><?php echo get_the_date('m.d'); ?></span>
									</div>

									<div class="flex-grow">
										<div class="flex flex-wrap items-center gap-2 mb-2">
											<span class="px-3 py-0.5 text-[10px] font-black border rounded-full bg-slate-100 text-slate-700 border-slate-200">
												<?php echo esc_html($category_name ?: 'お知らせ'); ?>
											</span>
											<?php if ($is_important) : ?>
												<span class="flex items-center gap-1 px-2 py-0.5 text-[10px] font-black text-white bg-red-500 rounded-full shadow-md">
													<svg width="12" height="12" viewBox="0 0 20 20" fill="none">
														<path d="M10 2L12 6H16L13 9L14 13L10 11L6 13L7 9L4 6H8L10 2Z" stroke="currentColor" stroke-width="2" fill="currentColor"/>
													</svg>
													重要
												</span>
											<?php endif; ?>
										</div>
										<h4 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug flex items-center gap-2">
											<span class="flex-1"><?php the_title(); ?></span>
											<?php if ($is_member_only) : ?>
												<svg width="16" height="16" viewBox="0 0 20 20" fill="none" class="flex-shrink-0 text-slate-400">
													<rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
													<path d="M7 8V5C7 3.34315 8.34315 2 10 2C11.6569 2 13 3.34315 13 5V8" stroke="currentColor" stroke-width="2"/>
												</svg>
											<?php endif; ?>
										</h4>
									</div>

									<div class="flex-shrink-0 flex items-center justify-end">
										<div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
												<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</div>
									</div>
								</a>
							</article>
						<?php
							endwhile;
							wp_reset_postdata();
						else :
						?>
							<div class="text-center py-20 bg-white/95 backdrop-blur-sm rounded-3xl border border-dashed border-white/30 text-slate-300">
								該当する情報はありません
							</div>
						<?php endif; ?>
					</div>

					<!-- もっと見るボタン -->
					<?php if ($all_posts_query->found_posts > 7) : ?>
						<div class="mt-4">
							<a href="/archives/" class="w-full py-5 text-white font-black text-sm border-2 border-white/30 rounded-2xl bg-white/10 backdrop-blur-sm hover:bg-blue-600 hover:border-blue-600 transition-all flex items-center justify-center gap-2 shadow-lg">
								過去の情報をすべて見る
								<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
									<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<!-- サイドバー -->
				<div class="lg:w-1/3 space-y-6">
					<?php get_template_part('parts/sidebar-top'); ?>
				</div>
			</div>
		</div>
	</section>

</main>

<!-- トップページ専用フッター -->
<?php get_template_part('parts/footer-top'); ?>

<?php wp_footer(); ?>
</body>
</html>
