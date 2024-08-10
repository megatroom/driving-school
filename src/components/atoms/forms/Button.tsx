import { Button as ChakraButton } from '@chakra-ui/react';
import { ReactNode } from 'react';

interface ButtonProps {
  children: ReactNode;
}

export function Button({ children }: ButtonProps) {
  return <ChakraButton colorScheme="blue">{children}</ChakraButton>;
}
