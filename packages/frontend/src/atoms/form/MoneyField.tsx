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
        onChange && onChange({ target: { value: values.floatValue } })
      }}
      prefix="R$ "
      thousandSeparator="."
      decimalSeparator=","
      isNumericString
    />
  )
}

export default function MoneyField(props: TextFieldProps) {
  return <TextField {...props} inputComponent={NumberFormatCustom} />
}
