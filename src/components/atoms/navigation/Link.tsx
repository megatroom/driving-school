'use client';

import { Link as NextLink } from '@chakra-ui/next-js';
import { ReactNode } from 'react';

interface LinkProps {
  children: ReactNode;
  href: string;
}

export function Link({ children, href }: LinkProps) {
  return (
    <NextLink _hover={{ color: 'blue.500' }} href={href}>
      {children}
    </NextLink>
  );
}
