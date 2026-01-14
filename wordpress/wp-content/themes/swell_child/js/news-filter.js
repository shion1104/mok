/**
 * GT-NET 最新情報フィルター機能
 */
(function() {
  'use strict';

  document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.gtnet-news-filter');
    const newsItems = document.querySelectorAll('.gtnet-news-item');
    const emptyMessage = document.querySelector('.gtnet-news-empty');
    const newsList = document.getElementById('gtnet-news-list');

    if (!filterButtons.length || !newsItems.length) {
      return;
    }

    // 初期状態：すべてのボタンに非アクティブクラスを適用
    filterButtons.forEach(button => {
      const activeClass = button.getAttribute('data-active-class');
      const inactiveClass = button.getAttribute('data-inactive-class');
      
      if (activeClass && inactiveClass) {
        // クラスを分割して適用
        activeClass.split(' ').forEach(cls => button.classList.remove(cls));
        inactiveClass.split(' ').forEach(cls => button.classList.add(cls));
      }
    });

    // 「すべて」ボタンをアクティブに
    const allButton = Array.from(filterButtons).find(btn => btn.getAttribute('data-filter') === 'all');
    if (allButton) {
      const activeClass = allButton.getAttribute('data-active-class');
      if (activeClass) {
        activeClass.split(' ').forEach(cls => allButton.classList.add(cls));
        const inactiveClass = allButton.getAttribute('data-inactive-class');
        if (inactiveClass) {
          inactiveClass.split(' ').forEach(cls => allButton.classList.remove(cls));
        }
      }
    }

    // フィルターボタンのクリックイベント
    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        const filter = this.getAttribute('data-filter');
        const activeClass = this.getAttribute('data-active-class');
        const inactiveClass = this.getAttribute('data-inactive-class');

        // すべてのボタンの状態をリセット
        filterButtons.forEach(btn => {
          const btnActiveClass = btn.getAttribute('data-active-class');
          const btnInactiveClass = btn.getAttribute('data-inactive-class');
          
          if (btnActiveClass && btnInactiveClass) {
            btnActiveClass.split(' ').forEach(cls => btn.classList.remove(cls));
            btnInactiveClass.split(' ').forEach(cls => btn.classList.add(cls));
          }
        });

        // クリックされたボタンをアクティブに
        if (activeClass && inactiveClass) {
          activeClass.split(' ').forEach(cls => this.classList.add(cls));
          inactiveClass.split(' ').forEach(cls => this.classList.remove(cls));
        }

        // ニュースアイテムをフィルタリング
        let visibleCount = 0;
        newsItems.forEach(item => {
          const category = item.getAttribute('data-category');
          
          if (filter === 'all' || category === filter) {
            item.style.display = '';
            visibleCount++;
          } else {
            item.style.display = 'none';
          }
        });

        // 空メッセージの表示/非表示
        if (emptyMessage) {
          if (visibleCount === 0) {
            emptyMessage.style.display = 'block';
          } else {
            emptyMessage.style.display = 'none';
          }
        }
      });
    });
  });
})();
