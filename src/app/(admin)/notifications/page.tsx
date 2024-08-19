import { getAllNotifications } from '@/services/system';
import { PlusButton } from '@/components/atoms/actions/PlusButton';
import { PageHeading } from '@/components/molecules/PageHeading';
import { NotificationDataTable } from './NotificationDataTable';

export default async function NotificationsPage() {
  const notifications = await getAllNotifications();

  return (
    <>
      <PageHeading title="Avisos">
        <PlusButton>Novo Aviso</PlusButton>
      </PageHeading>
      <NotificationDataTable notifications={notifications} />
    </>
  );
}
