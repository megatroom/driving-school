import type { Metadata } from 'next';

import { fonts } from '../styles/fonts';
import { ClientProviders } from './ClientProviders';

export const metadata: Metadata = {
  title: 'Sistema de Autoescola',
  description: 'Sistema de Autoescola',
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="pt-BR">
      <body className={fonts.rubik.variable}>
        <ClientProviders>{children}</ClientProviders>
      </body>
    </html>
  );
}
