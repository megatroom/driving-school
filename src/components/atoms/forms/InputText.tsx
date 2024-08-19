import { Input, InputProps } from '@chakra-ui/react';
import { BaseFormControlProps, FormControl } from './FormControl';

interface InputTextProps extends BaseFormControlProps {
  onChange?: InputProps['onChange'];
  onBlur?: InputProps['onBlur'];
  autoFocus?: boolean;
  value?: string;
  name: string;
}

export function InputText({
  onChange,
  onBlur,
  autoFocus,
  name,
  value,
  ...rest
}: InputTextProps) {
  return (
    <FormControl {...rest}>
      <Input
        type="text"
        onChange={onChange}
        onBlur={onBlur}
        autoFocus={autoFocus}
        value={value}
        name={name}
      />
    </FormControl>
  );
}
