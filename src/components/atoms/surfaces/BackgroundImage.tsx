'use client';

import { Box } from '@chakra-ui/react';
import { ReactNode } from 'react';

interface BackgroundImageProps {
  children: ReactNode;
  backgroundImageUrl?: string;
}

export function BackgroundImage({
  backgroundImageUrl,
  children,
}: BackgroundImageProps) {
  return (
    <Box
      backgroundImage={`url("${backgroundImageUrl}")`}
      backgroundPosition="center"
      backgroundRepeat="no-repeat"
      backgroundSize="cover"
      backgroundColor="gray.300"
      display="flex"
      alignItems="center"
      justifyContent="center"
      height="100vh"
    >
      {children}
    </Box>
  );
}
