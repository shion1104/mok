<?php
/**
 * トップページ専用フッター
 * Next.jsのpage.tsxから変換
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<footer class="bg-slate-950 pt-24 pb-12 text-slate-500">
	<div class="container mx-auto px-4">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-20 mb-20">
			<div>
				<div class="mb-8">
					<?php
					// 参照先のNext.jsファイルに合わせてロゴパスを設定
					$logo_url = home_url('/wp-content/uploads/gtnet/logo.png');
					if (!file_exists(ABSPATH . 'wp-content/uploads/gtnet/logo.png')) {
						// フォールバック: カスタムロゴまたはデフォルト
						if ( has_custom_logo() ) {
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
						} else {
							$logo_url = get_stylesheet_directory_uri() . '/screenshot.png';
						}
					}
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img
							src="<?php echo esc_url( $logo_url ); ?>"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							class="h-12 w-auto brightness-0 invert"
						/>
					</a>
				</div>
				<p class="max-w-md text-sm leading-relaxed mb-8">
					株式会社ジーティネットは、パチンコ・スロット業界の健全な発展を願い、高度なセキュリティ技術と専門知識をもって不正に立ち向かうリスクマネジメント企業です。
				</p>
				<div class="flex gap-4">
					<a href="/contact/" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M3 4L10 9L17 4M3 4H17V16H3V4Z" stroke="currentColor" stroke-width="2"/>
						</svg>
					</a>
					<a href="tel:0120-189-510" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
							<path d="M2 3C2 2.44772 2.44772 2 3 2H5.15287C5.64171 2 6.0589 2.35341 6.13927 2.8356L6.87858 7.27147C6.95075 7.70451 6.73206 8.13397 6.3394 8.3303L4.79126 9.10437C5.90756 11.8783 8.12168 14.0924 10.8956 15.2087L11.6697 13.6606C11.866 13.2679 12.2955 13.0492 12.7285 13.1214L17.1644 13.8607C17.6466 13.9411 18 14.3583 18 14.8471V17C18 17.5523 17.5523 18 17 18H15C7.8203 18 2 12.1797 2 5V3Z" stroke="currentColor" stroke-width="2"/>
						</svg>
					</a>
				</div>
			</div>

			<div class="grid grid-cols-2 md:grid-cols-3 gap-8">
				<div>
					<h6 class="text-white font-black text-xs tracking-widest mb-6">コンテンツ</h6>
					<ul class="space-y-4 text-xs font-bold">
						<li><a href="/archives/?category=gt-news" class="hover:text-white transition-colors">GTニュース</a></li>
						<li><a href="/archives/?category=gt-mail" class="hover:text-white transition-colors">GTメール</a></li>
						<li><a href="/archives/?category=industry" class="hover:text-white transition-colors">業界ニュース</a></li>
						<li><a href="/archives/?category=victim" class="hover:text-white transition-colors">被害発生状況</a></li>
						<li><a href="/archives/?category=topics" class="hover:text-white transition-colors">トピックス</a></li>
						<li><a href="/archives/?category=column" class="hover:text-white transition-colors">コラム</a></li>
						<li><a href="/templates/" class="hover:text-white transition-colors">資料・書式</a></li>
					</ul>
				</div>
				<div>
					<h6 class="text-white font-black text-xs tracking-widest mb-6">事業案内</h6>
					<ul class="space-y-4 text-xs font-bold">
						<li><a href="/services/" class="hover:text-white transition-colors">検査・監査事業</a></li>
						<li><a href="/services/patrol/" class="hover:text-white transition-colors">巡回・防犯指導</a></li>
						<li><a href="/services/education/" class="hover:text-white transition-colors">教育・セミナー</a></li>
						<li><a href="/services/information/" class="hover:text-white transition-colors">情報提供サービス</a></li>
						<li><a href="/services/price/" class="hover:text-white transition-colors">料金表</a></li>
						<li><a href="/products/" class="hover:text-white transition-colors">GT商品</a></li>
					</ul>
				</div>
				<div class="col-span-2 md:col-span-1">
					<h6 class="text-white font-black text-xs tracking-widest mb-6">会社情報</h6>
					<ul class="space-y-4 text-xs font-bold">
						<li><a href="/firsttime/" class="hover:text-white transition-colors">はじめての方へ</a></li>
						<li><a href="/company/" class="hover:text-white transition-colors">会社概要</a></li>
						<li><a href="/company/philosophy/" class="hover:text-white transition-colors">経営理念</a></li>
						<li><a href="/company/message/" class="hover:text-white transition-colors">代表挨拶</a></li>
						<li><a href="/privacy/" class="hover:text-white transition-colors">プライバシー方針</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
			<p class="text-[10px] font-black tracking-widest">
				© 2010 - <?php echo date( 'Y' ); ?> 株式会社ジーティネット All Rights Reserved.
			</p>
		</div>
	</div>
</footer>

<style>
/* トップページ下部の余白調整 */
.top-page .top-business-section {
	margin-bottom: 0;
}

.top-page footer {
	margin-top: 0;
}
</style>
