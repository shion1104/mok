<?php
/**
 * 事業紹介セクション
 */
$services = [
  [
    'title' => '検査事業',
    'desc' => '不正改造の有無を徹底調査。',
    'href' => home_url('/services/inspection/')
  ],
  [
    'title' => '監査事業',
    'desc' => '適正なホール運営を第三者評価。',
    'href' => home_url('/services/audit/')
  ],
  [
    'title' => '巡回事業',
    'desc' => '現場の脆弱性をプロが診断。',
    'href' => home_url('/services/patrol/')
  ],
  [
    'title' => '教育事業',
    'desc' => '防犯意識を組織の文化へ。',
    'href' => home_url('/services/education/')
  ],
  [
    'title' => '情報提供事業',
    'desc' => '業界動向と不正情報を配信。',
    'href' => home_url('/services/information/')
  ]
];
?>
<section class="py-24 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-t border-b border-slate-200 relative overflow-hidden">
  <div class="absolute inset-0 opacity-30 pointer-events-none">
    <div class="absolute top-0 left-0 w-full h-full" style="background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px), linear-gradient(to bottom, #e2e8f0 1px, transparent 1px); background-size: 40px 40px;"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 relative z-10">
    <div class="mb-16">
      <h3 class="text-4xl md:text-5xl font-bold mb-4 text-slate-900 tracking-tight">事業紹介</h3>
      <p class="text-slate-600 max-w-2xl font-medium text-base">
        30年以上の実績に基づく、パチンコ業界特化型のリスクマネジメント。
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
      <?php foreach ($services as $service): ?>
        <a href="<?php echo esc_url($service['href']); ?>" class="group p-6 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-slate-300 hover:shadow-md transition-all duration-200 cursor-pointer flex flex-col">
          <div class="w-12 h-12 rounded-lg bg-slate-900 text-white flex items-center justify-center mb-4 group-hover:bg-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <h4 class="text-lg font-bold mb-3 text-slate-900"><?php echo esc_html($service['title']); ?></h4>
          <p class="text-slate-600 text-sm leading-relaxed mb-4 flex-grow">
            <?php echo esc_html($service['desc']); ?>ホール経営のあらゆるリスクに対応します。
          </p>
          <div class="flex items-center gap-2 text-xs font-bold text-blue-600 mt-auto">
            詳しく見る
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
