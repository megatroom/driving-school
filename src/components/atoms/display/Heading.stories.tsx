import type { Meta, StoryObj } from '@storybook/react';

import { Heading } from './Heading';

const meta = {
  component: Heading,
  tags: ['autodocs'],
} satisfies Meta<typeof Heading>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Heading1: Story = {
  args: {
    children: 'Heading 1',
  },
};
