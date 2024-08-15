'use client';

import { formatSimpleDate } from '@/helpers/dateTime';
import { useTheme } from '@/hooks/useTheme';
import {
  castPriorityToBadge,
  Notification,
  PriorityBadge,
} from '@/models/system';
import {
  Badge,
  Card,
  Checkbox,
  Table,
  TableContainer,
  Tbody,
  Td,
  Th,
  Thead,
  Tr,
} from '@chakra-ui/react';

interface NotificationListProps {
  notifications: Notification[];
}

export function NotificationList({ notifications }: NotificationListProps) {
  const { tableHeadColor } = useTheme();

  return (
    <Card>
      <TableContainer>
        <Table variant="simple">
          <Thead backgroundColor={tableHeadColor}>
            <Tr>
              <Th>Data</Th>
              <Th>Mensagem</Th>
              <Th>Prioridade</Th>
              <Th>Remetente</Th>
              <Th>Concluído?</Th>
            </Tr>
          </Thead>
          <Tbody>
            {notifications.map((notification) => {
              const priorityBadge: PriorityBadge = castPriorityToBadge(
                notification.priority,
              );
              return (
                <Tr key={`notification-${notification.id}`}>
                  <Td>{formatSimpleDate(notification.createdAt)}</Td>
                  <Td>
                    <pre>{notification.message}</pre>
                  </Td>
                  <Td>
                    <Badge colorScheme={priorityBadge.color}>
                      {priorityBadge.text}
                    </Badge>
                  </Td>
                  <Td>{notification.sender}</Td>
                  <Td>
                    <Checkbox />
                  </Td>
                </Tr>
              );
            })}
          </Tbody>
        </Table>
      </TableContainer>
    </Card>
  );
}
