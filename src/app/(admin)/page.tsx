import { NotificationList } from '@/components/organisms/NotificationList';
import { getNotificationsForRecipient } from '@/services/system';
import { ScheduleList } from '@/components/organisms/ScheduleList';

export default async function Home() {
  const notifications = await getNotificationsForRecipient(26);

  return (
    <>
      <NotificationList notifications={notifications} />
      <ScheduleList scheduleList={[]} />
    </>
  );
}
