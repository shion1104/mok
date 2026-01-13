<?php
/**
 * Template Name: 動画ライブラリ
 * 
 * 動画ライブラリ一覧ページ（カテゴリフィルタ）
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

// 動画カテゴリの定義
$movie_categories = array(
	'all' => 'すべて',
	'gt-movie' => 'GTムービー',
	'hankou' => '犯行動画',
	'kensyou' => '検証動画'
);

// URLパラメータからカテゴリを取得
$selected_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : 'all';
if (!array_key_exists($selected_category, $movie_categories)) {
	$selected_category = 'all';
}

// 現在のページ番号
$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
$posts_per_page = 12;

// クエリパラメータ（動画カスタム投稿タイプがある場合）
$query_args = array(
	'post_type' => 'movie', // カスタム投稿タイプ「動画」がある場合、通常の投稿の場合は'post'に変更
	'post_status' => 'publish',
	'posts_per_page' => $posts_per_page,
	'paged' => $paged,
	'orderby' => 'date',
	'order' => 'DESC',
);

// カスタム投稿タイプ「動画」がない場合は通常の投稿を使用し、カテゴリでフィルター
if (!post_type_exists('movie')) {
	$query_args['post_type'] = 'post';
	// 動画カテゴリはタグとして実装する場合
	if ($selected_category !== 'all') {
		$query_args['tag'] = $selected_category;
	}
} else {
	// カスタムタクソノミーでフィルターする場合
	if ($selected_category !== 'all') {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'movie_category',
				'field' => 'slug',
				'terms' => $selected_category,
			),
		);
	}
}

$movies_query = new WP_Query($query_args);
?>

<main id="main_content" class="l-mainContent movies-page">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-5xl mx-auto">
			<!-- ページタイトル -->
			<div class="mb-10">
				<div class="flex items-center gap-3 mb-2">
					<svg width="32" height="32" viewBox="0 0 20 20" fill="none" class="text-blue-500">
						<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2"/>
						<path d="M7 6L13 10L7 14V6Z" fill="currentColor"/>
					</svg>
					<h1 class="text-3xl font-black text-slate-900">動画ライブラリ</h1>
				</div>
				<p class="text-slate-500">ゴト対策に役立つ検証動画・解説動画を公開しています</p>
				<div class="mt-3 flex items-center gap-2 text-sm text-amber-600 bg-amber-50 px-4 py-2 rounded-lg border border-amber-100">
					<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
						<rect x="3" y="8" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
						<path d="M7 8V5C7 3.34315 8.34315 2 10 2C11.6569 2 13 3.34315 13 5V8" stroke="currentColor" stroke-width="2"/>
					</svg>
					<span class="font-bold">会員限定コンテンツ</span>
					<span class="text-amber-500">- 動画の視聴には会員ログインが必要です</span>
				</div>
			</div>

			<!-- パンくずリスト -->
			<div class="bg-white border-b border-slate-100 mb-6">
				<div class="flex items-center gap-2 text-sm text-slate-500 py-3">
					<a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 transition-colors">トップ</a>
					<svg width="16" height="16" viewBox="0 0 20 20" fill="none">
						<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
					<span class="text-slate-900 font-bold">動画ライブラリ</span>
				</div>
			</div>

			<!-- カテゴリタブ -->
			<div class="mb-8">
				<div class="flex flex-wrap gap-2">
					<?php foreach ($movie_categories as $cat_id => $cat_label) : ?>
						<a href="<?php echo esc_url(add_query_arg('category', $cat_id)); ?>" 
						   class="top-category-tab <?php echo $selected_category === $cat_id ? 'active' : ''; ?>">
							<?php echo esc_html($cat_label); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- 結果件数 -->
			<div class="mb-4 text-sm text-slate-500">
				<?php echo number_format($movies_query->found_posts); ?>件の動画
				<?php if ($selected_category !== 'all') : ?>
					- <?php echo esc_html($movie_categories[$selected_category]); ?>
				<?php endif; ?>
			</div>

			<!-- 動画グリッド -->
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
				<?php if ($movies_query->have_posts()) : ?>
					<?php while ($movies_query->have_posts()) : $movies_query->the_post(); ?>
						<?php
						// 動画のカテゴリを取得
						$movie_category = '';
						if (post_type_exists('movie') && has_term('', 'movie_category')) {
							$terms = get_the_terms(get_the_ID(), 'movie_category');
							if (!empty($terms)) {
								$movie_category = $terms[0]->slug;
							}
						} else {
							// タグから判定
							$post_tags = get_the_tags();
							if ($post_tags) {
								foreach ($post_tags as $tag) {
									if (in_array($tag->slug, array('gt-movie', 'hankou', 'kensyou'))) {
										$movie_category = $tag->slug;
										break;
									}
								}
							}
						}
						
						// 動画の長さ（カスタムフィールド）
						$duration = get_post_meta(get_the_ID(), 'video_duration', true) ?: '00:00';
						
						// カテゴリラベルの取得
						$category_label = '動画';
						foreach ($movie_categories as $cat_id => $cat_label) {
							if ($cat_id === $movie_category) {
								$category_label = $cat_label;
								break;
							}
						}
						
						// カテゴリの色クラス
						$category_color_class = 'bg-slate-100 text-slate-700 border-slate-200';
						switch ($movie_category) {
							case 'gt-movie':
								$category_color_class = 'bg-blue-100 text-blue-700 border-blue-200';
								break;
							case 'hankou':
								$category_color_class = 'bg-red-100 text-red-700 border-red-200';
								break;
							case 'kensyou':
								$category_color_class = 'bg-green-100 text-green-700 border-green-200';
								break;
						}
						?>
						<article class="top-movie-item group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">
							<a href="<?php the_permalink(); ?>">
								<!-- サムネイルプレースホルダー -->
								<div class="relative aspect-video bg-slate-900 flex items-center justify-center">
									<?php if (has_post_thumbnail()) : ?>
										<?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
									<?php else : ?>
										<svg width="64" height="64" viewBox="0 0 20 20" fill="none" class="text-white/30 group-hover:text-white/60 group-hover:scale-110 transition-all">
											<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2"/>
											<path d="M7 6L13 10L7 14V6Z" fill="currentColor"/>
										</svg>
									<?php endif; ?>
									<!-- 動画の長さ -->
									<div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs font-bold px-2 py-1 rounded flex items-center gap-1">
										<svg width="12" height="12" viewBox="0 0 20 20" fill="none">
											<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2"/>
											<path d="M10 5V10L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
										</svg>
										<?php echo esc_html($duration); ?>
									</div>
									<!-- カテゴリバッジ -->
									<div class="absolute top-2 left-2">
										<span class="px-2 py-1 text-[10px] font-black rounded border <?php echo esc_attr($category_color_class); ?>">
											<?php echo esc_html($category_label); ?>
										</span>
									</div>
								</div>
								<!-- 情報 -->
								<div class="p-4">
									<p class="text-xs text-slate-400 mb-1"><?php echo get_the_date('Y-m-d'); ?></p>
									<h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-sm leading-snug line-clamp-2">
										<?php the_title(); ?>
									</h3>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				<?php else : ?>
					<div class="col-span-full text-center py-20 bg-white rounded-xl border border-dashed border-slate-200 text-slate-400">
						該当する動画がありません
					</div>
				<?php endif; ?>
			</div>

			<?php wp_reset_postdata(); ?>

			<!-- ページネーション -->
			<?php if ($movies_query->max_num_pages > 1) : ?>
				<div class="mt-10 flex items-center justify-center gap-2">
					<?php
					echo paginate_links(array(
						'total' => $movies_query->max_num_pages,
						'current' => $paged,
						'prev_text' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
						'next_text' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
						'type' => 'list',
						'format' => '?paged=%#%',
						'add_args' => array('category' => $selected_category)
					));
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
