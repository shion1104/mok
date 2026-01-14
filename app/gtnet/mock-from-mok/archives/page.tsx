'use client';

import React, { useState, useMemo } from 'react';
import Link from 'next/link';
import {
  ChevronRight,
  ChevronLeft,
  AlertTriangle,
  Lock,
  LogIn,
  Filter,
} from 'lucide-react';

// サンプルニュースデータ
const allNews = [
  { id: 1, date: "2026-01-05", category: "gt-news", subcategory: "ゴト情報", type: "レポート", title: "GT-NET Incident report 2025 全編公開開始", important: true, memberOnly: true },
  { id: 2, date: "2026-01-05", category: "victim", subcategory: null, type: "被害速報", title: "東京都：ぱちんこコーナーにおける不審な挙動の報告（11:00時点）", important: false, memberOnly: true },
  { id: 3, date: "2026-01-04", category: "topics", subcategory: null, type: "トピックス", title: "テンプレート更新のお知らせ", important: false, memberOnly: false },
  { id: 4, date: "2026-01-01", category: "gt-news", subcategory: "お知らせ", type: "お知らせ", title: "【謹賀新年】新年のご挨拶と2026年度の活動方針について", important: false, memberOnly: true },
  { id: 5, date: "2025-12-28", category: "industry", subcategory: null, type: "行政", title: "【警察庁】広告宣伝規制の解釈運用等について（通知）の周知依頼", important: true, memberOnly: true },
  { id: 6, date: "2025-12-25", category: "column", subcategory: null, type: "コラム", title: "2025年を振り返って：ゴト被害の傾向と対策", important: false, memberOnly: false },
  { id: 7, date: "2025-12-20", category: "gt-mail", subcategory: null, type: "メール", title: "【GTメール】年末年始の営業体制とゴト対策強化のお願い", important: false, memberOnly: true },
  { id: 8, date: "2025-12-17", category: "gt-news", subcategory: "統計", type: "統計", title: "［2026年2月～4月］ 検定満了遊技機一覧をアップデート", important: false, memberOnly: true },
  { id: 9, date: "2025-12-15", category: "victim", subcategory: null, type: "被害速報", title: "福岡県：スロットコーナーにてメダル不正流出の疑い（15:30）", important: false, memberOnly: true },
  { id: 10, date: "2025-12-11", category: "industry", subcategory: null, type: "事件", title: "【大阪】景品交換所における強盗未遂事件の発生について", important: false, memberOnly: true },
  { id: 11, date: "2025-12-10", category: "gt-mail", subcategory: null, type: "メール", title: "【GTメール】新型不正器具に関する緊急注意喚起", important: true, memberOnly: true },
  { id: 12, date: "2025-12-08", category: "topics", subcategory: null, type: "トピックス", title: "【セミナー講師】福島県遊技業協同組合連合会にて講演", important: false, memberOnly: false },
  { id: 13, date: "2025-12-04", category: "gt-news", subcategory: "防護・対策", type: "資料", title: "ホール内巡回用チェックシートの最新版（2025.12版）配布開始", important: false, memberOnly: true },
  { id: 14, date: "2025-11-30", category: "column", subcategory: null, type: "コラム", title: "より一層求められるリスクマネジメント", important: false, memberOnly: false },
  { id: 15, date: "2025-11-25", category: "topics", subcategory: null, type: "トピックス", title: "【検証動画】スマパチ球抜き方法（ニューギン／RE:BOOST枠）", important: false, memberOnly: false },
  { id: 16, date: "2025-11-20", category: "gt-news", subcategory: "ゴト情報", type: "ゴト情報", title: "【注意喚起】新型電波ゴトの発生について", important: true, memberOnly: true },
  { id: 17, date: "2025-11-15", category: "gt-news", subcategory: "噂未確認", type: "噂未確認", title: "関東圏で確認された不審な挙動について（未確認情報）", important: false, memberOnly: true },
  { id: 18, date: "2025-11-10", category: "industry", subcategory: null, type: "行政", title: "遊技機規則の一部改正について", important: false, memberOnly: true },
  { id: 19, date: "2025-11-05", category: "gt-news", subcategory: "話題", type: "話題", title: "業界カンファレンス2025 開催レポート", important: false, memberOnly: true },
  { id: 20, date: "2025-11-01", category: "victim", subcategory: null, type: "被害速報", title: "愛知県：スロットコーナーにて不正行為の疑い（09:30）", important: false, memberOnly: true },
];

const mainCategories = [
  { id: 'all', label: 'すべて' },
  { id: 'gt-news', label: 'GTニュース' },
  { id: 'industry', label: '業界ニュース' },
  { id: 'victim', label: '被害速報' },
  { id: 'gt-mail', label: 'GTメール' },
  { id: 'topics', label: 'トピックス' },
  { id: 'column', label: 'コラム' },
];

const gtNewsSubcategories = [
  { id: 'all', label: 'すべて' },
  { id: 'ゴト情報', label: 'ゴト情報' },
  { id: '噂未確認', label: '噂未確認' },
  { id: '防護・対策', label: '防護・対策' },
  { id: '話題', label: '話題' },
  { id: '統計', label: '統計' },
  { id: 'お知らせ', label: 'お知らせ' },
];

const ITEMS_PER_PAGE = 10;

