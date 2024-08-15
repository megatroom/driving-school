import type { Meta, StoryObj } from '@storybook/react';

import { Container } from './Container';
import { Text } from '../display/Text';

const meta = {
  component: Container,
} satisfies Meta<typeof Container>;

export default meta;

type Story = StoryObj<typeof meta>;

export const Default: Story = {
  name: 'Container',
  args: {
    children: (
      <div>
        <Text>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt,
          rerum. Eius beatae sapiente assumenda! Odio laudantium ab, asperiores
          excepturi vitae aliquid nam at? Nisi, expedita beatae. Alias nostrum
          temporibus cupiditate.
        </Text>
        <Text>
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Asperiores,
          voluptatem. Error culpa in blanditiis accusamus excepturi nulla
          doloremque impedit molestias deleniti iure. Explicabo fugiat odio
          maxime quos incidunt, ratione alias?
        </Text>
      </div>
    ),
  },
};
