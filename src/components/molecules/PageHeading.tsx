import { Heading, VStack } from '@chakra-ui/react';
import { ReactNode } from 'react';
import { PAGE_BODY_SPACING } from '../constants';

interface PageHeadingProps {
  children: ReactNode;
  title: string;
}

export function PageHeading({ title, children }: PageHeadingProps) {
  return (
    <VStack
      spacing={4}
      mb={PAGE_BODY_SPACING}
      direction="row"
      align="flex-start"
    >
      <Heading>{title}</Heading>
      {children}
    </VStack>
  );
}
