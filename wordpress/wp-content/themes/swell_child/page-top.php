<?php
/**
 * Template Name: トップページ
 * 
 * パチンコ不正対策会社向けトップページテンプレート
 * Next.jsのpage.tsxから変換
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
		'bg_class' => 'bg-slate-900',
		'accent_class' => 'text-blue-300'
	),
	array(
		'id' => 3,
		'title' => "教育こそが\n最大の防御策",
		'subtitle' => 'スタッフ教育',
		'desc' => 'スタッフの意識を変え、有事に強い組織を作る。独自のカリキュラムで防犯スキルを向上。',
		'bg_class' => 'bg-slate-900',
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
				<div class="top-carousel-slide__visual-elements">
					<div class="top-carousel-slide__triangle"></div>
					<div class="top-carousel-slide__gradient"></div>
				</div>
				<div class="container mx-auto px-4 h-full flex flex-col justify-center relative z-20">
					<div class="max-w-4xl">
						<p class="font-bold tracking-widest mb-4 text-sm md:text-base uppercase" style="color: #dbeafe; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);"><?php echo esc_html($slide['subtitle']); ?></p>
						<h2 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-8 whitespace-pre-line">
							<?php 
							$title_parts = explode("\n", $slide['title']);
							if (count($title_parts) > 1) {
								echo esc_html($title_parts[0]) . '<br />';
								echo '<span class="' . esc_attr($slide['accent_class']) . '">' . esc_html($title_parts[1]) . '</span>';
							} else {
								echo esc_html($slide['title']);
							}
							?>
						</h2>
						<p class="text-white text-base md:text-xl max-w-2xl leading-relaxed" style="opacity: 0.95;">
							<?php echo esc_html($slide['desc']); ?>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		
		<!-- カルーセルコントロール -->
		<div class="top-carousel-controls">
			<div class="container mx-auto px-4 flex justify-between items-center">
				<div class="flex gap-4">
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
				<div class="flex gap-3" id="carousel-indicators">
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
			<div class="grid grid-cols-1 md:grid-cols-3 gap-1">
				<div class="top-stats-card top-stats-card--alert">
					<div class="flex items-center gap-3 text-red-600 mb-2">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M10 2L12 6H16L13 9L14 13L10 11L6 13L7 9L4 6H8L10 2Z" stroke="currentColor" stroke-width="2"/>
						</svg>
						<span class="text-xs font-bold tracking-tighter">最新状況</span>
					</div>
					<p class="text-slate-500 text-sm font-bold">2026年 被害発生件数</p>
					<div class="flex items-baseline gap-2 mt-1">
						<span class="text-5xl font-bold text-slate-900" data-animate="count-up" data-target="0">0</span>
						<span class="text-slate-400 font-bold text-xs">件</span>
					</div>
				</div>
				<div class="top-stats-card">
					<div class="flex items-center gap-3 text-blue-600 mb-2">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M2 2H8V8H2V2Z M12 2H18V8H12V2Z M2 12H8V18H2V12Z M12 12H18V18H12V12Z" stroke="currentColor" stroke-width="2"/>
						</svg>
						<span class="text-xs font-bold tracking-tighter">年間集計</span>
					</div>
					<p class="text-slate-500 text-sm font-bold">2025年 被害発生件数</p>
					<div class="flex items-baseline gap-2 mt-1">
						<span class="text-5xl font-bold text-slate-900" data-animate="count-up" data-target="65">0</span>
						<span class="text-slate-400 font-bold text-xs">件</span>
					</div>
				</div>
				<div class="top-stats-card top-stats-card--dark">
					<div class="relative z-10">
						<p class="text-blue-300 text-xs font-bold mb-2 tracking-widest flex items-center gap-2">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
								<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2"/>
								<path d="M10 5V10L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
							</svg>
							更新情報
						</p>
						<p class="text-lg font-bold leading-snug">
							最終更新：<time datetime="<?php echo date('Y-m-d'); ?>"><?php echo date('Y年m月d日'); ?></time><br>
							<span class="text-slate-400 text-sm">被害情報を随時更新中</span>
						</p>
					</div>
					<svg class="absolute -right-6 -bottom-6 w-32 h-32 text-white/5" width="128" height="128" viewBox="0 0 128 128" fill="none">
						<path d="M64 8L80 40H112L88 56L96 88L64 72L32 88L40 56L16 40H48L64 8Z" stroke="currentColor" stroke-width="2" fill="currentColor"/>
					</svg>
				</div>
			</div>
		</div>
	</div>

	<!-- 「はじめての方へ」バナー -->
	<div class="top-first-time-banner">
		<div class="container mx-auto px-4">
			<a href="/firsttime/" class="top-first-time-banner__link">
				<div class="flex items-center gap-4">
					<svg width="24" height="24" viewBox="0 0 20 20" fill="none" class="flex-shrink-0">
						<circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2"/>
						<path d="M10 6V10M10 14H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
					<p class="text-slate-600">
						<span class="font-bold text-slate-700">はじめての方へ</span>
						<span class="hidden sm:inline"> ー GT-NETは不正事例から実務マニュアルまで網羅した会員制専門サイトです</span>
					</p>
				</div>
				<div class="flex items-center gap-1 text-blue-500 font-bold flex-shrink-0">
					<span class="hidden sm:inline">詳しく見る</span>
					<svg width="24" height="24" viewBox="0 0 20 20" fill="none">
						<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
				</div>
			</a>
		</div>
	</div>

	<!-- 事業紹介セクション（5カード） -->
	<section class="top-business-section py-24 bg-white relative overflow-hidden">
		<div class="container mx-auto px-4 relative z-10">
			<div class="mb-16">
				<h3 class="text-4xl md:text-5xl font-bold mb-4 text-slate-900">SERVICES</h3>
				<p class="text-slate-600 max-w-2xl font-medium">
					30年以上の実績に基づく、パチンコ業界特化型のリスクマネジメント。
				</p>
			</div>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
				<?php foreach ($business_cards as $card) : ?>
					<a href="<?php echo esc_url($card['href']); ?>" class="group p-8 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-slate-300 hover:shadow-lg transition-all duration-300 cursor-pointer">
						<div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center mb-6 shadow-sm group-hover:bg-slate-800 transition-colors">
							<!-- Icon will be rendered via CSS/JS -->
							<span class="text-2xl"><?php echo esc_html(substr($card['icon'], 0, 1)); ?></span>
						</div>
						<h4 class="text-xl font-bold mb-4 text-slate-900"><?php echo esc_html($card['title']); ?></h4>
						<p class="text-slate-600 group-hover:text-slate-700 text-sm leading-relaxed mb-6">
							<?php echo esc_html($card['desc']); ?>
						</p>
						<div class="flex items-center gap-2 text-xs font-bold text-slate-700 group-hover:text-slate-900">
							Learn More
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
								<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
							</svg>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- 統合ニュースフィード + サイドバー -->
	<section class="top-news-feed-section py-24 bg-white">
		<div class="container mx-auto px-4">
			<div class="flex flex-col lg:flex-row gap-12">
				
				<!-- メインニュースフィード -->
				<div class="lg:w-2/3">
					<div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-slate-200">
						<div>
							<div class="flex items-center gap-3 mb-3">
								<div class="w-1 h-8 bg-gradient-to-b from-blue-600 to-blue-800 rounded-full"></div>
								<h3 class="text-4xl md:text-5xl font-bold text-slate-900 tracking-tight">
									NEWS
								</h3>
							</div>
							<p class="text-slate-600 mt-3 text-base font-medium leading-relaxed">GT-NETが収集した最新の業界動向と不正情報</p>
						</div>

						<!-- 大カテゴリフィルタータブ -->
						<div class="flex gap-2 overflow-x-auto pb-2 -mb-2" id="main-category-tabs">
							<?php foreach ($main_categories as $cat_id => $cat_label) : ?>
								<button class="top-category-tab <?php echo $cat_id === 'all' ? 'active' : ''; ?>" data-category="<?php echo esc_attr($cat_id); ?>">
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
							$post_count = 0;
							while ($all_posts_query->have_posts()) : $all_posts_query->the_post();
								$post_count++;
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
								<a href="<?php the_permalink(); ?>" class="group relative bg-white p-6 md:p-8 rounded-2xl border border-slate-200 transition-all duration-300 hover:shadow-lg hover:border-slate-300 cursor-pointer flex flex-col sm:flex-row sm:items-center gap-6 sm:gap-8">
									<div class="flex-shrink-0 flex flex-col items-start sm:items-center w-28 sm:w-24">
										<span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"><?php echo get_the_date('Y'); ?></span>
										<div class="flex items-baseline gap-1">
											<span class="text-2xl font-bold text-slate-900"><?php echo get_the_date('d'); ?></span>
											<span class="text-sm font-semibold text-slate-600"><?php echo get_the_date('M'); ?></span>
										</div>
									</div>
									<div class="flex-grow min-w-0">
										<div class="flex flex-wrap items-center gap-2 mb-3">
											<span class="px-3 py-1 text-xs font-semibold rounded-md bg-slate-100 text-slate-700 border border-slate-200">
												<?php echo esc_html($category_name ?: 'お知らせ'); ?>
											</span>
											<?php if ($is_important) : ?>
												<span class="flex items-center gap-1.5 px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-md shadow-sm">
													<svg width="12" height="12" viewBox="0 0 20 20" fill="none">
														<path d="M10 2L12 6H16L13 9L14 13L10 11L6 13L7 9L4 6H8L10 2Z" stroke="currentColor" stroke-width="2" fill="currentColor"/>
													</svg>
													重要
												</span>
											<?php endif; ?>
										</div>
										<h4 class="text-lg md:text-xl font-bold text-slate-900 group-hover:text-blue-700 transition-colors leading-snug flex items-start gap-2">
											<span class="flex-1"><?php the_title(); ?></span>
											<?php if ($is_member_only) : ?>
												<svg width="18" height="18" viewBox="0 0 20 20" fill="none" class="flex-shrink-0 mt-0.5 text-slate-400">
													<rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
													<path d="M7 8V5C7 3.34315 8.34315 2 10 2C11.6569 2 13 3.34315 13 5V8" stroke="currentColor" stroke-width="2"/>
												</svg>
											<?php endif; ?>
										</h4>
									</div>
									<div class="flex-shrink-0 flex items-center justify-end">
										<div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-md">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="group-hover:translate-x-0.5 transition-transform duration-300">
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
							<div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-400">
								該当する情報はありません
							</div>
						<?php endif; ?>
					</div>

					<!-- もっと見るボタン -->
					<?php if ($all_posts_query->found_posts > 7) : ?>
						<div class="mt-8">
							<a href="/archives/" class="w-full flex items-center justify-center gap-2 px-8 py-5 text-slate-900 font-bold text-lg border-2 border-slate-200 rounded-xl hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300">
								もっと見る
								<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="transition-transform duration-300 group-hover:translate-x-1">
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
