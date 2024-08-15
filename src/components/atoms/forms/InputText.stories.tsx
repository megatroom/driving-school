import type { Meta, StoryObj } from '@storybook/react';

import { InputText } from './InputText';

const meta = {
  component: InputText,
} satisfies Meta<typeof InputText>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  name: 'InputText',
  args: {
    label: 'Input Text',
    help: 'Help Text',
    name: 'default',
  },
};
