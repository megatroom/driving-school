'use client';

import { BellIcon } from '@chakra-ui/icons';
import { CardHeader, Heading } from '@chakra-ui/react';

export function NotificationHeader() {
  return (
    <CardHeader>
      <Heading size="md">
        <BellIcon /> Avisos
      </Heading>
    </CardHeader>
  );
}
