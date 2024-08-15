import { Box } from '@chakra-ui/react';
import { ReactNode } from 'react';

interface ContainerProps {
  children: ReactNode;
}

export function Container({ children }: ContainerProps) {
  return (
    <Box
      maxW={{ base: 'xl', md: '7xl' }}
      px={{ base: '6', md: '8' }}
      w="full"
      mx="auto"
    >
      {children}
    </Box>
  );
}
