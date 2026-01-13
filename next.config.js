/** @type {import('next').NextConfig} */
const nextConfig = {
  // WordPressディレクトリを除外
  pageExtensions: ['tsx', 'ts', 'jsx', 'js'],
  
  // Turbopackの設定（Next.js 16）
  turbopack: {
    // WordPressディレクトリを除外
    resolveAlias: {},
  },
}

module.exports = nextConfig
