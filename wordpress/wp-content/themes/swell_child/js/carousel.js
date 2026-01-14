/**
 * GT-NET カルーセル機能
 */
(function() {
  'use strict';

  document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.gtnet-carousel');
    if (!carousel) return;

    const slides = carousel.querySelectorAll('.gtnet-carousel-slide');
    const prevBtn = carousel.querySelector('.gtnet-carousel-prev');
    const nextBtn = carousel.querySelector('.gtnet-carousel-next');
    const indicators = carousel.querySelectorAll('.gtnet-carousel-indicator');

    let currentSlide = 0;
    let autoPlayTimer = null;

    function showSlide(index) {
      // すべてのスライドを非表示
      slides.forEach((slide, i) => {
        slide.classList.remove('opacity-100', 'z-10');
        slide.classList.add('opacity-0', 'z-0');
      });

      // 現在のスライドを表示
      if (slides[index]) {
        slides[index].classList.remove('opacity-0', 'z-0');
        slides[index].classList.add('opacity-100', 'z-10');
      }

      // インジケーターを更新
      indicators.forEach((indicator, i) => {
        if (i === index) {
          indicator.classList.remove('w-4', 'bg-white/30');
          indicator.classList.add('w-12', 'bg-white');
        } else {
          indicator.classList.remove('w-12', 'bg-white');
          indicator.classList.add('w-4', 'bg-white/30');
        }
      });

      currentSlide = index;
    }

    function nextSlide() {
      const next = (currentSlide + 1) % slides.length;
      showSlide(next);
    }

    function prevSlide() {
      const prev = (currentSlide - 1 + slides.length) % slides.length;
      showSlide(prev);
    }

    function startAutoPlay() {
      autoPlayTimer = setInterval(nextSlide, 5000);
    }

    function stopAutoPlay() {
      if (autoPlayTimer) {
        clearInterval(autoPlayTimer);
        autoPlayTimer = null;
      }
    }

    // イベントリスナー
    if (nextBtn) {
      nextBtn.addEventListener('click', function() {
        stopAutoPlay();
        nextSlide();
        startAutoPlay();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function() {
        stopAutoPlay();
        prevSlide();
        startAutoPlay();
      });
    }

    indicators.forEach((indicator, index) => {
      indicator.addEventListener('click', function() {
        stopAutoPlay();
        showSlide(index);
        startAutoPlay();
      });
    });

    // マウスホバーで自動再生を停止
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);

    // 初期化
    showSlide(0);
    startAutoPlay();
  });
})();