export default function ArchivesPage() {
  const [mainCategory, setMainCategory] = useState('all');
  const [subCategory, setSubCategory] = useState('all');
  const [currentPage, setCurrentPage] = useState(1);

  // URLパラメータから初期カテゴリを取得
  React.useEffect(() => {
    if (typeof window !== 'undefined') {
      const params = new URLSearchParams(window.location.search);
      const cat = params.get('category');
      if (cat && mainCategories.some(c => c.id === cat)) {
        setMainCategory(cat);
      }
    }
  }, []);

  // フィルタリング
  const filteredNews = useMemo(() => {
    let result = allNews;

    if (mainCategory !== 'all') {
      result = result.filter(item => item.category === mainCategory);
    }

    if (mainCategory === 'gt-news' && subCategory !== 'all') {
      result = result.filter(item => item.subcategory === subCategory);
    }

    return result;
  }, [mainCategory, subCategory]);

  // ページネーション
  const totalPages = Math.ceil(filteredNews.length / ITEMS_PER_PAGE);
  const paginatedNews = filteredNews.slice(
    (currentPage - 1) * ITEMS_PER_PAGE,
    currentPage * ITEMS_PER_PAGE
  );

  // カテゴリ変更時にページをリセット
  const handleMainCategoryChange = (cat: string) => {
    setMainCategory(cat);
    setSubCategory('all');
    setCurrentPage(1);
  };

  const handleSubCategoryChange = (cat: string) => {
    setSubCategory(cat);
    setCurrentPage(1);
  };

  const getCategoryLabel = (cat: string) => {
    const found = mainCategories.find(c => c.id === cat);
    return found ? found.label : cat;
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
            <span className="text-slate-900 font-bold">ニュース一覧</span>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-12">
        <div className="max-w-4xl mx-auto">
          {/* Page Title */}
          <div className="mb-10">
            <h1 className="text-3xl font-black text-slate-900 mb-2">ニュース一覧</h1>
            <p className="text-slate-500">GT-NETが収集した業界動向と不正情報のアーカイブ</p>
          </div>

          {/* Main Category Tabs */}
          <div className="mb-6">
            <div className="flex flex-wrap gap-2">
              {mainCategories.map((cat) => (
                <button
                  key={cat.id}
                  onClick={() => handleMainCategoryChange(cat.id)}
                  className={`px-4 py-2 rounded-lg text-sm font-bold transition-all ${
                    mainCategory === cat.id
                      ? 'bg-slate-900 text-white'
                      : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
                  }`}
                >
                  {cat.label}
                </button>
              ))}
            </div>
          </div>

          {/* Subcategory Filter (GTニュース only) */}
          {mainCategory === 'gt-news' && (
            <div className="mb-8 p-4 bg-white rounded-xl border border-slate-100">
              <div className="flex items-center gap-3 mb-3">
                <Filter className="w-4 h-4 text-slate-400" />
                <span className="text-sm font-bold text-slate-600">サブカテゴリで絞り込み</span>
              </div>
              <div className="flex flex-wrap gap-2">
                {gtNewsSubcategories.map((cat) => (
                  <button
                    key={cat.id}
                    onClick={() => handleSubCategoryChange(cat.id)}
                    className={`px-3 py-1.5 rounded-full text-xs font-bold transition-all ${
                      subCategory === cat.id
                        ? 'bg-blue-600 text-white'
                        : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                    }`}
                  >
                    {cat.label}
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Results Count */}
          <div className="mb-4 text-sm text-slate-500">
            {filteredNews.length}件の記事
            {mainCategory !== 'all' && ` - ${getCategoryLabel(mainCategory)}`}
            {subCategory !== 'all' && ` / ${subCategory}`}
          </div>

          {/* News List */}
          <div className="space-y-3">
            {paginatedNews.length > 0 ? paginatedNews.map((item) => (
              <Link
                key={item.id}
                href={`/gtnet/1/news/${item.id}`}
                className={`group block bg-white p-5 rounded-xl border transition-all hover:shadow-lg hover:-translate-y-0.5 ${
                  item.important ? 'border-l-4 border-l-red-500 border-slate-200' : 'border-slate-100'
                }`}
              >
                <div className="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6">
                  <div className="flex-shrink-0 text-sm text-slate-400 font-bold w-24">
                    {item.date}
                  </div>

                  <div className="flex-grow">
                    <div className="flex flex-wrap items-center gap-2 mb-1.5">
                      <span className="px-2.5 py-0.5 text-[10px] font-black bg-slate-100 text-slate-600 rounded-full border border-slate-200">
                        {item.type}
                      </span>
                      {item.important && (
                        <span className="flex items-center gap-1 px-2 py-0.5 text-[10px] font-black text-white bg-red-500 rounded-full">
                          <AlertTriangle className="w-3 h-3" /> 重要
                        </span>
                      )}
                    </div>
                    <h3 className="font-bold text-slate-800 group-hover:text-blue-600 transition-colors flex items-center gap-2">
                      {item.title}
                      {item.memberOnly && (
                        <Lock className="w-4 h-4 text-slate-400 flex-shrink-0" />
                      )}
                    </h3>
                  </div>

                  <div className="flex-shrink-0">
                    <ChevronRight className="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" />
                  </div>
                </div>
              </Link>
            )) : (
              <div className="text-center py-20 bg-white rounded-xl border border-dashed border-slate-200 text-slate-400">
                該当する記事がありません
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
