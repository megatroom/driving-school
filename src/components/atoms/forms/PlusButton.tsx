'use client';

import { AddIcon } from '@chakra-ui/icons';
import { Button } from '@chakra-ui/react';
import { MouseEventHandler, ReactNode } from 'react';

interface PlusButtonProps {
  onClick?: MouseEventHandler<HTMLButtonElement>;
  children?: ReactNode;
}

export function PlusButton({ onClick, children }: PlusButtonProps) {
  return (
    <Button colorScheme="blue" leftIcon={<AddIcon />} onClick={onClick}>
      {children}
    </Button>
  );
}
