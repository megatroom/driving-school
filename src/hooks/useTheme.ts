import { useColorMode } from '@chakra-ui/react';
import { useMemo } from 'react';

interface UseThemeResult {
  toggleColorMode: () => void;
  isDarkMode: boolean;
  isLightMode: boolean;
  bodyBgColor: string | undefined;
  tableHeadColor: string;
}

export const useTheme = (): UseThemeResult => {
  const { colorMode, toggleColorMode } = useColorMode();

  const themeProps = useMemo(() => {
    const isDarkMode = colorMode === 'dark';
    const isLightMode = colorMode === 'light';

    return {
      isDarkMode,
      isLightMode,
      bodyBgColor: isLightMode ? '#F4F5FA' : undefined,
      tableHeadColor: isLightMode ? 'gray.50' : 'gray.600',
    };
  }, [colorMode]);

  return {
    toggleColorMode,
    ...themeProps,
  };
};
