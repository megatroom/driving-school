import { Input, InputProps } from '@chakra-ui/react';
import { BaseFormControlProps, FormControl } from './FormControl';

interface InputTextProps extends BaseFormControlProps {
  onChange?: InputProps['onChange'];
  onBlur?: InputProps['onBlur'];
  value?: string;
  name: string;
}

export function InputText({
  onChange,
  onBlur,
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
        value={value}
        name={name}
      />
    </FormControl>
  );
}
