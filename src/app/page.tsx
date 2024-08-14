import { NotificationList } from '@/components/organisms/NotificationList';
import { getNotifications, getSystemModules } from '@/services/system';
import { AdminLayout } from './AdminLayout';

export default async function Home() {
  const systemModules = await getSystemModules();
  const notifications = await getNotifications(26);

  return (
    <AdminLayout systemModules={systemModules}>
      <NotificationList notifications={notifications} />
    </AdminLayout>
  );
}
