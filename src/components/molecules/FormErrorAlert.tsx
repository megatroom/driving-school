import { Alert, AlertDescription, AlertIcon } from '@chakra-ui/react';
import { ReactNode } from 'react';

interface FormStateAlertProps {
  message?: string;
}

export function FormErrorAlert({ message }: FormStateAlertProps) {
  if (!message) {
    return;
  }

  return (
    <Alert status="error" mb={6}>
      <AlertIcon />
      <AlertDescription>{message}</AlertDescription>
    </Alert>
  );
}
