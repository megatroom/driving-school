import { Controller } from 'react-hook-form'
import { fade, withStyles, makeStyles } from '@material-ui/core/styles'
import FormControl from '@material-ui/core/FormControl'
import InputLabel from '@material-ui/core/InputLabel'
import InputBase, {
  InputBaseComponentProps,
  InputBaseProps,
} from '@material-ui/core/InputBase'
import FormHelperText from '@material-ui/core/FormHelperText'

const CustomInput = withStyles((theme) => ({
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
  inputComponent?: React.ElementType<InputBaseComponentProps>
}

export default function TextField({
  error,
  control,
  label,
  id,
  inputComponent,
  defaultValue,
  ...rest
}: TextFieldProps) {
  const classes = useStyles()
  const hasError = !!error
  const errorId = `field-${id}-error-text`

  return (
    <FormControl error={hasError} className={classes.control} fullWidth>
      <InputLabel className={classes.label} htmlFor={id} shrink>
        {label}
      </InputLabel>
      <Controller
        name={id}
        control={control}
        defaultValue={defaultValue}
        render={({ onChange, value }) => (
          <CustomInput
            inputComponent={inputComponent}
            aria-describedby={errorId}
            onChange={onChange}
            value={value}
            id={id}
            fullWidth
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
