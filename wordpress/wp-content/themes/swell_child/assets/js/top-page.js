/**
 * トップページ専用JavaScript
 * Next.jsのpage.tsxから変換した新実装
 */

(function() {
	'use strict';

	// DOMContentLoaded後に実行
	document.addEventListener('DOMContentLoaded', function() {
		initHeaderScroll();
		initCarousel();
		initStatsOverlay();
		initNewsFilter();
		initCountUp();
		initSmoothScroll();
	});

	/**
	 * ヘッダーのスクロール効果（Next.jsのpage.tsxから変換）
	 */
	function initHeaderScroll() {
		const header = document.getElementById('header');
		if (!header) return;

		const handleScroll = function() {
			const scrolled = window.scrollY > 20;
			if (scrolled) {
				header.classList.add('header-scrolled');
			} else {
				header.classList.remove('header-scrolled');
			}
		};

		window.addEventListener('scroll', handleScroll);
		handleScroll(); // 初期状態を設定
	}

	/**
	 * カルーセルMV
	 */
	function initCarousel() {
		const carouselSection = document.querySelector('.top-carousel-mv');
		if (!carouselSection) return;

		const slides = carouselSection.querySelectorAll('.top-carousel-slide');
		if (slides.length === 0) return;

		let currentSlide = 0;
		const totalSlides = slides.length;

		// 前へボタン
		const prevBtn = document.getElementById('carousel-prev');
		if (prevBtn) {
			prevBtn.addEventListener('click', function() {
				currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
				updateCarousel();
			});
		}

		// 次へボタン
		const nextBtn = document.getElementById('carousel-next');
		if (nextBtn) {
			nextBtn.addEventListener('click', function() {
				currentSlide = (currentSlide + 1) % totalSlides;
				updateCarousel();
			});
		}

		// インジケーター
		const indicators = document.querySelectorAll('.top-carousel-indicator');
		indicators.forEach(function(indicator, index) {
			indicator.addEventListener('click', function() {
				currentSlide = index;
				updateCarousel();
			});
		});

		function updateCarousel() {
			// スライドを更新
			slides.forEach(function(slide, index) {
				if (index === currentSlide) {
					slide.classList.add('active');
					slide.style.opacity = '1';
					slide.style.zIndex = '10';
				} else {
					slide.classList.remove('active');
					slide.style.opacity = '0';
					slide.style.zIndex = '0';
				}
			});

			// インジケーターを更新
			indicators.forEach(function(indicator, index) {
				if (index === currentSlide) {
					indicator.classList.add('active');
				} else {
					indicator.classList.remove('active');
				}
			});
		}

		// 初期状態を設定（最初のスライドを表示）
		currentSlide = 0;
		updateCarousel();

		// 自動再生（5秒ごと）
		setInterval(function() {
			currentSlide = (currentSlide + 1) % totalSlides;
			updateCarousel();
		}, 5000);
	}

	/**
	 * 統計オーバーレイのカウントアップ
	 */
	function initStatsOverlay() {
		const statsCards = document.querySelectorAll('.top-stats-card [data-animate="count-up"]');
		if (statsCards.length === 0) return;

		const observerOptions = {
			root: null,
			rootMargin: '0px',
			threshold: 0.5
		};

		const observer = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
					entry.target.classList.add('counted');
					animateCountUp(entry.target);
					observer.unobserve(entry.target);
				}
			});
		}, observerOptions);

		statsCards.forEach(function(card) {
			observer.observe(card);
		});
	}

	/**
	 * カウントアップアニメーション
	 */
	function animateCountUp(element) {
		const target = parseFloat(element.dataset.target) || 0;
		const suffix = element.dataset.suffix || '';
		
		if (!element) return;

		const duration = 2000; // 2秒
		const startTime = performance.now();
		const startValue = 0;

		function updateCount(currentTime) {
			const elapsed = currentTime - startTime;
			const progress = Math.min(elapsed / duration, 1);
			
			// イージング関数（ease-out）
			const easeOut = 1 - Math.pow(1 - progress, 3);
			const currentValue = startValue + (target - startValue) * easeOut;
			
			element.textContent = Math.floor(currentValue) + (suffix ? ' ' + suffix : '');

			if (progress < 1) {
				requestAnimationFrame(updateCount);
			} else {
				element.textContent = target + (suffix ? ' ' + suffix : '');
			}
		}

		requestAnimationFrame(updateCount);
	}

	/**
	 * ニュースフィルター（大カテゴリタブ）
	 */
	function initNewsFilter() {
		const categoryTabs = document.querySelectorAll('.top-category-tab');
		const newsItems = document.querySelectorAll('.top-news-item');
		const subcategoryFilter = document.getElementById('subcategory-filter');
		const subcategoryTabs = document.querySelectorAll('.top-subcategory-tab');

		if (categoryTabs.length === 0 || newsItems.length === 0) return;

		// 大カテゴリタブのクリックイベント
		categoryTabs.forEach(function(tab) {
			tab.addEventListener('click', function(e) {
				e.preventDefault();
				
				const category = this.dataset.category;
				if (!category) return;

				// アクティブ状態を更新
				categoryTabs.forEach(function(t) {
					t.classList.remove('active');
				});
				this.classList.add('active');

				// GTニュース選択時にサブカテゴリフィルターを表示
				if (category === 'gt-news' && subcategoryFilter) {
					subcategoryFilter.classList.remove('hidden');
				} else if (subcategoryFilter) {
					subcategoryFilter.classList.add('hidden');
				}

				// ニュースアイテムをフィルター
				newsItems.forEach(function(item) {
					const itemCategory = item.dataset.category || '';
					const itemMainCategory = item.dataset.mainCategory || '';

					if (category === 'all' || itemCategory === category || itemMainCategory === category) {
						item.style.display = '';
						setTimeout(function() {
							item.style.opacity = '1';
							item.style.transform = 'translateY(0)';
						}, 10);
					} else {
						item.style.opacity = '0';
						item.style.transform = 'translateY(-10px)';
						setTimeout(function() {
							item.style.display = 'none';
						}, 300);
					}
				});
			});
		});

		// サブカテゴリタブのクリックイベント（GTニュースのみ）
		subcategoryTabs.forEach(function(tab) {
			tab.addEventListener('click', function(e) {
				e.preventDefault();
				
				const subcategory = this.dataset.subcategory;
				if (!subcategory) return;

				// アクティブ状態を更新
				subcategoryTabs.forEach(function(t) {
					t.classList.remove('active');
				});
				this.classList.add('active');

				// GTニュースのアイテムのみをさらにフィルター
				newsItems.forEach(function(item) {
					const itemCategory = item.dataset.category || '';
					const itemMainCategory = item.dataset.mainCategory || '';

					if (itemMainCategory === 'gt-news' || itemCategory === 'gt-news') {
						// サブカテゴリでフィルター（タグまたはカスタムフィールドから判定）
						if (subcategory === 'all') {
							item.style.display = '';
							setTimeout(function() {
								item.style.opacity = '1';
								item.style.transform = 'translateY(0)';
							}, 10);
						} else {
							// タグからサブカテゴリを判定（実際の実装ではカスタムフィールドやタグを使用）
							const itemTags = Array.from(item.querySelectorAll('.top-news-item__tags span')).map(function(tag) {
								return tag.textContent.trim();
							});
							
							if (itemTags.includes(subcategory)) {
								item.style.display = '';
								setTimeout(function() {
									item.style.opacity = '1';
									item.style.transform = 'translateY(0)';
								}, 10);
							} else {
								item.style.opacity = '0';
								item.style.transform = 'translateY(-10px)';
								setTimeout(function() {
									item.style.display = 'none';
								}, 300);
							}
						}
					}
				});
			});
		});
	}

	/**
	 * カウントアップ（既存の実装を保持）
	 */
	function initCountUp() {
		const countElements = document.querySelectorAll('[data-animate="count-up"]:not(.counted)');
		
		if (countElements.length === 0) return;

		const observerOptions = {
			root: null,
			rootMargin: '0px',
			threshold: 0.5
		};

		const countObserver = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
					entry.target.classList.add('counted');
					animateCountUp(entry.target);
					countObserver.unobserve(entry.target);
				}
			});
		}, observerOptions);

		countElements.forEach(function(element) {
			countObserver.observe(element);
		});
	}

	/**
	 * スムーズスクロール
	 */
	function initSmoothScroll() {
		const links = document.querySelectorAll('a[href^="#"]');
		
		links.forEach(function(link) {
			link.addEventListener('click', function(e) {
				const href = this.getAttribute('href');
				
				if (href === '#' || href === '') {
					return;
				}

				const targetId = href.substring(1);
				const targetElement = document.getElementById(targetId);

				if (targetElement) {
					e.preventDefault();
					
					const headerOffset = 80;
					const elementPosition = targetElement.getBoundingClientRect().top;
					const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

					window.scrollTo({
						top: offsetPosition,
						behavior: 'smooth'
					});
				}
			});
		});
	}

})();
