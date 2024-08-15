import type { Meta, StoryObj } from '@storybook/react';

import { InputPassword } from './InputPassword';

const meta = {
  component: InputPassword,
} satisfies Meta<typeof InputPassword>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  name: 'InputPassword',
  args: {
    label: 'Input Password',
    name: 'label',
  },
};
