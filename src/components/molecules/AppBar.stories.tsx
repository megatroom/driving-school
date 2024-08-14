import type { Meta, StoryObj } from '@storybook/react';

import { AppBar } from './AppBar';

const meta = {
  component: AppBar,
} satisfies Meta<typeof AppBar>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  name: 'AppBar',
  args: {},
};
