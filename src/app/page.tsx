import { NotificationList } from '@/components/organisms/NotificationList';
import {
  getNotificationsForRecipient,
  getSystemModules,
} from '@/services/system';
import { AdminLayout } from './AdminLayout';
import { ScheduleList } from '@/components/organisms/ScheduleList';

export default async function Home() {
  const systemModules = await getSystemModules();
  const notifications = await getNotificationsForRecipient(26);

  return (
    <AdminLayout systemModules={systemModules}>
      <NotificationList notifications={notifications} />
      <ScheduleList scheduleList={[]} />
    </AdminLayout>
  );
}
