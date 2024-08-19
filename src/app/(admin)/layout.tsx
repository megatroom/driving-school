import { getUserSession } from '@/helpers/session';
import { getSystemModules } from '@/services/system';
import { AdminTemplate } from '@/components/templates/AdminTemplate';
import { Suspense } from 'react';

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const userSession = await getUserSession();
  const systemModules = await getSystemModules(userSession.id);

  return (
    <AdminTemplate systemModules={systemModules}>{children}</AdminTemplate>
  );
}
