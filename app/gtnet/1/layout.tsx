import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'GT-NET | パチンコ業界のリスクマネジメント',
  description: 'ゴト対策からセキュリティ監査まで、現場主義のコンサルティングでホール経営を守ります。',
}

export default function GTNET1Layout({
  children,
}: {
  children: React.ReactNode
}) {
  return <>{children}</>
}
