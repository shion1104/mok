<?php
/**
 * お知らせ・キャンペーンセクション（モックデザイン準拠）
 */
// 最新の投稿を5件取得（1件目をフィーチャー、2-5件目をグリッド用）
$news_query = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => 5,
  'post_status' => 'publish',
  'ignore_sticky_posts' => true,
  'orderby' => 'date',
  'order' => 'DESC',
]);

$featured_post = null;
$grid_posts = [];

if ($news_query->have_posts()) {
  $post_index = 0;
  while ($news_query->have_posts()) {
    $news_query->the_post();
    if ($post_index === 0) {
      $featured_post = get_post();
    } else {
      $grid_posts[] = get_post();
    }
    $post_index++;
  }
  wp_reset_postdata();
}
?>
<section class="py-20 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="flex items-end justify-between mb-12">
      <div>
        <h3 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2 tracking-tight">お知らせ・キャンペーン</h3>
        <p class="text-slate-600 text-sm">GT-NETからのお知らせとキャンペーン情報</p>
      </div>
      <a href="<?php echo esc_url(home_url('/archives/')); ?>" class="flex items-center gap-1.5 text-slate-700 hover:text-slate-900 font-medium text-sm transition-colors pb-1">
        すべて見る
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>

    <?php if ($featured_post || !empty($grid_posts)): ?>
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Featured News - Left Large -->
        <?php if ($featured_post): 
          setup_postdata($featured_post);
          $thumbnail_url = get_the_post_thumbnail_url($featured_post->ID, 'large');
          if (!$thumbnail_url) {
            $thumbnail_url = 'https://images.unsplash.com/photo-1556761175-4b46a572b786?w=1200&h=500&fit=crop';
          }
        ?>
          <a href="<?php echo esc_url(get_permalink($featured_post->ID)); ?>" class="lg:col-span-3 group block bg-white rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="h-64 md:h-80 bg-slate-100 overflow-hidden relative">
              <img
                src="<?php echo esc_url($thumbnail_url); ?>"
                alt="<?php echo esc_attr(get_the_title($featured_post->ID)); ?>"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-full shadow-lg">注目</span>
              </div>
            </div>
            <div class="p-6">
              <div class="mb-3">
                <time class="text-sm text-slate-500 font-medium"><?php echo esc_html(get_the_date('Y年m月d日', $featured_post->ID)); ?></time>
              </div>
              <h4 class="text-xl md:text-2xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors leading-tight">
                <?php echo esc_html(get_the_title($featured_post->ID)); ?>
              </h4>
              <?php if (has_excerpt($featured_post->ID)): ?>
                <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                  <?php echo esc_html(get_the_excerpt($featured_post->ID)); ?>
                </p>
              <?php endif; ?>
            </div>
          </a>
          <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <!-- News Grid - Right 2x2 -->
        <?php if (!empty($grid_posts)): ?>
          <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <?php foreach (array_slice($grid_posts, 0, 4) as $grid_post): 
              setup_postdata($grid_post);
              $categories = get_the_category($grid_post->ID);
              $category_name = !empty($categories) ? $categories[0]->name : 'お知らせ';
              
              $thumbnail_url = get_the_post_thumbnail_url($grid_post->ID, 'medium');
              if (!$thumbnail_url) {
                $thumbnail_url = 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&h=600&fit=crop';
              }
              
              // カテゴリに応じたバッジの色
              $badge_class = 'bg-blue-50 text-blue-700';
              if (strpos($category_name, 'キャンペーン') !== false || strpos($category_name, 'campaign') !== false) {
                $badge_class = 'bg-green-50 text-green-700';
              } elseif (strpos($category_name, 'アップデート') !== false || strpos($category_name, 'update') !== false) {
                $badge_class = 'bg-purple-50 text-purple-700';
              }
            ?>
              <a href="<?php echo esc_url(get_permalink($grid_post->ID)); ?>" class="group bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-32 bg-slate-100 overflow-hidden relative">
                  <img
                    src="<?php echo esc_url($thumbnail_url); ?>"
                    alt="<?php echo esc_attr(get_the_title($grid_post->ID)); ?>"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div class="absolute top-2 left-2">
                    <span class="px-2 py-0.5 <?php echo esc_attr($badge_class); ?> text-[10px] font-bold rounded-full backdrop-blur-sm"><?php echo esc_html($category_name); ?></span>
                  </div>
                </div>
                <div class="p-3">
                  <h4 class="text-sm font-bold text-slate-900 mb-1.5 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                    <?php echo esc_html(get_the_title($grid_post->ID)); ?>
                  </h4>
                  <time class="text-[10px] text-slate-500 font-medium"><?php echo esc_html(get_the_date('Y年m月d日', $grid_post->ID)); ?></time>
                </div>
              </a>
            <?php 
              wp_reset_postdata();
            endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <p class="text-slate-600">記事がありません。</p>
    <?php endif; ?>
  </div>
</section>
