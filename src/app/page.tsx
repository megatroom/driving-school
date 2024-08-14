import { AppBar } from '@/components/molecules/AppBar';
import { PageBody } from '@/components/molecules/PageBody';
import { NotificationList } from '@/components/organisms/NotificationList';
import { getNotifications, getSystemModules } from '@/services/system';

export default async function Home() {
  const systemModules = await getSystemModules();
  const notifications = await getNotifications(26);

  return (
    <div>
      <AppBar systemModules={systemModules} />
      <PageBody>
        <NotificationList notifications={notifications} />
      </PageBody>
    </div>
  );
}
