'use client';

import { AddIcon } from '@chakra-ui/icons';
import { Button } from '@chakra-ui/react';
import Link from 'next/link';
import { ReactNode } from 'react';

interface PlusButtonProps {
  children?: ReactNode;
  linkTo: string;
}

export function PlusButton({ linkTo, children }: PlusButtonProps) {
  return (
    <Button colorScheme="blue" leftIcon={<AddIcon />} as={Link} href={linkTo}>
      {children}
    </Button>
  );
}
