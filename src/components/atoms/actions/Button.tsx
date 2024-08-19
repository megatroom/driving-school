import { Button as ChakraButton } from '@chakra-ui/react';
import { MouseEventHandler, ReactNode } from 'react';

export interface ButtonProps {
  as?: any;
  disabled?: boolean;
  children?: ReactNode;
  leftIcon?: React.ReactElement;
  rightIcon?: React.ReactElement;
  type?: 'button' | 'submit';
  loading?: boolean;
  loadingText?: string;
  colorScheme?: string;
  variant?: string;
  onClick?: MouseEventHandler<HTMLButtonElement>;
}

export function Button({
  as,
  type,
  loading,
  children,
  loadingText,
  leftIcon,
  rightIcon,
  disabled = false,
  variant = 'solid',
  colorScheme = 'blue',
  onClick,
}: ButtonProps) {
  return (
    <ChakraButton
      as={as}
      variant={variant}
      colorScheme={colorScheme}
      type={type || 'button'}
      leftIcon={leftIcon}
      rightIcon={rightIcon}
      isLoading={loading}
      loadingText={loadingText}
      disabled={disabled}
      aria-disabled={disabled}
      onClick={(...args) => {
        if (!disabled && !loading) {
          onClick?.(...args);
        }
      }}
    >
      {children}
    </ChakraButton>
  );
}
