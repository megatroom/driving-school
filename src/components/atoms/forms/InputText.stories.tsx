import type { Meta, StoryObj } from '@storybook/react';

import { InputText } from './InputText';

const meta = {
  component: InputText,
} satisfies Meta<typeof InputText>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    label: 'Label',
    help: 'Help Text',
    name: 'default',
  },
};

export const WithError: Story = {
  args: {
    label: 'Label',
    name: 'with-error',
    errors: ['This field is required.'],
  },
};
