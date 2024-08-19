import { expect } from '@storybook/test';
import { render, screen } from '@testing-library/react';

import { Button } from '../../actions/Button';
import userEvent from '@testing-library/user-event';

describe('<Button />', () => {
  it('should trigger onClick', async () => {
    const onClick = jest.fn();

    render(<Button onClick={onClick} />);

    await userEvent.click(screen.getByRole('button'));

    expect(onClick).toHaveBeenCalledTimes(1);
  });
});
