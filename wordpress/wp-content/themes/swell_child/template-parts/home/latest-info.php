<?php
/**
 * 最新情報セクション
 */
// 最新の投稿を15件取得
$latest_query = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => 15,
  'post_status' => 'publish',
  'ignore_sticky_posts' => true,
  'orderby' => 'date',
  'order' => 'DESC',
]);
?>
<section class="py-24 bg-white border-t border-slate-200">
  <div class="container mx-auto px-4 md:px-6">
    <div class="mb-12">
      <h3 class="text-4xl md:text-5xl font-bold mb-4 text-slate-900 tracking-tight">最新情報</h3>
    </div>

    <?php if ($latest_query->have_posts()): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($latest_query->have_posts()): $latest_query->the_post(); ?>
          <?php
          // カテゴリ取得
          $categories = get_the_category();
          $category_name = !empty($categories) ? $categories[0]->name : '';
          
          // 重要タグチェック
          $tags = get_the_tags();
          $is_important = false;
          if ($tags) {
            foreach ($tags as $tag) {
              if (strpos(strtolower($tag->name), 'important') !== false || strpos(strtolower($tag->name), '重要') !== false) {
                $is_important = true;
                break;
              }
            }
          }
          
          // メンバー限定チェック（カスタムフィールドまたはタグ）
          $is_member_only = get_post_meta(get_the_ID(), 'member_only', true) === 'yes';
          if (!$is_member_only && $tags) {
            foreach ($tags as $tag) {
              if (strpos(strtolower($tag->name), 'member') !== false || strpos(strtolower($tag->name), 'メンバー') !== false) {
                $is_member_only = true;
                break;
              }
            }
          }
          ?>
          <article class="group p-6 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-slate-300 hover:shadow-md transition-all duration-200">
            <?php if ($is_important): ?>
              <span class="inline-block px-3 py-1 mb-3 text-xs font-bold text-red-600 bg-red-50 rounded-full">重要</span>
            <?php endif; ?>
            
            <?php if ($is_member_only): ?>
              <span class="inline-block px-3 py-1 mb-3 text-xs font-bold text-blue-600 bg-blue-50 rounded-full">メンバー限定</span>
            <?php endif; ?>
            
            <?php if ($category_name): ?>
              <span class="inline-block px-3 py-1 mb-3 text-xs font-medium text-slate-600 bg-slate-100 rounded-full">
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
          </article>
        <?php endwhile; ?>
      </div>
      
      <?php wp_reset_postdata(); ?>
    <?php else: ?>
      <p class="text-slate-600">記事がありません。</p>
    <?php endif; ?>
  </div>
</section>
