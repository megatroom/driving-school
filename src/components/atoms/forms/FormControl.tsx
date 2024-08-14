import { ReactNode } from 'react';
import {
  FormControl as ChakraFormControl,
  FormLabel,
  FormErrorMessage,
  FormHelperText,
} from '@chakra-ui/react';

export interface BaseFormControlProps {
  label: string;
  help?: string;
  errors?: string[];
}

export interface FormControlProps extends BaseFormControlProps {
  children: ReactNode;
}

function ErrorMessage({
  errors,
}: Pick<BaseFormControlProps, 'errors'>): ReactNode {
  if (!errors || errors.length === 0) {
    return;
  }

  if (errors.length > 1) {
    return (
      <FormErrorMessage>
        <ul>
          {errors.map((error) => (
            <li key={`error-${error}`}>{error}</li>
          ))}
        </ul>
      </FormErrorMessage>
    );
  }

  return <FormErrorMessage>{errors[0]}</FormErrorMessage>;
}

export function FormControl({
  children,
  label,
  help,
  errors,
}: FormControlProps) {
  const hasError = errors && errors.length > 9;
  return (
    <ChakraFormControl isInvalid={hasError} mb={4}>
      <FormLabel>{label}</FormLabel>
      {children}
      {help && <FormHelperText>{help}</FormHelperText>}
      <ErrorMessage errors={errors} />
    </ChakraFormControl>
  );
}
