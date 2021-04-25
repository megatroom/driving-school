import MaskedInput from 'react-text-mask'

import { formatPayloadDate } from 'formatters/date'
import TextField, { TextFieldProps } from './TextField'

interface TextMaskCustomProps {
  inputRef: any
}

function TextMaskCustom({ inputRef, ...other }: TextMaskCustomProps) {
  return (
    <MaskedInput
      {...other}
      ref={(ref: any) => {
        inputRef(ref ? ref.inputElement : null)
      }}
      mask={[/\d/, /\d/, ':', /\d/, /\d/]}
      placeholderChar={'\u2000'}
      showMask
    />
  )
}

export default function TimeField({ defaultValue, ...rest }: TextFieldProps) {
  return (
    <TextField
      {...rest}
      defaultValue={formatPayloadDate(defaultValue as string)}
      inputComponent={TextMaskCustom}
    />
  )
}
