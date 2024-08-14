'use client';

import { Input, InputProps } from '@chakra-ui/react';
import { BaseFormControlProps, FormControl } from './FormControl';

interface InputPasswordProps extends BaseFormControlProps {
  onChange?: InputProps['onChange'];
  onBlur?: InputProps['onBlur'];
  value?: string;
  name: string;
}

export function InputPassword({
  onChange,
  onBlur,
  name,
  value,
  ...rest
}: InputPasswordProps) {
  return (
    <FormControl {...rest}>
      <Input
        type="password"
        onChange={onChange}
        onBlur={onBlur}
        value={value}
        name={name}
      />
    </FormControl>
  );
}
