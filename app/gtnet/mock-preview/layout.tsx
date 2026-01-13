import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "GT-NET | パチンコホール向け不正対策専門サイト",
  description: "最新の不正事例から実務マニュアルまで網羅した会員制専門サイト",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ja">
      <body>{children}</body>
    </html>
  );
}
