<?php
/**
 * 統計カードセクション
 */
global $post;

// 管理画面で設定された統計データを取得
$stats_current = get_post_meta($post->ID, '_gtnet_stats_current', true) ?: '0';
$stats_annual = get_post_meta($post->ID, '_gtnet_stats_annual', true) ?: '65';
$stats_update_date = get_post_meta($post->ID, '_gtnet_stats_update_date', true) ?: '2026年01月05日';
?>
<div class="container mx-auto px-4 md:px-6 -mt-8 md:-mt-6 relative z-40 gtnet-stats-container">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 gtnet-stats-grid">
    <!-- 最新状況 -->
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-red-200 flex flex-col justify-center">
      <div class="flex items-center gap-2 text-red-600 mb-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span class="text-xs font-bold tracking-wide text-red-600">最新状況</span>
      </div>
      <p class="text-slate-600 text-sm font-medium mb-2">2026年 被害発生件数</p>
      <div class="flex items-baseline gap-2">
        <span class="text-4xl font-bold text-slate-900"><?php echo esc_html($stats_current); ?></span>
        <span class="text-slate-500 font-medium text-sm">件</span>
      </div>
    </div>

    <!-- 年間集計 -->
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-blue-200 flex flex-col justify-center">
      <div class="flex items-center gap-2 text-blue-600 mb-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span class="text-xs font-bold tracking-wide text-blue-600">年間集計</span>
      </div>
      <p class="text-slate-600 text-sm font-medium mb-2">2025年 被害発生件数</p>
      <div class="flex items-baseline gap-2">
        <span class="text-4xl font-bold text-slate-900"><?php echo esc_html($stats_annual); ?></span>
        <span class="text-slate-500 font-medium text-sm">件</span>
      </div>
    </div>

    <!-- 更新情報 -->
    <div class="bg-slate-900 p-6 md:p-8 rounded-xl shadow-lg border border-slate-800 text-white flex flex-col justify-center">
      <div class="flex items-center gap-2 text-blue-400 mb-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-xs font-bold tracking-wide">更新情報</span>
      </div>
      <p class="text-base font-medium leading-relaxed">
        最終更新：<?php echo esc_html($stats_update_date); ?><br>
        <span class="text-slate-400 text-sm">被害情報を随時更新中</span>
      </p>
    </div>
  </div>
</div>
