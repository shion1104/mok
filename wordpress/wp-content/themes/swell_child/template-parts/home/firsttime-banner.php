<?php
/**
 * はじめての方へバナー
 */
$firsttime_url = get_permalink(get_page_by_path('firsttime'));
if (!$firsttime_url) {
  $firsttime_url = home_url('/firsttime/');
}
?>
<div class="container mx-auto px-4 md:px-6 mt-12 mb-8 gtnet-firsttime-container">
  <a href="<?php echo esc_url($firsttime_url); ?>" class="group flex flex-col md:flex-row items-center bg-blue-600 rounded-xl px-8 md:px-10 py-6 md:py-8 border border-blue-700 hover:bg-blue-700 hover:shadow-lg transition-all duration-200 gtnet-firsttime-banner">
    <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-white/20 rounded-lg flex items-center justify-center">
      <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    </div>
    <div class="flex-1 flex flex-col items-center justify-center mt-4 md:mt-0 md:px-8">
      <h3 class="text-xl md:text-2xl font-bold text-white leading-tight mb-2 text-center">
        はじめての方へ
      </h3>
      <p class="text-sm md:text-base text-blue-50 font-medium leading-relaxed text-center">
        GT-NETは不正事例から実務マニュアルまで網羅した会員制専門サイトです。<span class="hidden md:inline"> 新規会員登録で、すぐにご利用いただけます。</span>
      </p>
    </div>
    <div class="flex items-center gap-2 bg-white px-5 py-3 rounded-lg group-hover:bg-blue-50 transition-colors flex-shrink-0 mt-4 md:mt-0" style="display: flex; align-items: center; gap: 0.5rem; background-color: #ffffff; padding: 0.75rem 1.25rem; border-radius: 0.5rem;">
      <span class="text-base font-bold text-blue-600 group-hover:text-blue-700" style="font-size: 1rem; font-weight: 700; color: #2563eb;">詳しく見る</span>
      <svg class="w-5 h-5 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.25rem; height: 1.25rem; color: #2563eb;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </div>
  </a>
</div>
