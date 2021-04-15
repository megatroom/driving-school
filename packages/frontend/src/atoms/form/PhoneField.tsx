import MaskedInput from 'react-text-mask'

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
      mask={[
        '(',
        /[1-9]/,
        /\d/,
        ')',
        ' ',
        /\d/,
        /\d/,
        /\d/,
        /\d/,
        '-',
        /\d/,
        /\d/,
        /\d/,
        /\d/,
        /\d/,
      ]}
      placeholderChar={'\u2000'}
      showMask
    />
  )
}

export default function PhoneField(props: TextFieldProps) {
  return <TextField {...props} inputComponent={TextMaskCustom} />
}
