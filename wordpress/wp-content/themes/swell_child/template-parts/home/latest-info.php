<?php
/**
 * 最新情報セクション（サイドバー付き）- 紺色背景、フィルター横配置版
 */
// 最新の投稿を取得（フィルター用）
$all_posts_query = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  'ignore_sticky_posts' => true,
  'orderby' => 'date',
  'order' => 'DESC',
]);

// カテゴリ別に投稿を分類
$posts_by_category = [];
$all_posts = [];

if ($all_posts_query->have_posts()) {
  while ($all_posts_query->have_posts()) {
    $all_posts_query->the_post();
    $categories = get_the_category();
    $category_slug = !empty($categories) ? $categories[0]->slug : 'uncategorized';
    
    if (!isset($posts_by_category[$category_slug])) {
      $posts_by_category[$category_slug] = [];
    }
    $posts_by_category[$category_slug][] = get_post();
    $all_posts[] = get_post();
  }
  wp_reset_postdata();
}

// 表示用に最初の15件を取得
$display_posts = array_slice($all_posts, 0, 15);
?>
<section class="py-24 bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800 relative overflow-hidden">
  <!-- Background Pattern -->
  <div class="absolute inset-0 opacity-10 pointer-events-none">
    <div class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_80%_20%,rgba(59,130,246,0.2),transparent_50%)]"></div>
  </div>
  
  <div class="container mx-auto px-4 md:px-6 relative z-10">
    <!-- Header with Title, Subtitle, Filter Tabs, and Sidebar -->
    <div class="flex flex-col lg:flex-row gap-8 mb-10">
      <!-- Left: Title, Subtitle, Filter Tabs -->
      <div class="lg:w-2/3 flex flex-col gap-6">
        <div>
          <h3 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3 mb-2">
            最新情報
            <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
          </h3>
          <p class="text-slate-200 text-sm md:text-base font-medium">GT-NETが収集した最新の業界動向と不正情報</p>
        </div>

        <!-- Filter Tabs - サブタイトルの横に配置 -->
        <div class="flex gap-1 overflow-x-auto pb-2 -mb-2" id="gt-news-filter-tabs">
          <?php
          $filter_categories = [
            ['slug' => 'all', 'label' => 'すべて'],
            ['slug' => 'gt-news', 'label' => 'GTニュース'],
            ['slug' => 'industry', 'label' => '業界'],
            ['slug' => 'victim', 'label' => '被害速報'],
            ['slug' => 'gt-mail', 'label' => 'GTメール'],
            ['slug' => 'topics', 'label' => 'トピックス'],
            ['slug' => 'column', 'label' => 'コラム']
          ];
          foreach ($filter_categories as $filter_cat):
          ?>
            <button
              data-filter="<?php echo esc_attr($filter_cat['slug']); ?>"
              class="gt-news-filter-btn px-4 py-2 rounded-lg text-xs font-bold whitespace-nowrap transition-all <?php echo $filter_cat['slug'] === 'all' ? 'bg-blue-600 text-white shadow-lg' : 'bg-white/10 text-slate-200 hover:bg-white/20 border border-white/20'; ?>"
            >
              <?php echo esc_html($filter_cat['label']); ?>
            </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: Sidebar -->
      <div class="lg:w-1/3 space-y-6">
        <!-- Video Library Card -->
        <div class="bg-blue-600/90 backdrop-blur-sm rounded-3xl p-8 text-white relative overflow-hidden group shadow-xl border border-white/20">
          <svg class="absolute right-2 bottom-2 w-40 h-40 text-white/5 group-hover:scale-110 transition-transform duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="relative z-10">
            <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black mb-4 inline-block">会員限定コンテンツ</span>
            <h5 class="text-2xl font-black mb-4">動画ライブラリ</h5>
            <div class="flex flex-wrap gap-2 mb-6">
              <span class="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">GTムービー</span>
              <span class="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">犯行動画</span>
              <span class="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">検証動画</span>
            </div>
            <a href="<?php echo esc_url(home_url('/movies/')); ?>" class="w-full bg-slate-900 py-4 rounded-xl font-bold hover:bg-black transition-all flex items-center justify-center gap-2 shadow-xl">
              動画ライブラリへ
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Templates Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-lg">
          <h5 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            書式・テンプレート
          </h5>
          <div class="space-y-2">
            <?php
            $template_categories = [
              'チェックシート',
              'ハウスルール',
              'フォーマット',
              '基礎・理論',
              '自主対策',
              '検査マニュアル'
            ];
            foreach ($template_categories as $cat):
            ?>
              <a
                href="<?php echo esc_url(home_url('/templates/?category=' . urlencode($cat))); ?>"
                class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100"
              >
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors"><?php echo esc_html($cat); ?></span>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Database Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-lg">
          <h5 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            データベース
          </h5>
          <div class="space-y-2">
            <a href="<?php echo esc_url(home_url('/prowler/')); ?>" class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">不審者情報</span>
              </div>
              <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
            <a href="<?php echo esc_url(home_url('/suspicious-vehicle/')); ?>" class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">不審車両情報</span>
              </div>
              <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
            <a href="http://gtnet.mobaqr.jp/index.php" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">遊技機DB</span>
              </div>
              <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Contact Widget -->
        <div class="bg-slate-900/90 backdrop-blur-sm rounded-3xl p-8 text-white border border-white/20 shadow-xl">
          <h5 class="text-xl font-black mb-4">緊急のご相談</h5>
          <div class="space-y-4">
            <div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/10">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <div>
                <p class="text-[10px] text-slate-400 font-bold tracking-widest">フリーダイヤル</p>
                <p class="text-xl font-black">0120-189-510</p>
              </div>
            </div>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="w-full bg-blue-600 py-4 rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/40 flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              メールでお問い合わせ
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- News List -->
    <div class="space-y-4" id="gt-news-list">
      <?php if (!empty($display_posts)): ?>
        <?php foreach ($display_posts as $post_item): 
          setup_postdata($post_item);
          $categories = get_the_category($post_item->ID);
          $category_slug = !empty($categories) ? $categories[0]->slug : '';
          $category_name = !empty($categories) ? $categories[0]->name : '';
          
          // 重要タグチェック
          $tags = get_the_tags($post_item->ID);
          $is_important = false;
          if ($tags) {
            foreach ($tags as $tag) {
              if (strpos(strtolower($tag->name), 'important') !== false || strpos(strtolower($tag->name), '重要') !== false) {
                $is_important = true;
                break;
              }
            }
          }
          
          // メンバー限定チェック
          $is_member_only = get_post_meta($post_item->ID, 'member_only', true) === 'yes';
          if (!$is_member_only && $tags) {
            foreach ($tags as $tag) {
              if (strpos(strtolower($tag->name), 'member') !== false || strpos(strtolower($tag->name), 'メンバー') !== false) {
                $is_member_only = true;
                break;
              }
            }
          }
          
          $post_date = get_the_date('Y-m-d', $post_item->ID);
          $post_date_parts = explode('-', $post_date);
        ?>
          <a
            href="<?php echo esc_url(get_permalink($post_item->ID)); ?>"
            class="gt-news-item group relative bg-white/95 backdrop-blur-sm p-5 rounded-xl border transition-all hover:shadow-lg hover:bg-white cursor-pointer flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 <?php echo $is_important ? 'border-l-4 border-l-red-500 border-white/30' : 'border-white/30'; ?>"
            data-category="<?php echo esc_attr($category_slug); ?>"
          >
            <div class="flex-shrink-0 flex flex-col items-start sm:items-center w-24">
              <span class="text-xs font-black text-slate-500"><?php echo esc_html($post_date_parts[0]); ?></span>
              <span class="text-lg font-black text-slate-900"><?php echo esc_html($post_date_parts[1] . '.' . $post_date_parts[2]); ?></span>
            </div>

            <div class="flex-grow">
              <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="px-3 py-0.5 text-[10px] font-black border rounded-full bg-slate-100 text-slate-700 border-slate-200">
                  <?php echo esc_html($category_name ?: 'お知らせ'); ?>
                </span>
                <?php if ($is_important): ?>
                  <span class="flex items-center gap-1 px-2 py-0.5 text-[10px] font-black text-white bg-red-500 rounded-full shadow-md">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    重要
                  </span>
                <?php endif; ?>
              </div>
              <h4 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug flex items-center gap-2">
                <?php echo esc_html(get_the_title($post_item->ID)); ?>
                <?php if ($is_member_only): ?>
                  <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                <?php endif; ?>
              </h4>
            </div>

            <div class="flex-shrink-0 flex items-center justify-end">
              <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-5 h-5 text-slate-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
        <?php wp_reset_postdata(); ?>
        
        <a href="<?php echo esc_url(home_url('/archives/')); ?>" class="w-full py-5 text-white font-black text-sm border-2 border-white/30 rounded-2xl bg-white/10 backdrop-blur-sm hover:bg-blue-600 hover:border-blue-600 transition-all flex items-center justify-center gap-2 mt-4 shadow-lg">
          過去の情報をすべて見る
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </a>
      <?php else: ?>
        <div class="text-center py-20 bg-white/95 backdrop-blur-sm rounded-3xl border border-dashed border-white/30 text-slate-300">
          該当する情報はありません
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
(function() {
  // フィルター機能
  const filterBtns = document.querySelectorAll('.gt-news-filter-btn');
  const newsItems = document.querySelectorAll('.gt-news-item');
  
  filterBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const filter = this.getAttribute('data-filter');
      
      // アクティブ状態の更新
      filterBtns.forEach(b => {
        b.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
        b.classList.add('bg-white/10', 'text-slate-200', 'border', 'border-white/20');
      });
      this.classList.remove('bg-white/10', 'text-slate-200', 'border', 'border-white/20');
      this.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
      
      // フィルタリング
      newsItems.forEach(item => {
        if (filter === 'all' || item.getAttribute('data-category') === filter) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
})();
</script>
