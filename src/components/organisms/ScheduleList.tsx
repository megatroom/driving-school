import { CardTable, Tbody, Td, Th, Thead, Tr } from '../atoms/display/Table';

interface ScheduleListProps {
  scheduleList: string[];
}

export function ScheduleList({ scheduleList }: ScheduleListProps) {
  return (
    <CardTable>
      <Thead isColored>
        <Tr>
          <Th>Aluno</Th>
          <Th>Tipo de Agendamento</Th>
          <Th isCentered>Data</Th>
          <Th isCentered>Hora</Th>
        </Tr>
      </Thead>
      <Tbody>Working in progress...</Tbody>
    </CardTable>
  );
}
