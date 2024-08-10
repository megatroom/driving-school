import type { Meta, StoryObj } from '@storybook/react';

import { Text } from './Text';

const meta = {
  component: Text,
  tags: ['autodocs'],
} satisfies Meta<typeof Text>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    children:
      'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam, officia repellendus. Expedita repudiandae maiores nemo atque dolor sed exercitationem consequatur beatae, rem ea accusantium distinctio ut alias iusto esse quos.',
  },
};
