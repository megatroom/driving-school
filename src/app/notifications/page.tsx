import { AdminLayout } from '../AdminLayout';
import { getAllNotifications, getSystemModules } from '@/services/system';
import { PlusButton } from '@/components/atoms/forms/PlusButton';
import { PageHeading } from '@/components/molecules/PageHeading';
import { NotificationDataTable } from './NotificationDataTable';

export default async function page() {
  const systemModules = await getSystemModules();
  const notifications = await getAllNotifications();

  return (
    <AdminLayout systemModules={systemModules}>
      <PageHeading title="Avisos">
        <PlusButton>Novo Aviso</PlusButton>
      </PageHeading>
      <NotificationDataTable notifications={notifications} />
    </AdminLayout>
  );
}
