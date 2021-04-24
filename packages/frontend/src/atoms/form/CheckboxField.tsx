import { Controller } from 'react-hook-form'
import { makeStyles } from '@material-ui/core/styles'
import FormControlLabel from '@material-ui/core/FormControlLabel'
import Checkbox, { CheckboxProps } from '@material-ui/core/Checkbox'

interface Props extends CheckboxProps {
  control: any
  id: string
  label: string
}

const useStyles = makeStyles((theme) => ({
  controlBottom: {
    marginBottom: theme.spacing(3),
  },
}))

export default function CheckboxField({
  onChange,
  control,
  id,
  label,
  defaultValue,
  disabled,
}: Props) {
  const classes = useStyles()

  return (
    <Controller
      name={id}
      control={control}
      defaultValue={defaultValue === 'S'}
      render={({ onChange: onValueChange, value }) => (
        <FormControlLabel
          classes={{
            root: classes.controlBottom,
          }}
          control={
            <Checkbox
              id={id}
              checked={value}
              onChange={(event, checked) => {
                onValueChange(checked)
                onChange && onChange(event, checked)
              }}
              name={id}
              disabled={disabled}
              color="primary"
            />
          }
          label={label}
        />
      )}
    />
  )
}
