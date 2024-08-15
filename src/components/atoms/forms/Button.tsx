import { Button as ChakraButton } from '@chakra-ui/react';
import { MouseEventHandler, ReactNode } from 'react';

interface ButtonProps {
  children?: ReactNode;
  type?: 'button' | 'submit';
  loading?: boolean;
  loadingText?: string;
  onClick?: MouseEventHandler<HTMLButtonElement>;
}

export function Button({
  children,
  type,
  loading,
  loadingText,
  onClick,
}: ButtonProps) {
  return (
    <ChakraButton
      colorScheme="blue"
      type={type || 'button'}
      isLoading={loading}
      loadingText={loadingText}
      onClick={onClick}
    >
      {children}
    </ChakraButton>
  );
}
