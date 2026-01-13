<?php
/**
 * TOPページ用サイドバー
 * 動画ライブラリ、書式・テンプレート、データベース、緊急連絡先
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- 動画ライブラリカード -->
<div class="top-sidebar-card top-sidebar-card--video">
	<div class="relative z-10">
		<span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black mb-4 inline-block">会員限定コンテンツ</span>
		<h5 class="text-2xl font-black mb-4">動画ライブラリ</h5>
		<div class="flex flex-wrap gap-2 mb-6">
			<span class="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">GTムービー</span>
			<span class="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">犯行動画</span>
			<span class="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">検証動画</span>
		</div>
		<a href="/movies/" class="w-full bg-slate-900 py-4 rounded-xl font-bold hover:bg-black transition-all flex items-center justify-center gap-2 shadow-xl">
			動画ライブラリへ
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
				<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2"/>
				<path d="M7 6L13 10L7 14V6Z" fill="currentColor"/>
			</svg>
		</a>
	</div>
	<!-- Background Icon -->
	<svg class="absolute -right-4 -bottom-4 w-40 h-40 text-white/10 group-hover:scale-110 transition-transform duration-700" width="160" height="160" viewBox="0 0 160 160" fill="none">
		<circle cx="80" cy="80" r="76" stroke="currentColor" stroke-width="2"/>
		<path d="M60 50L110 80L60 110V50Z" fill="currentColor"/>
	</svg>
</div>

<!-- 書式・テンプレートカード -->
<div class="top-sidebar-card top-sidebar-card--template">
	<h5 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
		<svg width="24" height="24" viewBox="0 0 20 20" fill="none">
			<path d="M4 2H16V18H4V2Z M6 6H14V8H6V6Z M6 10H14V12H6V10Z M6 14H10V16H6V14Z" stroke="currentColor" stroke-width="2"/>
		</svg>
		書式・テンプレート
	</h5>
	<div class="space-y-2">
		<?php
		$template_categories = array(
			'チェックシート',
			'ハウスルール',
			'フォーマット',
			'基礎・理論',
			'自主対策',
			'検査マニュアル'
		);
		
		foreach ($template_categories as $cat) :
		?>
			<a href="/templates/?category=<?php echo urlencode($cat); ?>" class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
				<div class="flex items-center gap-3">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-slate-400 group-hover:text-blue-500 transition-colors">
						<path d="M4 2H16V18H4V2Z M6 6H14V8H6V6Z M6 10H14V12H6V10Z M6 14H10V16H6V14Z" stroke="currentColor" stroke-width="2"/>
					</svg>
					<span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors"><?php echo esc_html($cat); ?></span>
				</div>
				<svg width="16" height="16" viewBox="0 0 20 20" fill="none" class="text-slate-300 group-hover:text-blue-500 transition-colors">
					<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
			</a>
		<?php endforeach; ?>
	</div>
</div>

<!-- データベースカード -->
<div class="top-sidebar-card top-sidebar-card--database">
	<h5 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
		<svg width="24" height="24" viewBox="0 0 20 20" fill="none">
			<ellipse cx="10" cy="5" rx="7" ry="3" stroke="currentColor" stroke-width="2"/>
			<path d="M3 5V15C3 16.6569 6.13401 18 10 18C13.866 18 17 16.6569 17 15V5" stroke="currentColor" stroke-width="2"/>
			<ellipse cx="10" cy="10" rx="7" ry="3" stroke="currentColor" stroke-width="2" fill="none"/>
			<ellipse cx="10" cy="15" rx="7" ry="3" stroke="currentColor" stroke-width="2" fill="none"/>
		</svg>
		データベース
	</h5>
	<div class="space-y-2">
		<a href="/prowler/" class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
			<div class="flex items-center gap-3">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-slate-400 group-hover:text-blue-500 transition-colors">
					<circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
					<path d="M5 16C5 13.7909 7.23858 12 10 12C12.7614 12 15 13.7909 15 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
				<span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">不審者情報</span>
			</div>
			<svg width="16" height="16" viewBox="0 0 20 20" fill="none" class="text-slate-300 group-hover:text-blue-500 transition-colors">
				<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
			</svg>
		</a>
		<a href="/suspicious-vehicle/" class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
			<div class="flex items-center gap-3">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-slate-400 group-hover:text-blue-500 transition-colors">
					<rect x="2" y="8" width="16" height="8" rx="1" stroke="currentColor" stroke-width="2"/>
					<path d="M4 8V5C4 3.89543 4.89543 3 6 3H14C15.1046 3 16 3.89543 16 5V8" stroke="currentColor" stroke-width="2"/>
					<circle cx="6" cy="13" r="1" fill="currentColor"/>
					<circle cx="14" cy="13" r="1" fill="currentColor"/>
				</svg>
				<span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">不審車両情報</span>
			</div>
			<svg width="16" height="16" viewBox="0 0 20 20" fill="none" class="text-slate-300 group-hover:text-blue-500 transition-colors">
				<path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
			</svg>
		</a>
		<a href="http://gtnet.mobaqr.jp/index.php" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
			<div class="flex items-center gap-3">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-slate-400 group-hover:text-blue-500 transition-colors">
					<ellipse cx="10" cy="5" rx="7" ry="3" stroke="currentColor" stroke-width="2"/>
					<path d="M3 5V15C3 16.6569 6.13401 18 10 18C13.866 18 17 16.6569 17 15V5" stroke="currentColor" stroke-width="2"/>
				</svg>
				<span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">遊技機DB</span>
			</div>
			<svg width="16" height="16" viewBox="0 0 20 20" fill="none" class="text-slate-300 group-hover:text-blue-500 transition-colors">
				<path d="M10 3L13 7H17L14 10L15 14L10 12L5 14L6 10L3 7H7L10 3Z" stroke="currentColor" stroke-width="2"/>
			</svg>
		</a>
	</div>
</div>

<!-- 緊急連絡先ウィジェット -->
<div class="top-sidebar-card top-sidebar-card--contact">
	<h5 class="text-xl font-black mb-4">緊急のご相談</h5>
	<div class="space-y-4">
		<div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/10">
			<svg width="24" height="24" viewBox="0 0 20 20" fill="none" class="text-blue-400">
				<path d="M2 4H6L8 11L5.5 13.5C6.57096 15.6715 8.32854 17.429 10.5 18.5L13 16L20 18V22C20 22.5304 19.7893 23.0391 19.4142 23.4142C19.0391 23.7893 18.5304 24 18 24C7.61116 24 0 16.3888 0 6C0 5.46957 0.210714 4.96086 0.585786 4.58579C0.960859 4.21071 1.46957 4 2 4Z" stroke="currentColor" stroke-width="2"/>
			</svg>
			<div>
				<p class="text-[10px] text-slate-400 font-bold tracking-widest">フリーダイヤル</p>
				<p class="text-xl font-black">0120-189-510</p>
			</div>
		</div>
		<a href="/contact/" class="w-full bg-blue-600 py-4 rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/40 flex items-center justify-center gap-2">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
				<path d="M2.5 4.5L10 9.5L17.5 4.5M2.5 15.5H17.5V4.5H2.5V15.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
			</svg>
			メールでお問い合わせ
		</a>
	</div>
</div>
