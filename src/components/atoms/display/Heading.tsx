import { ReactNode } from 'react';
import { Heading as ChakraHeading } from '@chakra-ui/react';

interface HeadingProps {
  children: ReactNode;
}

export function Heading({ children }: HeadingProps) {
  return <ChakraHeading>{children}</ChakraHeading>;
}
