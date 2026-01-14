<?php
/**
 * Template Name: GT-NET Home Page
 * Description: GT-NETモックプレビューのデザインを適用したホームページテンプレート
 */

get_header();
?>

<main id="main-content" class="gtnet-home">
  <?php
  // カルーセルセクション
  get_template_part('template-parts/home/carousel');
  
  // 統計カードセクション（カルーセルに被せる）
  get_template_part('template-parts/home/stats');
  
  // はじめての方へバナー
  get_template_part('template-parts/home/firsttime-banner');
  
  // お知らせ・キャンペーンセクション
  get_template_part('template-parts/home/news');
  
  // 事業紹介セクション
  get_template_part('template-parts/home/services');
  
  // 最新情報セクション
  get_template_part('template-parts/home/latest-info');
  ?>
</main>

<?php
get_footer();
?>
