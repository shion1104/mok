'use client';

import React, { useState, useMemo } from 'react';
import Link from 'next/link';
import {
  ChevronRight,
  ChevronLeft,
  PlayCircle,
  Lock,
  LogIn,
  Clock,
} from 'lucide-react';

// サンプル動画データ
const allMovies = [
  { id: 1, date: "2026-01-03", category: "kensyou", title: "【検証】スマパチ球抜き方法（ニューギン／RE:BOOST枠）", duration: "8:32", memberOnly: true },
  { id: 2, date: "2025-12-28", category: "hankou", title: "【犯行映像】電波ゴト犯行の一部始終", duration: "3:45", memberOnly: true },
  { id: 3, date: "2025-12-20", category: "gt-movie", title: "【GT解説】2025年のゴト被害傾向まとめ", duration: "15:20", memberOnly: true },
  { id: 4, date: "2025-12-15", category: "kensyou", title: "【検証】体感器使用時の挙動確認", duration: "6:18", memberOnly: true },
  { id: 5, date: "2025-12-10", category: "hankou", title: "【犯行映像】スロット不正ROM交換の手口", duration: "4:55", memberOnly: true },
  { id: 6, date: "2025-12-05", category: "gt-movie", title: "【GT解説】新人スタッフ向け 不正発見の基礎", duration: "22:10", memberOnly: true },
  { id: 7, date: "2025-11-28", category: "kensyou", title: "【検証】磁石ゴトの検証と対策", duration: "9:45", memberOnly: true },
  { id: 8, date: "2025-11-20", category: "hankou", title: "【犯行映像】グループによる組織的ゴト行為", duration: "5:30", memberOnly: true },
  { id: 9, date: "2025-11-15", category: "gt-movie", title: "【GT解説】防犯カメラの効果的な配置", duration: "18:00", memberOnly: true },
  { id: 10, date: "2025-11-10", category: "kensyou", title: "【検証】不正基板の見分け方", duration: "12:25", memberOnly: true },
  { id: 11, date: "2025-11-05", category: "hankou", title: "【犯行映像】セルフ精算機への不正アクセス", duration: "2:58", memberOnly: true },
  { id: 12, date: "2025-10-30", category: "gt-movie", title: "【GT解説】警察検査対応マニュアル", duration: "25:40", memberOnly: true },
  { id: 13, date: "2025-10-25", category: "kensyou", title: "【検証】ピアノ線ゴトの実態", duration: "7:12", memberOnly: true },
  { id: 14, date: "2025-10-20", category: "gt-movie", title: "【GT解説】ホール巡回のポイント", duration: "14:30", memberOnly: true },
  { id: 15, date: "2025-10-15", category: "kensyou", title: "【検証】スロット目押しツールの検証", duration: "10:05", memberOnly: true },
];

const categories = [
  { id: 'all', label: 'すべて' },
  { id: 'gt-movie', label: 'GTムービー' },
  { id: 'hankou', label: '犯行動画' },
  { id: 'kensyou', label: '検証動画' },
];

const ITEMS_PER_PAGE = 12;

