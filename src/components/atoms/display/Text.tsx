import { ReactNode } from 'react';
import { Text as ChakraText } from '@chakra-ui/react';

interface TextProps {
  children: ReactNode;
}

export function Text({ children }: TextProps) {
  return <ChakraText>{children}</ChakraText>;
}
