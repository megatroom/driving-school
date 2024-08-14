'use client';

import { ReactNode } from 'react';

import { logout } from '@/services/auth';
import { SystemModule } from '@/models/system';
import { AppBar } from '@/components/molecules/AppBar';
import { PageBody } from '@/components/molecules/PageBody';

interface AdminLayoutProps {
  children: ReactNode;
  systemModules: SystemModule[];
}

export function AdminLayout({ systemModules, children }: AdminLayoutProps) {
  const handleLogout = () => {
    logout();
  };

  return (
    <>
      <AppBar systemModules={systemModules} logout={handleLogout} />
      <PageBody>{children}</PageBody>
    </>
  );
}
