/**
 * GT-NET ヘッダー機能
 * スクロール時の背景色変更とモバイルメニュー
 */
(function() {
  'use strict';

  document.addEventListener('DOMContentLoaded', function() {
    const header = document.getElementById('gtnet-header');
    const mobileMenuBtn = document.querySelector('.gtnet-mobile-menu-btn');
    const mobileMenu = document.getElementById('gtnet-mobile-menu');
    const mobileCloseBtn = document.querySelector('.gtnet-mobile-close');
    const mobileLinks = document.querySelectorAll('.gtnet-mobile-link');
    const menuIcon = document.querySelector('.gtnet-menu-icon');
    const closeIcon = document.querySelector('.gtnet-close-icon');

    if (!header) return;

    let scrolled = false;

    // スクロール時の処理
    function handleScroll() {
      const scrollY = window.scrollY;
      const shouldBeScrolled = scrollY > 20;

      if (shouldBeScrolled !== scrolled) {
        scrolled = shouldBeScrolled;
        
        if (scrolled) {
          // スクロール時：白背景
          header.classList.add('gtnet-header-scrolled');
          header.classList.remove('gtnet-header-transparent');
        } else {
          // トップ時：透明背景
          header.classList.remove('gtnet-header-scrolled');
          header.classList.add('gtnet-header-transparent');
        }
      }
    }

    // モバイルメニューの開閉
    function openMobileMenu() {
      mobileMenu.classList.remove('hidden');
      menuIcon.classList.add('hidden');
      closeIcon.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
      mobileMenu.classList.add('hidden');
      menuIcon.classList.remove('hidden');
      closeIcon.classList.add('hidden');
      document.body.style.overflow = '';
    }

    // イベントリスナー
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // 初期状態を設定

    if (mobileMenuBtn) {
      mobileMenuBtn.addEventListener('click', function() {
        if (mobileMenu.classList.contains('hidden')) {
          openMobileMenu();
        } else {
          closeMobileMenu();
        }
      });
    }

    if (mobileCloseBtn) {
      mobileCloseBtn.addEventListener('click', closeMobileMenu);
    }

    mobileLinks.forEach(function(link) {
      link.addEventListener('click', closeMobileMenu);
    });

    // ESCキーでメニューを閉じる
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
        closeMobileMenu();
      }
    });
  });
})();
