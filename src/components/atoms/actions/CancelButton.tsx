import { Button } from '@chakra-ui/react';
import Link from 'next/link';

export interface CancelButtonProps {
  linkTo: string;
}

export function CancelButton({ linkTo }: CancelButtonProps) {
  return (
    <Button colorScheme="gray" variant="outline" as={Link} href={linkTo}>
      Cancelar
    </Button>
  );
}
