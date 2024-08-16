'user client';

import {
  DaysLeft,
  daysLeftFromNow,
  formatSimpleDate,
} from '@/helpers/dateTime';
import {
  castPriorityToBadge,
  NotificationForRecipient,
  PriorityBadge,
} from '@/models/system';
import { Text } from '../atoms/display/Text';
import { CardTable, Tbody, Td, Th, Thead, Tr } from '../atoms/display/Table';
import { Badge, Checkbox } from '@chakra-ui/react';

interface NotificationListProps {
  notifications: NotificationForRecipient[];
}

export function NotificationList({ notifications }: NotificationListProps) {
  return (
    <CardTable>
      <Thead isColored>
        <Tr>
          <Th isCentered>Data</Th>
          <Th>Remetente</Th>
          <Th>Mensagem</Th>
          <Th isCentered>Prioridade</Th>
          <Th isCentered>Concluído?</Th>
        </Tr>
      </Thead>
      <Tbody>
        {notifications.map((notification) => {
          const priorityBadge: PriorityBadge = castPriorityToBadge(
            notification.priority,
          );
          const daysLeft: DaysLeft = daysLeftFromNow(notification.createdAt);
          return (
            <Tr key={`notification-${notification.id}`}>
              <Td isCentered>
                {formatSimpleDate(notification.createdAt)}
                <br />
                <Text
                  variant="table-subtext"
                  color={daysLeft.isPast ? 'red' : undefined}
                >
                  {daysLeft.text}
                </Text>
              </Td>
              <Td>{notification.sender}</Td>
              <Td>
                <pre>{notification.message}</pre>
              </Td>
              <Td isCentered>
                <Badge colorScheme={priorityBadge.color}>
                  {priorityBadge.text}
                </Badge>
              </Td>
              <Td isCentered>
                <Checkbox />
              </Td>
            </Tr>
          );
        })}
      </Tbody>
    </CardTable>
  );
}
