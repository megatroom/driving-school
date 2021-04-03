import NumberFormat from 'react-number-format'

import TextField, { TextFieldProps } from './TextField'

interface NumberProps {
  inputRef?: any
  onChange?: (event: any) => void
}

function NumberFormatCustom({ inputRef, onChange, ...other }: NumberProps) {
  return (
    <NumberFormat
      {...other}
      getInputRef={inputRef}
      onValueChange={(values) => {
        onChange && onChange(values.floatValue)
      }}
      prefix=""
      maxLength={4}
      isNumericString
    />
  )
}

export default function YearField(props: TextFieldProps) {
  return <TextField {...props} inputComponent={NumberFormatCustom} />
}
