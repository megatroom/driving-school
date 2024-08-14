import { Button as ChakraButton } from '@chakra-ui/react';
import { ReactNode } from 'react';

interface ButtonProps {
  children: ReactNode;
  type?: 'button' | 'submit';
  loading?: boolean;
  loadingText?: string;
}

export function Button({ children, type, loading, loadingText }: ButtonProps) {
  return (
    <ChakraButton
      colorScheme="blue"
      type={type || 'button'}
      isLoading={loading}
      loadingText={loadingText}
    >
      {children}
    </ChakraButton>
  );
}
