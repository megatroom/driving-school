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
        /\d/,
        /\d/,
        /\d/,
        '.',
        /\d/,
        /\d/,
        /\d/,
        '.',
        /\d/,
        /\d/,
        /\d/,
        '-',
        /\d/,
        /\d/,
      ]}
      placeholderChar={'\u2000'}
      showMask
    />
  )
}

export default function CpfField(props: TextFieldProps) {
  return <TextField {...props} inputComponent={TextMaskCustom} />
}
