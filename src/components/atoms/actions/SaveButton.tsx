import { Button } from '@chakra-ui/react';

export interface SaveButtonProps {
  loading: boolean;
}

export function SaveButton({ loading }: SaveButtonProps) {
  return (
    <Button
      isLoading={loading}
      colorScheme="green"
      loadingText="Enviando..."
      type="submit"
    >
      Salvar
    </Button>
  );
}
