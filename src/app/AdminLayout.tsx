'use client';

import { ReactNode } from 'react';

import { logout } from '@/services/auth';
import { SystemModule } from '@/models/system';
import { AppBar } from '@/components/molecules/AppBar';
import { PageBody } from '@/components/molecules/PageBody';
import { Box } from '@chakra-ui/react';
import { useTheme } from '@/hooks/useTheme';

interface AdminLayoutProps {
  children: ReactNode;
  systemModules: SystemModule[];
}

export function AdminLayout({ systemModules, children }: AdminLayoutProps) {
  const { bodyBgColor } = useTheme();

  const handleLogout = () => {
    logout();
  };

  return (
    <Box backgroundColor={bodyBgColor} height="100vh">
      <AppBar systemModules={systemModules} logout={handleLogout} />
      <PageBody>{children}</PageBody>
    </Box>
  );
}
