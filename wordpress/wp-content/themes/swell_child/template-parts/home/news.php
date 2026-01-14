<?php
/**
 * お知らせ・キャンペーンセクション
 */
// 最新の投稿を4件取得
$news_query = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => 4,
  'post_status' => 'publish',
  'ignore_sticky_posts' => true,
  'orderby' => 'date',
  'order' => 'DESC',
]);
?>
<section class="py-24 bg-gradient-to-br from-blue-50 via-white to-slate-50 border-t border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6">
    <div class="mb-12">
      <h3 class="text-4xl md:text-5xl font-bold mb-4 text-slate-900 tracking-tight">お知らせ・キャンペーン</h3>
    </div>

    <?php if ($news_query->have_posts()): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php while ($news_query->have_posts()): $news_query->the_post(); ?>
          <?php
          // カテゴリ取得
          $categories = get_the_category();
          $category_name = !empty($categories) ? $categories[0]->name : '';
          
          // アイキャッチ画像取得
          $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
          if (!$thumbnail_url) {
            $thumbnail_url = get_template_directory_uri() . '/assets/images/default-thumbnail.jpg';
          }
          ?>
          <article class="group overflow-hidden rounded-xl bg-white border border-slate-200 hover:shadow-lg transition-all duration-200">
            <?php if ($thumbnail_url): ?>
              <a href="<?php echo esc_url(get_permalink()); ?>" class="block aspect-video overflow-hidden bg-slate-200">
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              </a>
            <?php endif; ?>
            
            <div class="p-6">
              <?php if ($category_name): ?>
                <span class="inline-block px-3 py-1 mb-3 text-xs font-medium text-blue-600 bg-blue-50 rounded-full">
                  <?php echo esc_html($category_name); ?>
                </span>
              <?php endif; ?>
              
              <h4 class="text-lg font-bold mb-3 text-slate-900 group-hover:text-blue-600 transition-colors">
                <a href="<?php echo esc_url(get_permalink()); ?>">
                  <?php echo esc_html(get_the_title()); ?>
                </a>
              </h4>
              
              <time class="text-xs text-slate-500" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date('Y.m.d')); ?>
              </time>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      
      <?php wp_reset_postdata(); ?>
    <?php else: ?>
      <p class="text-slate-600">記事がありません。</p>
    <?php endif; ?>
  </div>
</section>
