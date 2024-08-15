import type { Meta, StoryObj } from '@storybook/react';

import { FormControl } from './FormControl';

const meta = {
  component: FormControl,
} satisfies Meta<typeof FormControl>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  name: 'FormControl',
  args: {
    label: 'Field Label',
    children: <input type="text" />,
    help: 'Help text.',
    errors: ['Error message.'],
  },
};
