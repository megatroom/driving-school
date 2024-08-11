import type { Meta, StoryObj } from '@storybook/react';

import { AuthTemplate } from './AuthTemplate';

const meta = {
  component: AuthTemplate,
} satisfies Meta<typeof AuthTemplate>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  name: 'AuthTemplate',
  args: {
    children: '',
    title: 'Auth Title',
  },
};
