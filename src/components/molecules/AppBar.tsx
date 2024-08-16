'use client';

import {
  Avatar,
  Box,
  Button,
  ButtonGroup,
  Card,
  Flex,
  Heading,
  Icon,
  IconButton,
  Menu,
  MenuButton,
  MenuItem,
  MenuList,
  Spacer,
  Stack,
  StackDivider,
} from '@chakra-ui/react';

import { MdPowerSettingsNew } from 'react-icons/md';
import { ChevronDownIcon, MoonIcon, SunIcon } from '@chakra-ui/icons';
import { SystemModule } from '@/models/system';

import { Container } from '../atoms/layout/Container';
import { useTheme } from '@/hooks/useTheme';
import { Link } from '../atoms/navigation/Link';

interface AppBarProps {
  systemModules: SystemModule[];
  logout?: () => void;
}

export function AppBar({ systemModules, logout }: AppBarProps) {
  const { toggleColorMode, isDarkMode } = useTheme();

  return (
    <Card borderRadius={0} py={2}>
      <Stack divider={<StackDivider />} spacing={2.5}>
        <Container>
          <Flex minWidth="max-content" alignItems="center" gap="2">
            <Box p="2">
              <Link href="/">
                <Heading size="md">Auto Escola 4 Rodas</Heading>
              </Link>
            </Box>
            <Spacer />
            <ButtonGroup gap="2" alignItems="center">
              <IconButton
                isRound
                variant="ghost"
                aria-label="Dark Theme"
                fontSize="20px"
                onClick={toggleColorMode}
                icon={
                  isDarkMode ? (
                    <SunIcon color="gray.400" />
                  ) : (
                    <MoonIcon color="gray.400" />
                  )
                }
              />
              <Menu>
                <MenuButton
                  as={Avatar}
                  aria-label="Menu do usuário"
                  size="sm"
                  src="https://bit.ly/broken-link"
                  cursor="pointer"
                />
                <MenuList>
                  <MenuItem
                    icon={<Icon as={MdPowerSettingsNew} />}
                    onClick={logout}
                  >
                    Sair
                  </MenuItem>
                </MenuList>
              </Menu>
            </ButtonGroup>
          </Flex>
        </Container>
        <Container>
          {systemModules.map((systemModule) => (
            <Menu key={`system-module-${systemModule.id}`}>
              <MenuButton
                as={Button}
                variant="ghost"
                rightIcon={<ChevronDownIcon />}
              >
                {systemModule.description}
              </MenuButton>
              <MenuList>
                {systemModule.pages.map((page) => (
                  <MenuItem key={`system-module-${page.id}`}>
                    {page.name}
                  </MenuItem>
                ))}
              </MenuList>
            </Menu>
          ))}
        </Container>
      </Stack>
    </Card>
  );
}
