'use client';

import React, { useState, useEffect, useCallback } from 'react';
import Link from 'next/link';
import {
  ShieldCheck,
  AlertTriangle,
  FileText,
  PlayCircle,
  BarChart3,
  Menu,
  X,
  ChevronRight,
  ChevronLeft,
  Mail,
  Phone,
  Clock,
  ExternalLink,
  Database,
  Lock,
  LogIn,
  User,
  Car,
  HelpCircle,
} from 'lucide-react';

const App = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [currentSlide, setCurrentSlide] = useState(0);
  const [newsFilter, setNewsFilter] = useState('all');

  // --- Carousel Data ---
  const slides = [
    {
      id: 1,
      title: "パチンコホールの\n不正を未然に防ぐ",
      subtitle: "リスクマネジメント",
      desc: "ゴト対策からセキュリティ監査まで、現場主義のコンサルティングでホール経営を守ります。",
      bg: "bg-slate-900",
      accent: "text-blue-500"
    },
    {
      id: 2,
      title: "データが語る\n真実の防犯対策",
      subtitle: "データ分析",
      desc: "全国の被害発生状況をリアルタイムに収集。統計に基づいた的確な対策を提案します。",
      bg: "bg-blue-900",
      accent: "text-blue-300"
    },
    {
      id: 3,
      title: "教育こそが\n最大の防御策",
      subtitle: "スタッフ教育",
      desc: "スタッフの意識を変え、有事に強い組織を作る。独自のカリキュラムで防犯スキルを向上。",
      bg: "bg-slate-800",
      accent: "text-blue-400"
    }
  ];

  // --- Combined News Data ---
  const allNews = [
    { id: 1, date: "2026-01-05", category: "gt-news", type: "レポート", title: "GT-NET Incident report 2025 全編公開開始", important: true, memberOnly: true },
    { id: 2, date: "2026-01-05", category: "victim", type: "被害速報", title: "東京都：ぱちんこコーナーにおける不審な挙動の報告（11:00時点）", important: false, memberOnly: true },
    { id: 3, date: "2026-01-04", category: "topics", type: "トピックス", title: "テンプレート更新のお知らせ", important: false, memberOnly: false },
    { id: 4, date: "2026-01-01", category: "gt-news", type: "お知らせ", title: "【謹賀新年】新年のご挨拶と2026年度の活動方針について", important: false, memberOnly: true },
    { id: 5, date: "2025-12-28", category: "industry", type: "行政", title: "【警察庁】広告宣伝規制の解釈運用等について（通知）の周知依頼", important: true, memberOnly: true },
    { id: 6, date: "2025-12-25", category: "column", type: "コラム", title: "2025年を振り返って：ゴト被害の傾向と対策", important: false, memberOnly: false },
    { id: 7, date: "2025-12-20", category: "gt-mail", type: "メール", title: "【GTメール】年末年始の営業体制とゴト対策強化のお願い", important: false, memberOnly: true },
    { id: 8, date: "2025-12-17", category: "gt-news", type: "統計", title: "［2026年2月～4月］ 検定満了遊技機一覧をアップデート", important: false, memberOnly: true },
    { id: 9, date: "2025-12-15", category: "victim", type: "被害速報", title: "福岡県：スロットコーナーにてメダル不正流出の疑い（15:30）", important: false, memberOnly: true },
    { id: 10, date: "2025-12-11", category: "industry", type: "事件", title: "【大阪】景品交換所における強盗未遂事件の発生について", important: false, memberOnly: true },
    { id: 11, date: "2025-12-10", category: "gt-mail", type: "メール", title: "【GTメール】新型不正器具に関する緊急注意喚起", important: true, memberOnly: true },
    { id: 12, date: "2025-12-08", category: "topics", type: "トピックス", title: "【セミナー講師】福島県遊技業協同組合連合会にて講演", important: false, memberOnly: false },
    { id: 13, date: "2025-12-04", category: "gt-news", type: "資料", title: "ホール内巡回用チェックシートの最新版（2025.12版）配布開始", important: false, memberOnly: true },
    { id: 14, date: "2025-11-30", category: "column", type: "コラム", title: "より一層求められるリスクマネジメント", important: false, memberOnly: false },
    { id: 15, date: "2025-11-25", category: "topics", type: "トピックス", title: "【検証動画】スマパチ球抜き方法（ニューギン／RE:BOOST枠）", important: false, memberOnly: false },
  ];

  const filteredNews = newsFilter === 'all'
    ? allNews
    : allNews.filter(item => item.category === newsFilter);

  // --- Handlers ---
  const nextSlide = useCallback(() => {
    setCurrentSlide((prev) => (prev === slides.length - 1 ? 0 : prev + 1));
  }, [slides.length]);

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev === 0 ? slides.length - 1 : prev - 1));
  };

  useEffect(() => {
    const timer = setInterval(nextSlide, 5000);
    return () => clearInterval(timer);
  }, [nextSlide]);

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 20);
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const getCategoryColor = (cat: string) => {
    switch(cat) {
      case 'gt-news': return 'bg-slate-100 text-slate-700 border-slate-200';
      case 'industry': return 'bg-slate-100 text-slate-700 border-slate-200';
      case 'victim': return 'bg-slate-100 text-slate-700 border-slate-200';
      case 'gt-mail': return 'bg-slate-100 text-slate-700 border-slate-200';
      case 'topics': return 'bg-slate-100 text-slate-700 border-slate-200';
      case 'column': return 'bg-slate-100 text-slate-700 border-slate-200';
      default: return 'bg-slate-100 text-slate-700 border-slate-200';
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-900 overflow-x-hidden m-0 p-0">
      {/* Header */}
      <header className={`fixed w-full z-50 transition-all duration-300 ${scrolled ? 'bg-white/95 backdrop-blur-sm shadow-md py-2' : 'bg-transparent py-4'}`}>
        <div className="container mx-auto px-6 flex justify-between items-center">
          <Link href="/" className="flex items-center">
            <img
              src="/logo.png"
              alt="株式会社ジーティネット"
              className={`h-10 md:h-12 w-auto transition-all ${scrolled ? '' : 'brightness-0 invert'}`}
            />
          </Link>

          <nav className={`hidden md:flex items-center gap-6 ${scrolled ? 'text-slate-900' : 'text-white'}`}>
            <Link href="/" className="text-sm font-bold hover:text-blue-500 transition-colors">ホーム</Link>
            <Link href="/services/" className="text-sm font-bold hover:text-blue-500 transition-colors">事業紹介</Link>
            <Link href="/archives/?category=column" className="text-sm font-bold hover:text-blue-500 transition-colors">コラム</Link>
            <Link href="/login/" className={`flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all ${scrolled ? 'bg-slate-100 hover:bg-slate-200 text-slate-700' : 'bg-white/10 hover:bg-white/20 backdrop-blur-sm'}`}>
              <LogIn className="w-4 h-4" />
              会員ログイン
            </Link>
            <Link href="/contact/" className="bg-blue-600 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/20">
              お問い合わせ
            </Link>
          </nav>

          <button className={`md:hidden ${scrolled ? 'text-slate-900' : 'text-white'}`} onClick={() => setIsMenuOpen(!isMenuOpen)}>
            {isMenuOpen ? <X /> : <Menu />}
          </button>
        </div>
      </header>

      {/* Mobile Menu */}
      {isMenuOpen && (
        <div className="fixed inset-0 bg-slate-900 text-white z-[60] p-8 flex flex-col gap-6">
          <button className="self-end" onClick={() => setIsMenuOpen(false)}><X className="w-8 h-8" /></button>
          <nav className="flex flex-col gap-6 text-3xl font-black">
            <Link href="/" onClick={() => setIsMenuOpen(false)}>ホーム</Link>
            <Link href="/services/" onClick={() => setIsMenuOpen(false)}>事業紹介</Link>
            <Link href="/archives/?category=column" onClick={() => setIsMenuOpen(false)}>コラム</Link>
            <Link href="/contact/" className="text-blue-400" onClick={() => setIsMenuOpen(false)}>お問い合わせ</Link>
          </nav>
          <div className="mt-auto pt-8 border-t border-white/10">
            <Link href="/login/" className="flex items-center gap-3 bg-white/10 px-6 py-4 rounded-2xl text-lg font-bold hover:bg-white/20 transition-all" onClick={() => setIsMenuOpen(false)}>
              <LogIn className="w-6 h-6" />
              会員ログイン
            </Link>
          </div>
        </div>
      )}

      {/* Dynamic Carousel MV */}
      <section className="relative h-[600px] md:h-[700px] flex items-center overflow-hidden bg-slate-900">
        {slides.map((slide, idx) => (
          <div
            key={slide.id}
            className={`absolute inset-0 transition-opacity duration-700 ease-in-out ${idx === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0'} ${slide.bg}`}
          >
            {/* Subtle Background Overlay */}
            <div className="absolute inset-0 bg-gradient-to-b from-black/40 via-black/20 to-black/60"></div>

            <div className="container mx-auto px-4 md:px-6 h-full flex flex-col justify-center relative z-20">
              <div className="max-w-4xl">
                <p className="text-blue-400 font-bold tracking-wider mb-4 text-xs md:text-sm uppercase">{slide.subtitle}</p>
                <h2 className="text-4xl md:text-6xl font-bold text-white leading-tight mb-6 whitespace-pre-line">
                  {slide.title.split('\n')[0]}<br />
                  <span className="text-blue-400">{slide.title.split('\n')[1]}</span>
                </h2>
                <p className="text-slate-200 text-base md:text-xl max-w-2xl leading-relaxed font-medium">
                  {slide.desc}
                </p>
              </div>
            </div>
          </div>
        ))}

        {/* Carousel Controls */}
        <div className="absolute bottom-12 md:bottom-16 left-0 w-full z-30">
          <div className="container mx-auto px-4 md:px-6">
            <div className="flex justify-between items-center">
              <div className="flex gap-4">
                <button onClick={prevSlide} className="w-12 h-12 rounded-full border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-all backdrop-blur-sm">
                  <ChevronLeft className="w-6 h-6" />
                </button>
                <button onClick={nextSlide} className="w-12 h-12 rounded-full border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-all backdrop-blur-sm">
                  <ChevronRight className="w-6 h-6" />
                </button>
              </div>

              <div className="flex gap-3">
                {slides.map((_, i) => (
                  <button
                    key={i}
                    onClick={() => setCurrentSlide(i)}
                    className={`h-1.5 rounded-full transition-all ${i === currentSlide ? 'w-12 bg-white' : 'w-4 bg-white/30'}`}
                  />
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Quick Stats Overlay */}
      <div className="container mx-auto px-4 md:px-6 -mt-8 md:-mt-6 relative z-40">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div className="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-red-200 flex flex-col justify-center">
            <div className="flex items-center gap-2 text-red-600 mb-3">
              <AlertTriangle className="w-5 h-5" />
              <span className="text-xs font-bold tracking-wide text-red-600">最新状況</span>
            </div>
            <p className="text-slate-600 text-sm font-medium mb-2">2026年 被害発生件数</p>
            <div className="flex items-baseline gap-2">
              <span className="text-4xl font-bold text-slate-900">0</span>
              <span className="text-slate-500 font-medium text-sm">件</span>
            </div>
          </div>
          <div className="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-blue-200 flex flex-col justify-center">
            <div className="flex items-center gap-2 text-blue-600 mb-3">
              <BarChart3 className="w-5 h-5" />
              <span className="text-xs font-bold tracking-wide text-blue-600">年間集計</span>
            </div>
            <p className="text-slate-600 text-sm font-medium mb-2">2025年 被害発生件数</p>
            <div className="flex items-baseline gap-2">
              <span className="text-4xl font-bold text-slate-900">65</span>
              <span className="text-slate-500 font-medium text-sm">件</span>
            </div>
          </div>
          <div className="bg-slate-900 p-6 md:p-8 rounded-xl shadow-lg border border-slate-800 text-white flex flex-col justify-center">
            <div className="flex items-center gap-2 text-blue-400 mb-3">
              <Clock className="w-5 h-5" />
              <span className="text-xs font-bold tracking-wide">更新情報</span>
            </div>
            <p className="text-base font-medium leading-relaxed">
              最終更新：2026年01月05日<br />
              <span className="text-slate-400 text-sm">被害情報を随時更新中</span>
            </p>
          </div>
        </div>
      </div>

      {/* First Time Visitor Banner */}
      <div className="container mx-auto px-4 md:px-6 mt-12 mb-8">
        <Link href="/firsttime/" className="group flex flex-col md:flex-row items-center justify-between bg-blue-600 rounded-xl px-8 md:px-10 py-6 md:py-8 border border-blue-700 hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
          <div className="flex items-center gap-5 md:gap-6 w-full md:w-auto">
            <div className="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-white/20 rounded-lg flex items-center justify-center">
              <HelpCircle className="w-6 h-6 md:w-7 md:h-7 text-white" />
            </div>
            <div className="flex flex-col gap-1">
              <h3 className="text-xl md:text-2xl font-bold text-white leading-tight">
                はじめての方へ
              </h3>
              <p className="text-sm md:text-base text-blue-50 font-medium leading-relaxed">
                GT-NETは不正事例から実務マニュアルまで網羅した会員制専門サイトです。<span className="hidden md:inline"> 新規会員登録で、すぐにご利用いただけます。</span>
              </p>
            </div>
          </div>
          <div className="flex items-center gap-2 bg-white px-5 py-3 rounded-lg group-hover:bg-blue-50 transition-colors flex-shrink-0 mt-4 md:mt-0">
            <span className="text-base font-bold text-blue-600 group-hover:text-blue-700">詳しく見る</span>
            <ChevronRight className="w-5 h-5 text-blue-600 group-hover:text-blue-700" />
          </div>
        </Link>
      </div>

      {/* Latest News Section */}
      <section className="py-20 bg-slate-50">
        <div className="container mx-auto px-4 md:px-6">
          <div className="flex items-end justify-between mb-12">
            <div>
              <h3 className="text-3xl md:text-4xl font-bold text-slate-900 mb-2 tracking-tight">お知らせ・キャンペーン</h3>
              <p className="text-slate-600 text-sm">GT-NETからのお知らせとキャンペーン情報</p>
            </div>
            <Link href="/archives/" className="flex items-center gap-1.5 text-slate-700 hover:text-slate-900 font-medium text-sm transition-colors pb-1">
              すべて見る
              <ChevronRight className="w-4 h-4" />
            </Link>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-5 gap-6">
            {/* Featured News - Left Large */}
            <Link href="/news/featured-1" className="lg:col-span-3 group block bg-white rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-lg transition-all duration-300 overflow-hidden">
              <div className="h-64 md:h-80 bg-slate-100 overflow-hidden relative">
                <img
                  src="https://images.unsplash.com/photo-1556761175-4b46a572b786?w=1200&h=500&fit=crop"
                  alt="新規会員登録キャンペーン"
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
                <div className="absolute top-4 left-4">
                  <span className="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-full shadow-lg">注目</span>
                </div>
              </div>
              <div className="p-6">
                <div className="mb-3">
                  <time className="text-sm text-slate-500 font-medium">2026年01月10日</time>
                </div>
                <h4 className="text-xl md:text-2xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors leading-tight">
                  新規会員登録キャンペーン実施中！初月無料でお試しください
                </h4>
                <p className="text-slate-600 leading-relaxed text-sm md:text-base">
                  新規会員登録いただいた方に、初月無料でGT-NETの全機能をお試しいただけます。不正事例データベースから実務マニュアルまで、すべてのコンテンツにアクセス可能です。
                </p>
              </div>
            </Link>

            {/* News Grid - Right 2x2 */}
            <div className="lg:col-span-2 grid grid-cols-2 gap-4">
              {/* News 1 - 防犯セミナー */}
              <Link href="/news/seminar-2026" className="group bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-300 overflow-hidden">
                <div className="h-32 bg-slate-100 overflow-hidden relative">
                  <img
                    src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&h=600&fit=crop"
                    alt="防犯セミナー"
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div className="absolute top-2 left-2">
                    <span className="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-full backdrop-blur-sm">お知らせ</span>
                  </div>
                </div>
                <div className="p-3">
                  <h4 className="text-sm font-bold text-slate-900 mb-1.5 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                    2026年度 防犯セミナー開催のお知らせ
                  </h4>
                  <time className="text-[10px] text-slate-500 font-medium">2026年01月08日</time>
                </div>
              </Link>

              {/* News 2 - キャンペーン */}
              <Link href="/news/campaign-annual" className="group bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-300 overflow-hidden">
                <div className="h-32 bg-slate-100 overflow-hidden relative">
                  <img
                    src="https://images.unsplash.com/photo-1556740758-90de374c12ad?w=800&h=600&fit=crop"
                    alt="キャンペーン"
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div className="absolute top-2 left-2">
                    <span className="px-2 py-0.5 bg-green-50 text-green-700 text-[10px] font-bold rounded-full backdrop-blur-sm">キャンペーン</span>
                  </div>
                </div>
                <div className="p-3">
                  <h4 className="text-sm font-bold text-slate-900 mb-1.5 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                    年間契約で最大20%OFF！お得なプラン
                  </h4>
                  <time className="text-[10px] text-slate-500 font-medium">2026年01月03日</time>
                </div>
              </Link>

              {/* News 3 - データベース機能 */}
              <Link href="/news/update-database" className="group bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-300 overflow-hidden">
                <div className="h-32 bg-slate-100 overflow-hidden relative">
                  <img
                    src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=600&fit=crop"
                    alt="データベース機能"
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div className="absolute top-2 left-2">
                    <span className="px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded-full backdrop-blur-sm">アップデート</span>
                  </div>
                </div>
                <div className="p-3">
                  <h4 className="text-sm font-bold text-slate-900 mb-1.5 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                    データベース機能を大幅に強化しました
                  </h4>
                  <time className="text-[10px] text-slate-500 font-medium">2026年01月05日</time>
                </div>
              </Link>

              {/* News 4 - 休業案内 */}
              <Link href="/news/holiday-notice" className="group bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-300 overflow-hidden">
                <div className="h-32 bg-slate-100 overflow-hidden relative">
                  <img
                    src="https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=800&h=600&fit=crop"
                    alt="休業案内"
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div className="absolute top-2 left-2">
                    <span className="px-2 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-full backdrop-blur-sm">お知らせ</span>
                  </div>
                </div>
                <div className="p-3">
                  <h4 className="text-sm font-bold text-slate-900 mb-1.5 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                    年末年始休業のご案内
                  </h4>
                  <time className="text-[10px] text-slate-500 font-medium">2026年01月05日</time>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Services Section */}
      <section className="py-24 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-t border-b border-slate-200 relative overflow-hidden">
        <div className="absolute inset-0 opacity-30 pointer-events-none">
           <div className="absolute top-0 left-0 w-full h-full bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:40px_40px]"></div>
        </div>
        <div className="container mx-auto px-4 md:px-6 relative z-10">
          <div className="mb-16">
            <div className="mb-4">
              <div className="w-16 h-1 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full"></div>
            </div>
            <h3 className="text-4xl md:text-5xl font-bold mb-4 text-slate-900 tracking-tight">事業紹介</h3>
            <p className="text-slate-600 max-w-2xl font-medium text-base">
              30年以上の実績に基づく、パチンコ業界特化型のリスクマネジメント。
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            {[
              { title: "検査事業", desc: "不正改造の有無を徹底調査。", icon: <ShieldCheck />, href: "/services/inspection/" },
              { title: "監査事業", desc: "適正なホール運営を第三者評価。", icon: <FileText />, href: "/services/audit/" },
              { title: "巡回事業", desc: "現場の脆弱性をプロが診断。", icon: <BarChart3 />, href: "/services/patrol/" },
              { title: "教育事業", desc: "防犯意識を組織の文化へ。", icon: <PlayCircle />, href: "/services/education/" },
              { title: "情報提供事業", desc: "業界動向と不正情報を配信。", icon: <Database />, href: "/services/information/" },
            ].map((s, i) => (
              <Link key={i} href={s.href} className="group p-6 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-slate-300 hover:shadow-md transition-all duration-200 cursor-pointer">
                <div className="w-12 h-12 rounded-lg bg-slate-900 text-white flex items-center justify-center mb-4 group-hover:bg-blue-600 transition-colors">
                  {s.icon}
                </div>
                <h4 className="text-lg font-bold mb-3 text-slate-900">{s.title}</h4>
                <p className="text-slate-600 text-sm leading-relaxed mb-4">
                  {s.desc}ホール経営のあらゆるリスクに対応します。
                </p>
                <div className="flex items-center gap-2 text-xs font-bold text-blue-600">
                  詳しく見る <ChevronRight className="w-4 h-4" />
                </div>
              </Link>
            ))}
          </div>

        </div>
      </section>

      {/* Unified Integrated News Feed */}
      <section className="py-24 bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800 relative overflow-hidden">
        {/* Background Pattern */}
        <div className="absolute inset-0 opacity-10 pointer-events-none">
          <div className="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_80%_20%,rgba(59,130,246,0.2),transparent_50%)]"></div>
        </div>
        
        <div className="container mx-auto px-4 md:px-6 relative z-10">
          <div className="flex flex-col lg:flex-row gap-12">

            {/* Main News Feed */}
            <div className="lg:w-2/3">
              <div className="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                  <h3 className="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                    最新情報
                    <span className="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                  </h3>
                  <p className="text-slate-200 mt-2 text-sm md:text-base font-medium">GT-NETが収集した最新の業界動向と不正情報</p>
                </div>

                {/* Filter Tabs */}
                <div className="flex gap-1 overflow-x-auto pb-2 -mb-2">
                  {[
                    { id: 'all', label: 'すべて' },
                    { id: 'gt-news', label: 'GTニュース' },
                    { id: 'industry', label: '業界' },
                    { id: 'victim', label: '被害速報' },
                    { id: 'gt-mail', label: 'GTメール' },
                    { id: 'topics', label: 'トピックス' },
                    { id: 'column', label: 'コラム' }
                  ].map((tab) => (
                    <button
                      key={tab.id}
                      onClick={() => setNewsFilter(tab.id)}
                      className={`px-4 py-2 rounded-lg text-xs font-bold whitespace-nowrap transition-all ${
                        newsFilter === tab.id
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-white/10 text-slate-200 hover:bg-white/20 border border-white/20'
                      }`}
                    >
                      {tab.label}
                    </button>
                  ))}
                </div>
              </div>

              {/* News List */}
              <div className="space-y-4">
                {filteredNews.length > 0 ? filteredNews.map((item) => (
                  <Link
                    key={item.id}
                    href={`/news/${item.id}`}
                    className={`group relative bg-white/95 backdrop-blur-sm p-5 rounded-xl border transition-all hover:shadow-lg hover:bg-white cursor-pointer flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 ${
                      item.important ? 'border-l-4 border-l-red-500 border-white/30' : 'border-white/30'
                    }`}
                  >
                    <div className="flex-shrink-0 flex flex-col items-start sm:items-center w-24">
                       <span className="text-xs font-black text-slate-500">{item.date.split('-')[0]}</span>
                       <span className="text-lg font-black text-slate-900">{item.date.split('-')[1]}.{item.date.split('-')[2]}</span>
                    </div>

                    <div className="flex-grow">
                      <div className="flex flex-wrap items-center gap-2 mb-2">
                        <span className={`px-3 py-0.5 text-[10px] font-black border rounded-full bg-slate-100 text-slate-700 border-slate-200`}>
                          {item.type}
                        </span>
                        {item.important && (
                          <span className="flex items-center gap-1 px-2 py-0.5 text-[10px] font-black text-white bg-red-500 rounded-full shadow-md">
                            <AlertTriangle className="w-3 h-3" /> 重要
                          </span>
                        )}
                      </div>
                      <h4 className="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug flex items-center gap-2">
                        {item.title}
                        {item.memberOnly && (
                          <Lock className="w-4 h-4 text-slate-400 flex-shrink-0" />
                        )}
                      </h4>
                    </div>

                    <div className="flex-shrink-0 flex items-center justify-end">
                      <div className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                        <ChevronRight className="w-5 h-5" />
                      </div>
                    </div>
                  </Link>
                )) : (
                  <div className="text-center py-20 bg-white/95 backdrop-blur-sm rounded-3xl border border-dashed border-white/30 text-slate-300">
                    該当する情報はありません
                  </div>
                )}

                <Link href="/archives/" className="w-full py-5 text-white font-black text-sm border-2 border-white/30 rounded-2xl bg-white/10 backdrop-blur-sm hover:bg-blue-600 hover:border-blue-600 transition-all flex items-center justify-center gap-2 mt-4 shadow-lg">
                  過去の情報をすべて見る <ChevronRight className="w-4 h-4" />
                </Link>
              </div>
            </div>

            {/* Sidebar Resources */}
            <div className="lg:w-1/3 space-y-6">
               {/* Video Library Card */}
               <div className="bg-blue-600/90 backdrop-blur-sm rounded-3xl p-8 text-white relative overflow-hidden group shadow-xl border border-white/20">
                  <PlayCircle className="absolute -right-4 -bottom-4 w-40 h-40 text-white/10 group-hover:scale-110 transition-transform duration-700" />
                  <div className="relative z-10">
                    <span className="bg-white/20 px-3 py-1 rounded-full text-[10px] font-black mb-4 inline-block">会員限定コンテンツ</span>
                    <h5 className="text-2xl font-black mb-4">動画ライブラリ</h5>
                    <div className="flex flex-wrap gap-2 mb-6">
                      <span className="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">GTムービー</span>
                      <span className="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">犯行動画</span>
                      <span className="bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold">検証動画</span>
                    </div>
                    <Link href="/movies/" className="w-full bg-slate-900 py-4 rounded-xl font-bold hover:bg-black transition-all flex items-center justify-center gap-2 shadow-xl">
                      動画ライブラリへ <PlayCircle className="w-5 h-5" />
                    </Link>
                  </div>
               </div>

               {/* Templates Card */}
               <div className="bg-white/95 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-lg">
                  <h5 className="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <FileText className="w-6 h-6 text-blue-500" />
                    書式・テンプレート
                  </h5>
                  <div className="space-y-2">
                    {[
                      'チェックシート',
                      'ハウスルール',
                      'フォーマット',
                      '基礎・理論',
                      '自主対策',
                      '検査マニュアル'
                    ].map((cat, idx) => (
                      <Link
                        key={idx}
                        href={`/templates/?category=${encodeURIComponent(cat)}`}
                        className="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100"
                      >
                        <div className="flex items-center gap-3">
                          <FileText className="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" />
                          <span className="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{cat}</span>
                        </div>
                        <ChevronRight className="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" />
                      </Link>
                    ))}
                  </div>
               </div>

               {/* Database Card */}
               <div className="bg-white/95 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-lg">
                  <h5 className="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <Database className="w-6 h-6 text-blue-500" />
                    データベース
                  </h5>
                  <div className="space-y-2">
                    <Link href="/prowler/" className="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
                      <div className="flex items-center gap-3">
                        <User className="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" />
                        <span className="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">不審者情報</span>
                      </div>
                      <ChevronRight className="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" />
                    </Link>
                    <Link href="/suspicious-vehicle/" className="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
                      <div className="flex items-center gap-3">
                        <Car className="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" />
                        <span className="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">不審車両情報</span>
                      </div>
                      <ChevronRight className="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" />
                    </Link>
                    <a href="http://gtnet.mobaqr.jp/index.php" target="_blank" rel="noopener noreferrer" className="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group border border-transparent hover:border-slate-100">
                      <div className="flex items-center gap-3">
                        <Database className="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" />
                        <span className="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">遊技機DB</span>
                      </div>
                      <ExternalLink className="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" />
                    </a>
                  </div>
               </div>

               {/* Contact Widget */}
               <div className="bg-slate-900/90 backdrop-blur-sm rounded-3xl p-8 text-white border border-white/20 shadow-xl">
                  <h5 className="text-xl font-black mb-4">緊急のご相談</h5>
                  <div className="space-y-4">
                    <div className="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/10">
                      <Phone className="w-6 h-6 text-blue-400" />
                      <div>
                        <p className="text-[10px] text-slate-400 font-bold tracking-widest">フリーダイヤル</p>
                        <p className="text-xl font-black">0120-189-510</p>
                      </div>
                    </div>
                    <Link href="/contact/" className="w-full bg-blue-600 py-4 rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/40 flex items-center justify-center gap-2">
                      <Mail className="w-5 h-5" />
                      メールでお問い合わせ
                    </Link>
                  </div>
               </div>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-slate-950 pt-24 pb-12 text-slate-500">
        <div className="container mx-auto px-4 md:px-6">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 mb-20">
            <div>
              <div className="mb-8">
                <img
                  src="/logo.png"
                  alt="株式会社ジーティネット"
                  className="h-12 w-auto brightness-0 invert"
                />
              </div>
              <p className="max-w-md text-sm leading-relaxed mb-8">
                株式会社ジーティネットは、パチンコ・スロット業界の健全な発展を願い、高度なセキュリティ技術と専門知識をもって不正に立ち向かうリスクマネジメント企業です。
              </p>
              <div className="flex gap-4">
                <a href="#" className="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                  <Mail className="w-5 h-5" />
                </a>
                <a href="#" className="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                  <Phone className="w-5 h-5" />
                </a>
              </div>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-3 gap-8">
              <div>
                <h6 className="text-white font-black text-xs tracking-[0.2em] mb-6">コンテンツ</h6>
                <ul className="space-y-4 text-xs font-bold">
                  <li><Link href="/archives/?category=gt-news" className="hover:text-white transition-colors">GTニュース</Link></li>
                  <li><Link href="/archives/?category=gt-mail" className="hover:text-white transition-colors">GTメール</Link></li>
                  <li><Link href="/archives/?category=industry" className="hover:text-white transition-colors">業界ニュース</Link></li>
                  <li><Link href="/archives/?category=victim" className="hover:text-white transition-colors">被害発生状況</Link></li>
                  <li><Link href="/archives/?category=topics" className="hover:text-white transition-colors">トピックス</Link></li>
                  <li><Link href="/archives/?category=column" className="hover:text-white transition-colors">コラム</Link></li>
                  <li><Link href="/templates/" className="hover:text-white transition-colors">資料・書式</Link></li>
                </ul>
              </div>
              <div>
                <h6 className="text-white font-black text-xs tracking-[0.2em] mb-6">事業案内</h6>
                <ul className="space-y-4 text-xs font-bold">
                  <li><Link href="/services/" className="hover:text-white transition-colors">検査・監査事業</Link></li>
                  <li><Link href="/services/patrol/" className="hover:text-white transition-colors">巡回・防犯指導</Link></li>
                  <li><Link href="/services/education/" className="hover:text-white transition-colors">教育・セミナー</Link></li>
                  <li><Link href="/services/information/" className="hover:text-white transition-colors">情報提供サービス</Link></li>
                  <li><Link href="/services/price/" className="hover:text-white transition-colors">料金表</Link></li>
                  <li><Link href="/products/" className="hover:text-white transition-colors">GT商品</Link></li>
                </ul>
              </div>
              <div className="col-span-2 md:col-span-1">
                <h6 className="text-white font-black text-xs tracking-[0.2em] mb-6">会社情報</h6>
                <ul className="space-y-4 text-xs font-bold">
                  <li><Link href="/firsttime/" className="hover:text-white transition-colors">はじめての方へ</Link></li>
                  <li><Link href="/company/" className="hover:text-white transition-colors">会社概要</Link></li>
                  <li><Link href="/company/philosophy/" className="hover:text-white transition-colors">経営理念</Link></li>
                  <li><Link href="/company/message/" className="hover:text-white transition-colors">代表挨拶</Link></li>
                  <li><Link href="/privacy/" className="hover:text-white transition-colors">プライバシー方針</Link></li>
                </ul>
              </div>
            </div>
          </div>

          <div className="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p className="text-[10px] font-black tracking-widest">
              © 2010 - 2026 株式会社ジーティネット All Rights Reserved.
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default App;
