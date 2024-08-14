'use client';

import {
  Avatar,
  Box,
  Button,
  ButtonGroup,
  Divider,
  Flex,
  Heading,
  Icon,
  IconButton,
  Menu,
  MenuButton,
  MenuItem,
  MenuList,
  Spacer,
  useColorMode,
} from '@chakra-ui/react';

import { MdPowerSettingsNew } from 'react-icons/md';
import { ChevronDownIcon, MoonIcon, SunIcon } from '@chakra-ui/icons';
import { SystemModule } from '@/models/system';

import { Container } from '../atoms/layout/Container';

interface AppBarProps {
  systemModules: SystemModule[];
  logout?: () => void;
}

export function AppBar({ systemModules, logout }: AppBarProps) {
  const { colorMode, toggleColorMode } = useColorMode();
  const isDarkMode = colorMode === 'dark';

  return (
    <Box boxShadow="base">
      <Container>
        <Flex minWidth="max-content" alignItems="center" gap="2" padding={2.5}>
          <Box p="2">
            <Heading size="md">Auto Escola 4 Rodas</Heading>
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
      <Divider />
      <Container>
        <Box padding={2.5}>
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
        </Box>
      </Container>
    </Box>
  );
}
