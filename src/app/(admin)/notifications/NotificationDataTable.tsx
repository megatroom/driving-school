'use client';

import { DataTable } from '@/components/molecules/DataTable';
import { formatSimpleDate } from '@/helpers/dateTime';
import {
  castNotificationStatusToText,
  castPriorityToText,
  Notification,
  NotificationStatus,
} from '@/models/system';
import { createColumnHelper } from '@tanstack/react-table';

interface NotificationDataTableProps {
  notifications: Notification[];
}

const columnHelper = createColumnHelper<Notification>();

const columns = [
  columnHelper.accessor('recipient', {
    cell: (info) => info.getValue().name,
    header: 'Remetente',
  }),
  columnHelper.accessor('sender', {
    cell: (info) => info.getValue().name,
    header: 'Destinatário',
  }),
  columnHelper.accessor('createdAt', {
    cell: (info) => formatSimpleDate(info.getValue()),
    header: 'Data',
  }),
  columnHelper.accessor('status', {
    cell: (info) =>
      castNotificationStatusToText(info.getValue() as NotificationStatus),
    header: 'Status',
  }),
  columnHelper.accessor('priority', {
    cell: (info) => castPriorityToText(info.getValue()),
    header: 'Prioridade',
  }),
];

export function NotificationDataTable({
  notifications,
}: NotificationDataTableProps) {
  return <DataTable columns={columns} data={notifications} />;
}
