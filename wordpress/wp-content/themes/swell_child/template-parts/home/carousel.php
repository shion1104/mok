<?php
/**
 * カルーセルセクション
 */
global $post;

// 管理画面で設定されたカルーセルデータを取得
$slides = get_post_meta($post->ID, '_gtnet_carousel_slides', true);

// デフォルト値（設定がない場合）
if (empty($slides)) {
  $slides = [
    [
      'title' => 'パチンコホールの<br>不正を未然に防ぐ',
      'subtitle' => 'リスクマネジメント',
      'desc' => 'ゴト対策からセキュリティ監査まで、現場主義のコンサルティングでホール経営を守ります。',
      'bg_class' => 'bg-slate-900'
    ],
    [
      'title' => 'データが語る<br>真実の防犯対策',
      'subtitle' => 'データ分析',
      'desc' => '全国の被害発生状況をリアルタイムに収集。統計に基づいた的確な対策を提案します。',
      'bg_class' => 'bg-blue-900'
    ],
    [
      'title' => '教育こそが<br>最大の防御策',
      'subtitle' => 'スタッフ教育',
      'desc' => 'スタッフの意識を変え、有事に強い組織を作る。独自のカリキュラムで防犯スキルを向上。',
      'bg_class' => 'bg-slate-800'
    ]
  ];
}
?>
<section class="gtnet-carousel relative h-[600px] md:h-[700px] flex items-center overflow-hidden bg-slate-900 w-full">
  <?php foreach ($slides as $idx => $slide): ?>
    <div class="gtnet-carousel-slide absolute inset-0 transition-opacity duration-700 ease-in-out <?php echo $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'; ?> <?php echo esc_attr($slide['bg_class']); ?>">
      <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/20 to-black/60"></div>
      <div class="container mx-auto px-4 md:px-6 h-full flex flex-col justify-center relative z-20">
        <div class="max-w-4xl">
          <p class="text-blue-400 font-bold tracking-wider mb-4 text-xs md:text-sm uppercase"><?php echo esc_html($slide['subtitle']); ?></p>
          <h2 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6">
            <?php
            // タイトルを<br>で分割して、2行目を強調
            $title_parts = explode('<br>', $slide['title']);
            if (count($title_parts) > 1) {
              echo esc_html($title_parts[0]) . '<br /><span class="text-blue-400">' . esc_html($title_parts[1]) . '</span>';
            } else {
              echo wp_kses_post($slide['title']);
            }
            ?>
          </h2>
          <p class="text-white text-base md:text-xl max-w-2xl leading-relaxed font-medium">
            <?php echo esc_html($slide['desc']); ?>
          </p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- Carousel Controls -->
  <div class="absolute bottom-12 md:bottom-16 left-0 w-full z-30">
    <div class="container mx-auto px-4 md:px-6">
      <div class="flex justify-between items-center">
        <div class="flex gap-4">
          <button class="gtnet-carousel-prev w-12 h-12 rounded-full border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-all backdrop-blur-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button class="gtnet-carousel-next w-12 h-12 rounded-full border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-all backdrop-blur-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
        <div class="flex gap-3 gtnet-carousel-indicators">
          <?php foreach ($slides as $i => $slide): ?>
            <button class="gtnet-carousel-indicator h-1.5 rounded-full transition-all <?php echo $i === 0 ? 'w-12 bg-white' : 'w-4 bg-white/30'; ?>" data-slide="<?php echo $i; ?>"></button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
