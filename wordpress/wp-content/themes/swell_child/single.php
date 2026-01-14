<?php
/**
 * 単一投稿テンプレート
 */
get_header();
?>

<main id="main-content" class="l-mainContent l-article">
  <div class="l-mainContent__inner">
    <?php if (have_posts()): ?>
      <?php while (have_posts()): the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header mb-8">
            <h1 class="entry-title text-4xl md:text-5xl font-bold text-slate-900 mb-4">
              <?php the_title(); ?>
            </h1>
            
            <div class="entry-meta flex items-center gap-4 text-sm text-slate-600 mb-6">
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date('Y年m月d日')); ?>
              </time>
              
              <?php
              $categories = get_the_category();
              if (!empty($categories)): ?>
                <span class="flex items-center gap-2">
                  <?php foreach ($categories as $category): ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full hover:bg-blue-100 transition-colors">
                      <?php echo esc_html($category->name); ?>
                    </a>
                  <?php endforeach; ?>
                </span>
              <?php endif; ?>
            </div>
          </header>

          <div class="entry-content prose prose-lg max-w-none">
            <?php the_content(); ?>
          </div>

          <footer class="entry-footer mt-12 pt-8 border-t border-slate-200">
            <?php
            $tags = get_the_tags();
            if ($tags): ?>
              <div class="post-tags mb-6">
                <span class="text-sm font-medium text-slate-700 mr-2">タグ:</span>
                <?php foreach ($tags as $tag): ?>
                  <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="inline-block px-3 py-1 mr-2 mb-2 text-xs text-slate-600 bg-slate-100 rounded-full hover:bg-slate-200 transition-colors">
                    <?php echo esc_html($tag->name); ?>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </footer>
        </article>

        <?php
        // 前後の投稿ナビゲーション
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        if ($prev_post || $next_post): ?>
          <nav class="post-navigation mt-12 pt-8 border-t border-slate-200">
            <div class="flex justify-between items-center gap-4">
              <?php if ($prev_post): ?>
                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                  <span class="text-sm font-medium">前の記事</span>
                </a>
              <?php else: ?>
                <span></span>
              <?php endif; ?>
              
              <?php if ($next_post): ?>
                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors">
                  <span class="text-sm font-medium">次の記事</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              <?php else: ?>
                <span></span>
              <?php endif; ?>
            </div>
          </nav>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-slate-600">記事が見つかりませんでした。</p>
    <?php endif; ?>
  </div>
</main>

<?php
get_footer();
?>
