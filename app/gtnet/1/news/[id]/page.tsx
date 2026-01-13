import React from 'react';
import Link from 'next/link';
import {
  Lock,
  ChevronLeft,
  LogIn,
  UserPlus,
  Phone,
  AlertTriangle,
} from 'lucide-react';

// サンプルニュースデータ（実際はAPIから取得）
const newsData = [
  { id: '1', date: "2026-01-05", category: "gt-news", type: "レポート", title: "GT-NET Incident report 2025 全編公開開始", memberOnly: true },
  { id: '2', date: "2026-01-05", category: "victim", type: "被害速報", title: "東京都：ぱちんこコーナーにおける不審な挙動の報告（11:00時点）", memberOnly: true },
  { id: '3', date: "2026-01-04", category: "topics", type: "トピックス", title: "テンプレート更新のお知らせ", memberOnly: false },
  { id: '4', date: "2026-01-01", category: "gt-news", type: "お知らせ", title: "【謹賀新年】新年のご挨拶と2026年度の活動方針について", memberOnly: true },
  { id: '5', date: "2025-12-28", category: "industry", type: "行政", title: "【警察庁】広告宣伝規制の解釈運用等について（通知）の周知依頼", memberOnly: true },
  { id: '6', date: "2025-12-25", category: "column", type: "コラム", title: "2025年を振り返って：ゴト被害の傾向と対策", memberOnly: false },
  { id: '7', date: "2025-12-20", category: "gt-mail", type: "メール", title: "【GTメール】年末年始の営業体制とゴト対策強化のお願い", memberOnly: true },
  { id: '8', date: "2025-12-17", category: "gt-news", type: "統計", title: "［2026年2月～4月］ 検定満了遊技機一覧をアップデート", memberOnly: true },
  { id: '9', date: "2025-12-15", category: "victim", type: "被害速報", title: "福岡県：スロットコーナーにてメダル不正流出の疑い（15:30）", memberOnly: true },
  { id: '10', date: "2025-12-11", category: "industry", type: "事件", title: "【大阪】景品交換所における強盗未遂事件の発生について", memberOnly: true },
  { id: '11', date: "2025-12-10", category: "gt-mail", type: "メール", title: "【GTメール】新型不正器具に関する緊急注意喚起", memberOnly: true },
  { id: '12', date: "2025-12-08", category: "topics", type: "トピックス", title: "【セミナー講師】福島県遊技業協同組合連合会にて講演", memberOnly: false },
  { id: '13', date: "2025-12-04", category: "gt-news", type: "資料", title: "ホール内巡回用チェックシートの最新版（2025.12版）配布開始", memberOnly: true },
  { id: '14', date: "2025-11-30", category: "column", type: "コラム", title: "より一層求められるリスクマネジメント", memberOnly: false },
  { id: '15', date: "2025-11-25", category: "topics", type: "トピックス", title: "【検証動画】スマパチ球抜き方法（ニューギン／RE:BOOST枠）", memberOnly: false },
];

export function generateStaticParams() {
  return newsData.map((news) => ({
    id: news.id,
  }));
}

const getCategoryLabel = (cat: string) => {
  switch(cat) {
    case 'gt-news': return 'GTニュース';
    case 'industry': return '業界ニュース';
    case 'victim': return '被害速報';
    case 'gt-mail': return 'GTメール';
    case 'topics': return 'トピックス';
    case 'column': return 'コラム';
    default: return 'ニュース';
  }
};

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

type Props = {
  params: Promise<{ id: string }>;
};

