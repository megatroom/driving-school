import { ReactNode } from 'react';
import { Container } from '../atoms/layout/Container';
import { Box } from '@chakra-ui/react';

interface PageBodyProps {
  children: ReactNode;
}

export function PageBody({ children }: PageBodyProps) {
  return (
    <Box as="main" py={6}>
      <Container>{children}</Container>
    </Box>
  );
}
