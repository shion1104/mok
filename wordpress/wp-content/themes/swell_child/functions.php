<?php

/* 子テーマのfunctions.phpは、親テーマのfunctions.phpより先に読み込まれることに注意してください。 */


/**
 * 親テーマのfunctions.phpのあとで読み込みたいコードはこの中に。
 */
// add_filter('after_setup_theme', function(){
// }, 11);


/**
 * 子テーマでのファイルの読み込み
 */
add_action('wp_enqueue_scripts', function() {
	
	$timestamp = date( 'Ymdgis', filemtime( get_stylesheet_directory() . '/style.css' ) );
	wp_enqueue_style( 'child_style', get_stylesheet_directory_uri() .'/style.css', [], $timestamp );

	/* その他の読み込みファイルはこの下に記述 */

}, 11);
<?php
// [gt_news_tabs] 最新動向（マーケットニュース/サイト更新情報）タブ + NEW表示
add_shortcode('gt_news_tabs', function ($atts) {
  $atts = shortcode_atts([
    'market_slug' => 'market-news',
    'update_slug' => 'site-updates',
    'posts'       => 5,
    'new_days'    => 7,
  ], $atts, 'gt_news_tabs');

  $posts_per_tab = max(1, (int)$atts['posts']);
  $new_days      = max(1, (int)$atts['new_days']);
  $new_border_ts = time() - ($new_days * DAY_IN_SECONDS);

  // 1タブ分を生成する関数
  $render_list = function ($cat_slug, $more_url) use ($posts_per_tab, $new_border_ts) {
    $q = new WP_Query([
      'post_type'           => 'post',
      'posts_per_page'      => $posts_per_tab,
      'post_status'         => 'publish',
      'ignore_sticky_posts' => true,
      'tax_query'           => [[
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => $cat_slug,
      ]],
      'orderby' => 'modified',
      'order'   => 'DESC',
    ]);

    ob_start();

    echo '<ul class="gt-newsList">';
    if ($q->have_posts()) {
      while ($q->have_posts()) {
        $q->the_post();

        $title = get_the_title();
        $url   = get_permalink();

        // 更新日で判定（公開日じゃなく更新日）
        $modified_ts = (int) get_post_modified_time('U', true);
        $is_new      = $modified_ts >= $new_border_ts;

        $dt_attr = esc_attr(get_post_modified_time('c', true));
        $dt_text = esc_html(get_post_modified_time('Y.m.d', true));
        ?>
        <li class="gt-newsItem">
          <a class="gt-newsLink" href="<?php echo esc_url($url); ?>">
            <span class="gt-newsDate">
              <time datetime="<?php echo $dt_attr; ?>"><?php echo $dt_text; ?></time>
              <?php if ($is_new): ?>
                <span class="gt-newBadge">NEW</span>
              <?php endif; ?>
            </span>
            <span class="gt-newsText"><?php echo esc_html($title); ?></span>
          </a>
        </li>
        <?php
      }
      wp_reset_postdata();
    } else {
      echo '<li class="gt-newsItem gt-newsEmpty">記事がありません</li>';
    }
    echo '</ul>';

    echo '<div class="gt-newsMore">';
    echo '<a class="gt-newsMoreLink" href="'.esc_url($more_url).'">一覧を見る →</a>';
    echo '</div>';

    return ob_get_clean();
  };

  // カテゴリページURL（カテゴリが存在すればそこに飛ばす）
  $market_term = get_category_by_slug($atts['market_slug']);
  $update_term = get_category_by_slug($atts['update_slug']);
  $market_more = $market_term ? get_category_link($market_term->term_id) : '/category/' . $atts['market_slug'];
  $update_more = $update_term ? get_category_link($update_term->term_id) : '/category/' . $atts['update_slug'];

  ob_start();
  ?>
  <section class="gt-newsTabs">
    <div class="gt-newsTabs__inner">

      <div class="gt-newsTabs__head">
        <h2 class="gt-newsTabs__title">業界の最新動向</h2>

        <div class="gt-newsTabs__tabs" role="tablist" aria-label="最新動向タブ">
          <button class="gt-tab is-active" type="button" role="tab" aria-selected="true" data-gt-tab="market">
            マーケットニュース
          </button>
          <button class="gt-tab" type="button" role="tab" aria-selected="false" data-gt-tab="update">
            サイト更新情報
          </button>
        </div>
      </div>

      <div class="gt-panel is-active" role="tabpanel" data-gt-panel="market">
        <?php echo $render_list($atts['market_slug'], $market_more); ?>
      </div>

      <div class="gt-panel" role="tabpanel" data-gt-panel="update">
        <?php echo $render_list($atts['update_slug'], $update_more); ?>
      </div>

    </div>
  </section>

  <script>
  (function(){
    const wrap = document.currentScript?.previousElementSibling;
    // もし配置関係で取れなければ全体検索
    const root = wrap && wrap.classList?.contains('gt-newsTabs') ? wrap : document.querySelector('.gt-newsTabs');
    if(!root) return;

    const tabs = root.querySelectorAll('.gt-tab');
    const panels = root.querySelectorAll('.gt-panel');

    tabs.forEach(btn => {
      btn.addEventListener('click', () => {
        const key = btn.getAttribute('data-gt-tab');

        tabs.forEach(t => {
          const active = (t === btn);
          t.classList.toggle('is-active', active);
          t.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        panels.forEach(p => {
          p.classList.toggle('is-active', p.getAttribute('data-gt-panel') === key);
        });
      });
    });
  })();
  </script>
  <?php
  return ob_get_clean();
});add_filter('widget_text', 'do_shortcode');
add_filter('widget_text_content', 'do_shortcode');


