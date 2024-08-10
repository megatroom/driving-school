'use client';

import {
  FormControl,
  FormLabel,
  FormErrorMessage,
  FormHelperText,
  Input,
} from '@chakra-ui/react';

interface InputPasswordProps {
  label: string;
  help?: string;
  error?: string;
}

export function InputPassword({ label, help, error }: InputPasswordProps) {
  const hasError = !!error;

  return (
    <FormControl isInvalid={hasError}>
      <FormLabel>{label}</FormLabel>
      <Input type="password" />
      {help && <FormHelperText>{help}</FormHelperText>}
      {hasError && <FormErrorMessage>{error}</FormErrorMessage>}
    </FormControl>
  );
}
