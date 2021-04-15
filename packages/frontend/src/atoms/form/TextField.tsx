import { Controller } from 'react-hook-form'
import { fade, withStyles, makeStyles } from '@material-ui/core/styles'
import FormControl from '@material-ui/core/FormControl'
import InputLabel from '@material-ui/core/InputLabel'
import InputBase, { InputBaseProps } from '@material-ui/core/InputBase'
import FormHelperText from '@material-ui/core/FormHelperText'

export const CustomInput = withStyles((theme) => ({
  root: {
    'label + &': {
      marginTop: theme.spacing(3),
    },
  },
  input: {
    borderRadius: 2,
    position: 'relative',
    backgroundColor: theme.palette.common.white,
    border: '1px solid #97A1AC',
    fontSize: 16,
    padding: '10px 12px',
    transition: theme.transitions.create(['border-color', 'box-shadow']),
    '&:focus': {
      boxShadow: `${fade(theme.palette.primary.main, 0.25)} 0 0 0 0.2rem`,
      borderColor: theme.palette.primary.main,
    },
    '&:disabled': {
      backgroundColor: '#e9ecef',
    },
  },
}))(InputBase)

const useStyles = makeStyles((theme) => ({
  control: {
    marginBottom: theme.spacing(3),
  },
  label: {
    color: '#212529',
  },
}))

export interface TextFieldProps extends InputBaseProps {
  error?: any
  control: any
  label: string
  id: string
  inputComponent?: any
  required?: boolean
  maxLength?: number
  disableAutoUppercase?: boolean
}

export default function TextField({
  onChange,
  error,
  control,
  label,
  id,
  inputComponent,
  defaultValue,
  required,
  maxLength,
  disableAutoUppercase,
  ...rest
}: TextFieldProps) {
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
        render={({ onChange: onValueChange, value }) => (
          <CustomInput
            inputComponent={inputComponent}
            aria-describedby={errorId}
            onChange={(event) => {
              // const newValue = event.target.value
              // if (newValue && newValue.toUpperCase && !disableAutoUppercase) {
              //   onValueChange(newValue.toUpperCase())
              // } else {
              //   onValueChange(newValue)
              // }
              onValueChange(event.target.value)
              onChange && onChange(event)
            }}
            value={value}
            id={id}
            fullWidth
            inputProps={{
              maxLength: maxLength,
            }}
            {...rest}
          />
        )}
      />
      {hasError && (
        <FormHelperText id={errorId}>{error.message}</FormHelperText>
      )}
    </FormControl>
  )
}