export default function MoviesPage() {
  const [category, setCategory] = useState('all');
  const [currentPage, setCurrentPage] = useState(1);

  // フィルタリング
  const filteredMovies = useMemo(() => {
    if (category === 'all') {
      return allMovies;
    }
    return allMovies.filter(item => item.category === category);
  }, [category]);

  // ページネーション
  const totalPages = Math.ceil(filteredMovies.length / ITEMS_PER_PAGE);
  const paginatedMovies = filteredMovies.slice(
    (currentPage - 1) * ITEMS_PER_PAGE,
    currentPage * ITEMS_PER_PAGE
  );

  // カテゴリ変更時にページをリセット
  const handleCategoryChange = (cat: string) => {
    setCategory(cat);
    setCurrentPage(1);
  };

  const getCategoryLabel = (cat: string) => {
    const found = categories.find(c => c.id === cat);
    return found ? found.label : cat;
  };

  const getCategoryColor = (cat: string) => {
    switch (cat) {
      case 'gt-movie': return 'bg-blue-100 text-blue-700 border-blue-200';
      case 'hankou': return 'bg-red-100 text-red-700 border-red-200';
      case 'kensyou': return 'bg-green-100 text-green-700 border-green-200';
      default: return 'bg-slate-100 text-slate-700 border-slate-200';
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-900">
      {/* Header */}
      <header className="bg-white shadow-sm sticky top-0 z-50">
        <div className="container mx-auto px-4 py-4 flex justify-between items-center">
          <Link href="/gtnet/1/" className="flex items-center">
            <img
              src="/gtnet/logo.png"
              alt="株式会社ジーティネット"
              className="h-10 w-auto"
            />
          </Link>
          <div className="flex items-center gap-4">
            <Link href="/gtnet/1/login/" className="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">
              <LogIn className="w-4 h-4" />
              会員ログイン
            </Link>
          </div>
        </div>
      </header>

      {/* Breadcrumb */}
      <div className="bg-white border-b border-slate-100">
        <div className="container mx-auto px-4 py-3">
          <div className="flex items-center gap-2 text-sm text-slate-500">
            <Link href="/gtnet/1/" className="hover:text-blue-600 transition-colors">トップ</Link>
            <ChevronRight className="w-4 h-4" />
            <span className="text-slate-900 font-bold">動画ライブラリ</span>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-12">
        <div className="max-w-5xl mx-auto">
          {/* Page Title */}
          <div className="mb-10">
            <div className="flex items-center gap-3 mb-2">
              <PlayCircle className="w-8 h-8 text-blue-500" />
              <h1 className="text-3xl font-black text-slate-900">動画ライブラリ</h1>
            </div>
            <p className="text-slate-500">ゴト対策に役立つ検証動画・解説動画を公開しています</p>
            <div className="mt-3 flex items-center gap-2 text-sm text-amber-600 bg-amber-50 px-4 py-2 rounded-lg border border-amber-100">
              <Lock className="w-4 h-4" />
              <span className="font-bold">会員限定コンテンツ</span>
              <span className="text-amber-500">- 動画の視聴には会員ログインが必要です</span>
            </div>
          </div>

          {/* Category Tabs */}
          <div className="mb-8">
            <div className="flex flex-wrap gap-2">
              {categories.map((cat) => (
                <button
                  key={cat.id}
                  onClick={() => handleCategoryChange(cat.id)}
                  className={`px-5 py-2.5 rounded-xl text-sm font-bold transition-all ${
                    category === cat.id
                      ? 'bg-slate-900 text-white'
                      : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
                  }`}
                >
                  {cat.label}
                </button>
              ))}
            </div>
          </div>

          {/* Results Count */}
          <div className="mb-4 text-sm text-slate-500">
            {filteredMovies.length}件の動画
            {category !== 'all' && ` - ${getCategoryLabel(category)}`}
          </div>

          {/* Video Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {paginatedMovies.length > 0 ? paginatedMovies.map((item) => (
              <div
                key={item.id}
                className="group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer"
              >
                {/* Thumbnail Placeholder */}
                <div className="relative aspect-video bg-slate-900 flex items-center justify-center">
                  <PlayCircle className="w-16 h-16 text-white/30 group-hover:text-white/60 group-hover:scale-110 transition-all" />
                  <div className="absolute bottom-2 right-2 bg-black/80 text-white text-xs font-bold px-2 py-1 rounded flex items-center gap-1">
                    <Clock className="w-3 h-3" />
                    {item.duration}
                  </div>
                  <div className="absolute top-2 left-2">
                    <span className={`px-2 py-1 text-[10px] font-black rounded border ${getCategoryColor(item.category)}`}>
                      {getCategoryLabel(item.category)}
                    </span>
                  </div>
                </div>

                {/* Info */}
                <div className="p-4">
                  <p className="text-xs text-slate-400 mb-1">{item.date}</p>
                  <h3 className="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-sm leading-snug line-clamp-2">
                    {item.title}
                  </h3>
                </div>
              </div>
            )) : (
              <div className="col-span-full text-center py-20 bg-white rounded-xl border border-dashed border-slate-200 text-slate-400">
                該当する動画がありません
              </div>
            )}
          </div>

          {/* Pagination */}
          {totalPages > 1 && (
            <div className="mt-10 flex items-center justify-center gap-2">
              <button
                onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                disabled={currentPage === 1}
                className="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
              >
                <ChevronLeft className="w-5 h-5" />
              </button>

              {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                <button
                  key={page}
                  onClick={() => setCurrentPage(page)}
                  className={`w-10 h-10 rounded-lg font-bold text-sm transition-all ${
                    currentPage === page
                      ? 'bg-slate-900 text-white'
                      : 'border border-slate-200 text-slate-600 hover:bg-slate-100'
                  }`}
                >
                  {page}
                </button>
              ))}

              <button
                onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                disabled={currentPage === totalPages}
                className="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
              >
                <ChevronRight className="w-5 h-5" />
              </button>
            </div>
          )}
        </div>
      </main>

      {/* Footer */}
      <footer className="bg-slate-900 py-8 mt-12">
        <div className="container mx-auto px-4 text-center">
          <img
            src="/gtnet/logo.png"
            alt="株式会社ジーティネット"
            className="h-8 w-auto mx-auto brightness-0 invert mb-4"
          />
          <p className="text-slate-500 text-xs">
            © 2010 - 2026 GT-NET Co.,Ltd. All Rights Reserved.
          </p>
        </div>
      </footer>
    </div>
  );
}