export default async function NewsDetailPage({ params }: Props) {
  const { id } = await params;
  const news = newsData.find(n => n.id === id);

  if (!news) {
    return (
      <div className="min-h-screen bg-slate-50 flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-slate-900 mb-4">記事が見つかりません</h1>
          <Link href="/gtnet/1" className="text-blue-600 hover:underline">トップページに戻る</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-900">
      {/* Header */}
      <header className="bg-white shadow-sm sticky top-0 z-50">
        <div className="container mx-auto px-4 py-4 flex justify-between items-center">
          <Link href="/gtnet/1" className="flex items-center">
            <img
              src="/gtnet/logo.png"
              alt="株式会社ジーティネット"
              className="h-10 w-auto"
            />
          </Link>
          <div className="flex items-center gap-4">
            <a href="#" className="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">
              <LogIn className="w-4 h-4" />
              会員ログイン
            </a>
          </div>
        </div>
      </header>

      {/* Breadcrumb */}
      <div className="bg-white border-b border-slate-100">
        <div className="container mx-auto px-4 py-3">
          <Link href="/gtnet/1" className="flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors">
            <ChevronLeft className="w-4 h-4" />
            トップページに戻る
          </Link>
        </div>
      </div>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-12">
        <div className="max-w-3xl mx-auto">
          {/* Article Header */}
          <article className="bg-white rounded-3xl shadow-xl overflow-hidden">
            {/* Category & Date */}
            <div className="bg-slate-900 px-8 py-6">
              <div className="flex flex-wrap items-center gap-3 mb-4">
                <span className={`px-4 py-1 text-xs font-black border rounded-full ${getCategoryColor(news.category)}`}>
                  {getCategoryLabel(news.category)}
                </span>
                <span className="px-4 py-1 text-xs font-black bg-white/10 text-white rounded-full">
                  {news.type}
                </span>
                {news.memberOnly && (
                  <span className="flex items-center gap-1 px-3 py-1 text-xs font-black text-amber-400 bg-amber-400/10 border border-amber-400/30 rounded-full">
                    <Lock className="w-3 h-3" /> 会員限定
                  </span>
                )}
              </div>
              <time className="text-slate-400 text-sm font-bold">{news.date}</time>
              <h1 className="text-2xl md:text-3xl font-black text-white mt-2 leading-tight">
                {news.title}
              </h1>
            </div>

            {/* Content */}
            <div className="p-8 md:p-12">
              {news.memberOnly ? (
                <div className="bg-slate-50 rounded-2xl p-8 md:p-12 text-center border-2 border-dashed border-slate-200">
                  <div className="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <Lock className="w-10 h-10 text-amber-600" />
                  </div>
                  <h2 className="text-2xl font-black text-slate-900 mb-4">
                    会員専用コンテンツ
                  </h2>
                  <p className="text-slate-600 mb-8 leading-relaxed max-w-md mx-auto">
                    このコンテンツは会員様専用となっております。<br />
                    コンテンツをご覧になる場合は会員登録またはログインが必要です。
                  </p>

                  <div className="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="#" className="flex items-center justify-center gap-2 bg-blue-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/20">
                      <LogIn className="w-5 h-5" />
                      会員ログイン
                    </a>
                    <a href="#" className="flex items-center justify-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-slate-800 transition-all">
                      <UserPlus className="w-5 h-5" />
                      新規会員登録
                    </a>
                  </div>

                  <div className="pt-8 border-t border-slate-200">
                    <p className="text-sm text-slate-500 mb-4">入会に関するお問い合わせ</p>
                    <div className="flex items-center justify-center gap-3 text-slate-900">
                      <Phone className="w-5 h-5 text-blue-500" />
                      <span className="text-xl font-black">0120-189-510</span>
                    </div>
                    <p className="text-xs text-slate-400 mt-2">年中無休・10:00〜17:00</p>
                  </div>
                </div>
              ) : (
                <div className="prose prose-slate max-w-none">
                  <p className="text-slate-600 leading-relaxed">
                    ※ これはモックページです。実際のコンテンツはCMSから取得されます。
                  </p>
                  <div className="mt-8 p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <p className="text-sm text-slate-500">
                      このコンテンツは公開コンテンツです。どなたでもご覧いただけます。
                    </p>
                  </div>
                </div>
              )}
            </div>
          </article>

          {/* Related Info */}
          <div className="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div className="flex items-start gap-4">
              <div className="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <AlertTriangle className="w-5 h-5 text-blue-600" />
              </div>
              <div>
                <h3 className="font-bold text-slate-900 mb-1">GT-NET会員サービスについて</h3>
                <p className="text-sm text-slate-600 leading-relaxed">
                  GT-NETの会員サービスでは、最新のゴト情報、被害速報、検証動画、各種テンプレートなど、
                  ホール経営に役立つ情報をいち早くお届けしています。
                </p>
              </div>
            </div>
          </div>
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
