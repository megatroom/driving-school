import { formatSimpleDate } from '@/helpers/dateTime';
import { castPriorityToUser, Notification } from '@/models/system';
import {
  Card,
  CardBody,
  Checkbox,
  Table,
  TableContainer,
  Tbody,
  Td,
  Th,
  Thead,
  Tr,
} from '@chakra-ui/react';
import { NotificationHeader } from './NotificationHeader';

interface NotificationListProps {
  notifications: Notification[];
}

export function NotificationList({ notifications }: NotificationListProps) {
  return (
    <Card>
      <NotificationHeader />
      <CardBody>
        <TableContainer>
          <Table variant="simple">
            <Thead>
              <Tr>
                <Th>Data</Th>
                <Th>Mensagem</Th>
                <Th>Prioridade</Th>
                <Th>Remetente</Th>
                <Th>Concluído?</Th>
              </Tr>
            </Thead>
            <Tbody>
              {notifications.map((notification) => (
                <Tr key={`notification-${notification.id}`}>
                  <Td>{formatSimpleDate(notification.createdAt)}</Td>
                  <Td>
                    <pre>{notification.message}</pre>
                  </Td>
                  <Td>{castPriorityToUser(notification.priority)}</Td>
                  <Td>{notification.sender}</Td>
                  <Td>
                    <Checkbox />
                  </Td>
                </Tr>
              ))}
            </Tbody>
          </Table>
        </TableContainer>
      </CardBody>
    </Card>
  );
}
