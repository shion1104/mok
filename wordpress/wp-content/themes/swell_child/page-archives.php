<?php
/**
 * Template Name: ニュース一覧
 * 
 * ニュース一覧ページ（大カテゴリ+サブカテゴリフィルタ）
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

// 大カテゴリの定義
$main_categories = array(
	'all' => 'すべて',
	'gt-news' => 'GTニュース',
	'industry' => '業界ニュース',
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

// URLパラメータからカテゴリを取得
$selected_main_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : 'all';
if (!array_key_exists($selected_main_category, $main_categories)) {
	$selected_main_category = 'all';
}

$selected_sub_category = isset($_GET['subcategory']) ? sanitize_text_field($_GET['subcategory']) : 'all';

// 現在のページ番号
$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
$posts_per_page = 10;

// クエリパラメータ
$query_args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => $posts_per_page,
	'paged' => $paged,
	'orderby' => 'date',
	'order' => 'DESC',
);

// 大カテゴリでフィルター
if ($selected_main_category !== 'all') {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => 'category',
			'field' => 'slug',
			'terms' => $selected_main_category,
		),
	);
}

// サブカテゴリでフィルター（GTニュースの場合のみ）
if ($selected_main_category === 'gt-news' && $selected_sub_category !== 'all') {
	// サブカテゴリはタグとして実装する場合
	$query_args['tag'] = $selected_sub_category;
}

$news_query = new WP_Query($query_args);
?>

<main id="main_content" class="l-mainContent archives-page">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-4xl mx-auto">
			<!-- ページタイトル -->
			<div class="mb-10">
				<h1 class="text-3xl font-black text-slate-900 mb-2">ニュース一覧</h1>
				<p class="text-slate-500">GT-NETが収集した業界動向と不正情報のアーカイブ</p>
			</div>

			<!-- パンくずリスト -->
			<div class="bg-white border-b border-slate-100 mb-6">
				<div class="flex items-center gap-2 text-sm text-slate-500 py-3">
					<a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 transition-colors">トップ</a>
					<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
						<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
					<span class="text-slate-900 font-bold">ニュース一覧</span>
				</div>
			</div>

			<!-- 大カテゴリタブ -->
			<div class="mb-6">
				<div class="flex flex-wrap gap-2">
					<?php foreach ($main_categories as $cat_id => $cat_label) : ?>
						<a href="<?php echo esc_url(add_query_arg('category', $cat_id, remove_query_arg('subcategory'))); ?>" 
						   class="top-category-tab <?php echo $selected_main_category === $cat_id ? 'active' : ''; ?>">
							<?php echo esc_html($cat_label); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- サブカテゴリフィルター（GTニュースのみ） -->
			<?php if ($selected_main_category === 'gt-news') : ?>
				<div class="mb-8 p-4 bg-white rounded-xl border border-slate-100">
					<div class="flex items-center gap-3 mb-3">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
							<path d="M3 3H17V17H3V3Z M7 7H13V9H7V7Z M7 11H13V13H7V11Z" stroke="currentColor" stroke-width="2"/>
						</svg>
						<span class="text-sm font-bold text-slate-600">サブカテゴリで絞り込み</span>
					</div>
					<div class="flex flex-wrap gap-2">
						<?php foreach ($gt_news_subcategories as $subcat_id => $subcat_label) : ?>
							<a href="<?php echo esc_url(add_query_arg(array('category' => 'gt-news', 'subcategory' => $subcat_id))); ?>" 
							   class="top-subcategory-tab <?php echo $selected_sub_category === $subcat_id ? 'active' : ''; ?>">
								<?php echo esc_html($subcat_label); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- 結果件数 -->
			<div class="mb-4 text-sm text-slate-500">
				<?php echo number_format($news_query->found_posts); ?>件の記事
				<?php if ($selected_main_category !== 'all') : ?>
					- <?php echo esc_html($main_categories[$selected_main_category]); ?>
				<?php endif; ?>
				<?php if ($selected_main_category === 'gt-news' && $selected_sub_category !== 'all') : ?>
					/ <?php echo esc_html($gt_news_subcategories[$selected_sub_category]); ?>
				<?php endif; ?>
			</div>

			<!-- ニュースリスト -->
			<div class="space-y-3">
				<?php if ($news_query->have_posts()) : ?>
					<?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
						<?php
						$post_categories = get_the_category();
						$category_name = !empty($post_categories) ? $post_categories[0]->name : '';
						
						// 重要フラグ
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
						
						// 会員限定フラグ
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
						<article class="top-news-item <?php echo $is_important ? 'top-news-item--important' : ''; ?>">
							<a href="<?php the_permalink(); ?>" class="group block bg-white p-5 rounded-xl border transition-all hover:shadow-lg hover:-translate-y-0.5 <?php echo $is_important ? 'border-l-4 border-l-red-500 border-slate-200' : 'border-slate-100'; ?>">
								<div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6">
									<div class="flex-shrink-0 text-sm text-slate-400 font-bold w-24">
										<?php echo get_the_date('Y-m-d'); ?>
									</div>
									<div class="flex-grow">
										<div class="flex flex-wrap items-center gap-2 mb-1.5">
											<span class="px-2.5 py-0.5 text-[10px] font-black bg-slate-100 text-slate-600 rounded-full border border-slate-200">
												<?php echo esc_html($category_name ?: 'お知らせ'); ?>
											</span>
											<?php if ($is_important) : ?>
												<span class="flex items-center gap-1 px-2 py-0.5 text-[10px] font-black text-white bg-red-500 rounded-full">
													<svg width="12" height="12" viewBox="0 0 20 20" fill="none">
														<path d="M10 2L12 6H16L13 9L14 13L10 11L6 13L7 9L4 6H8L10 2Z" stroke="currentColor" stroke-width="2"/>
													</svg>
													重要
												</span>
											<?php endif; ?>
										</div>
										<h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors flex items-center gap-2">
											<?php the_title(); ?>
											<?php if ($is_member_only) : ?>
												<svg width="16" height="16" viewBox="0 0 20 20" fill="none" class="flex-shrink-0">
													<rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
													<path d="M7 8V5C7 3.34315 8.34315 2 10 2C11.6569 2 13 3.34315 13 5V8" stroke="currentColor" stroke-width="2"/>
												</svg>
											<?php endif; ?>
										</h3>
									</div>
									<div class="flex-shrink-0">
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-slate-300 group-hover:text-blue-500 transition-colors">
											<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
										</svg>
									</div>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				<?php else : ?>
					<div class="text-center py-20 bg-white rounded-xl border border-dashed border-slate-200 text-slate-400">
						該当する記事がありません
					</div>
				<?php endif; ?>
			</div>

			<?php wp_reset_postdata(); ?>

			<!-- ページネーション -->
			<?php if ($news_query->max_num_pages > 1) : ?>
				<div class="mt-10 flex items-center justify-center gap-2">
					<?php
					echo paginate_links(array(
						'total' => $news_query->max_num_pages,
						'current' => $paged,
						'prev_text' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
						'next_text' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
						'type' => 'list',
						'format' => '?paged=%#%',
						'add_args' => array(
							'category' => $selected_main_category,
							'subcategory' => $selected_main_category === 'gt-news' ? $selected_sub_category : ''
						)
					));
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
