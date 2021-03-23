import { Controller } from 'react-hook-form'
import { fade, withStyles, makeStyles } from '@material-ui/core/styles'
import FormControl from '@material-ui/core/FormControl'
import InputLabel from '@material-ui/core/InputLabel'
import InputBase, { InputBaseProps } from '@material-ui/core/InputBase'

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

interface ITextField extends InputBaseProps {
  control: any
  label: string
  id: string
}

export default function TextField({ control, label, id, ...rest }: ITextField) {
  const classes = useStyles()

  return (
    <FormControl className={classes.control} fullWidth>
      <InputLabel className={classes.label} htmlFor={id} shrink>
        {label}
      </InputLabel>
      <Controller
        name={id}
        control={control}
        defaultValue=""
        render={({ onChange, value }) => (
          <CustomInput
            onChange={onChange}
            value={value}
            id={id}
            fullWidth
            {...rest}
          />
        )}
      />
    </FormControl>
  )
}
