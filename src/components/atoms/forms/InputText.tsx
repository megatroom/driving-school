import {
  FormControl,
  FormLabel,
  FormErrorMessage,
  FormHelperText,
  Input,
} from '@chakra-ui/react';

interface InputTextProps {
  label: string;
  help?: string;
  error?: string;
}

export function InputText({ label, help, error }: InputTextProps) {
  const hasError = !!error;

  return (
    <FormControl isInvalid={hasError}>
      <FormLabel>{label}</FormLabel>
      <Input type="text" />
      {help && <FormHelperText>{help}</FormHelperText>}
      {hasError && <FormErrorMessage>{error}</FormErrorMessage>}
    </FormControl>
  );
}
