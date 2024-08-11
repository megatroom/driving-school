import type { Preview } from '@storybook/react';
const theme = require('../src/styles/theme');

const preview: Preview = {
  parameters: {
    options: {
      storySort: {
        order: [
          'Driving School',
          'components',
          ['atoms', 'templates', 'pages'],
          '*',
        ],
      },
    },
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/i,
      },
    },
    chakra: {
      theme,
    },
  },
};

export default preview;
