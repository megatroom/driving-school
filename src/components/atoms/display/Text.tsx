import { ReactNode } from 'react';
import { Text as ChakraText } from '@chakra-ui/react';

interface TextProps {
  children: ReactNode;
  variant?: 'table-subtext';
  color?: string;
}

export function Text({ children, variant, color }: TextProps) {
  switch (variant) {
    case 'table-subtext':
      return (
        <ChakraText as="sub" color={color}>
          {children}
        </ChakraText>
      );
    default:
      return <ChakraText color={color}>{children}</ChakraText>;
  }
}
