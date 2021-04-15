import TextField, { TextFieldProps } from './TextField'

export default function LicensePlateField(props: TextFieldProps) {
  return <TextField {...props} maxLength={7} />
}
