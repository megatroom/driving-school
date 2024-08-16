import { getUserSession } from '@/helpers/session';
import { getSystemModules } from '@/services/system';
import { AdminTemplate } from '@/components/templates/AdminTemplate';

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
