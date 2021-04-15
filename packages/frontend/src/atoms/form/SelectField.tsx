import { Controller } from 'react-hook-form'
import { makeStyles } from '@material-ui/core/styles'
import FormControl from '@material-ui/core/FormControl'
import InputLabel from '@material-ui/core/InputLabel'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'

import { TextFieldProps, CustomInput } from './TextField'

const useStyles = makeStyles((theme) => ({
  control: {
    marginBottom: theme.spacing(3),
  },
  label: {
    color: '#212529',
  },
}))

interface SelectOption {
  label: string
  value: string | number
}

interface SelectFieldProps extends TextFieldProps {
  options: Array<SelectOption>
}

export default function SelectField({
  error,
  control,
  label,
  id,
  options,
  inputComponent,
  defaultValue,
  required,
  ...rest
}: SelectFieldProps) {
  const classes = useStyles()
  const hasError = !!error
  const errorId = `field-${id}-error-text`

  return (
    <FormControl error={hasError} className={classes.control} fullWidth>
      <InputLabel className={classes.label} htmlFor={id} shrink>
        {label + (required ? ' *' : '')}
      </InputLabel>
      <Controller
        name={id}
        control={control}
        defaultValue={defaultValue || ''}
        render={({ onChange, value }) => (
          <Select
            input={
              <CustomInput
                aria-describedby={errorId}
                onChange={onChange}
                value={value}
                id={id}
                fullWidth
                {...rest}
              />
            }
          >
            <MenuItem value=""></MenuItem>
            {options.map(({ label, value }) => (
              <MenuItem key={`select-${id}-option-${value}`} value={value}>
                {label}
              </MenuItem>
            ))}
          </Select>
        )}
      />
    </FormControl>
  )
}
