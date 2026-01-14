<?php
/**
 * GT-NET カスタムフッターコンテンツ
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<footer class="bg-slate-950 pt-24 pb-12 text-slate-500 gtnet-footer">
  <div class="container mx-auto px-4 md:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 mb-20">
      <div>
        <div class="mb-8">
          <?php
          // ロゴのパスを確認（WordPressカスタマイザー → テーマ内logo.png の優先順）
          $logo_url = '';
          
          // まずWordPressのカスタマイザーからロゴを取得
          $custom_logo_id = get_theme_mod('custom_logo');
          if ($custom_logo_id) {
            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
          }
          
          // カスタマイザーにロゴがない場合、子テーマ直下の logo.png を使用
          if (!$logo_url) {
            $theme_logo_path = get_stylesheet_directory() . '/logo.png';
            if (file_exists($theme_logo_path)) {
              $logo_url = get_stylesheet_directory_uri() . '/logo.png';
            }
          }
          
          if ($logo_url) {
            echo '<img src="' . esc_url($logo_url) . '" alt="株式会社ジーティネット" class="h-12 w-auto brightness-0 invert" />';
          } else {
            // ロゴが見つからない場合はテキストロゴを表示
            echo '<h2 class="text-white text-2xl font-black">株式会社ジーティネット</h2>';
          }
          ?>
        </div>
        <p class="max-w-md text-sm leading-relaxed mb-8">
          株式会社ジーティネットは、パチンコ・スロット業界の健全な発展を願い、高度なセキュリティ技術と専門知識をもって不正に立ち向かうリスクマネジメント企業です。
        </p>
        <div class="flex gap-4">
          <a href="mailto:info@gtnet.co.jp" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </a>
          <a href="tel:03-1234-5678" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
          </a>
        </div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
        <div>
          <h6 class="text-white font-black text-xs tracking-[0.2em] mb-6">コンテンツ</h6>
          <ul class="space-y-4 text-xs font-bold">
            <li><a href="<?php echo esc_url(home_url('/archives/?category=gt-news')); ?>" class="hover:text-white transition-colors">GTニュース</a></li>
            <li><a href="<?php echo esc_url(home_url('/archives/?category=gt-mail')); ?>" class="hover:text-white transition-colors">GTメール</a></li>
            <li><a href="<?php echo esc_url(home_url('/archives/?category=industry')); ?>" class="hover:text-white transition-colors">業界ニュース</a></li>
            <li><a href="<?php echo esc_url(home_url('/archives/?category=victim')); ?>" class="hover:text-white transition-colors">被害発生状況</a></li>
            <li><a href="<?php echo esc_url(home_url('/archives/?category=topics')); ?>" class="hover:text-white transition-colors">トピックス</a></li>
            <li><a href="<?php echo esc_url(home_url('/archives/?category=column')); ?>" class="hover:text-white transition-colors">コラム</a></li>
            <li><a href="<?php echo esc_url(home_url('/templates/')); ?>" class="hover:text-white transition-colors">資料・書式</a></li>
          </ul>
        </div>
        <div>
          <h6 class="text-white font-black text-xs tracking-[0.2em] mb-6">事業案内</h6>
          <ul class="space-y-4 text-xs font-bold">
            <li><a href="<?php echo esc_url(home_url('/services/')); ?>" class="hover:text-white transition-colors">検査・監査事業</a></li>
            <li><a href="<?php echo esc_url(home_url('/services/patrol/')); ?>" class="hover:text-white transition-colors">巡回・防犯指導</a></li>
            <li><a href="<?php echo esc_url(home_url('/services/education/')); ?>" class="hover:text-white transition-colors">教育・セミナー</a></li>
            <li><a href="<?php echo esc_url(home_url('/services/information/')); ?>" class="hover:text-white transition-colors">情報提供サービス</a></li>
            <li><a href="<?php echo esc_url(home_url('/services/price/')); ?>" class="hover:text-white transition-colors">料金表</a></li>
            <li><a href="<?php echo esc_url(home_url('/products/')); ?>" class="hover:text-white transition-colors">GT商品</a></li>
          </ul>
        </div>
        <div class="col-span-2 md:col-span-1">
          <h6 class="text-white font-black text-xs tracking-[0.2em] mb-6">会社情報</h6>
          <ul class="space-y-4 text-xs font-bold">
            <li><a href="<?php echo esc_url(home_url('/firsttime/')); ?>" class="hover:text-white transition-colors">はじめての方へ</a></li>
            <li><a href="<?php echo esc_url(home_url('/company/')); ?>" class="hover:text-white transition-colors">会社概要</a></li>
            <li><a href="<?php echo esc_url(home_url('/company/philosophy/')); ?>" class="hover:text-white transition-colors">経営理念</a></li>
            <li><a href="<?php echo esc_url(home_url('/company/message/')); ?>" class="hover:text-white transition-colors">代表挨拶</a></li>
            <li><a href="<?php echo esc_url(home_url('/privacy/')); ?>" class="hover:text-white transition-colors">プライバシー方針</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
      <p class="text-[10px] font-black tracking-widest">
        © 2010 - <?php echo date('Y'); ?> 株式会社ジーティネット All Rights Reserved.
      </p>
    </div>
  </div>
</footer>
