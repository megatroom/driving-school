'use client';

import { PAGE_BODY_SPACING } from '@/components/constants';
import { useTheme } from '@/hooks/useTheme';
import {
  TableContainer,
  Table as ChakraTable,
  Thead as ChakraThead,
  Tbody as ChakraTbody,
  Tr as ChakraTr,
  Th as ChakraTh,
  Td as ChakraTd,
  Card,
} from '@chakra-ui/react';
import { ReactNode } from 'react';

interface TableProps {
  children: ReactNode;
}

export function Table({ children }: TableProps) {
  return (
    <TableContainer>
      <ChakraTable
        variant="simple"
        __css={{ 'table-layout': 'fixed', width: 'full' }}
      >
        {children}
      </ChakraTable>
    </TableContainer>
  );
}

interface CardTableProps {
  children: ReactNode;
}

export function CardTable(props: CardTableProps) {
  return (
    <Card mb={PAGE_BODY_SPACING}>
      <Table {...props} />
    </Card>
  );
}

interface TheadProps {
  children: ReactNode;
  isColored?: boolean;
}

export function Thead({ children, isColored }: TheadProps) {
  const { tableHeadColor } = useTheme();

  return (
    <ChakraThead backgroundColor={isColored ? tableHeadColor : undefined}>
      {children}
    </ChakraThead>
  );
}

interface TbodyProps {
  children: ReactNode;
}

export function Tbody({ children }: TbodyProps) {
  return <ChakraTbody>{children}</ChakraTbody>;
}

interface TrProps {
  children: ReactNode;
}

export function Tr({ children }: TrProps) {
  return <ChakraTr>{children}</ChakraTr>;
}

interface ThProps {
  children: ReactNode;
  isCentered?: boolean;
}

export function Th({ children, isCentered }: ThProps) {
  return (
    <ChakraTh textAlign={isCentered ? 'center' : undefined}>
      {children}
    </ChakraTh>
  );
}

interface TdProps {
  children: ReactNode;
  isCentered?: boolean;
}

export function Td({ children, isCentered }: TdProps) {
  return (
    <ChakraTd textAlign={isCentered ? 'center' : undefined}>
      {children}
    </ChakraTd>
  );
}
