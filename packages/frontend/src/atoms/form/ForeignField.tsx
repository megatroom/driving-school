import { useEffect, useState } from 'react'
import { Controller } from 'react-hook-form'
import { fade, makeStyles } from '@material-ui/core/styles'
import debounce from 'lodash.debounce'
import TextField from '@material-ui/core/TextField'
import Autocomplete from '@material-ui/lab/Autocomplete'
import CircularProgress from '@material-ui/core/CircularProgress'

import { Pagination } from 'services/api/client'

const useStyles = makeStyles((theme) => ({
  root: {
    marginBottom: theme.spacing(3),
    width: '100% !important',
  },
  inputRoot: {
    'label + &': {
      marginTop: theme.spacing(3),
    },
    borderRadius: 2,
    position: 'relative',
    backgroundColor: theme.palette.common.white,
    border: '1px solid #97A1AC',
    padding: '10px 12px',
    paddingBottom: '10px !important',
    transition: theme.transitions.create(['border-color', 'box-shadow']),
    '&.Mui-focused': {
      boxShadow: `${fade(theme.palette.primary.main, 0.25)} 0 0 0 0.2rem`,
      borderColor: theme.palette.primary.main,
    },
    '&.Mui-disabled': {
      backgroundColor: '#e9ecef',
    },
    '&.Mui-error': {
      borderColor: '#f44336',
    },
  },
  input: {
    fontSize: 16,
    paddingTop: '0 !important',
    paddingBottom: '0 !important',
  },
  label: {
    color: '#212529',
  },
}))

interface Option {
  label: string
  value: string | number
}

interface Props {
  loadData: (obj: Pagination) => Promise<any>
  fieldKey: string
  error?: any
  control: any
  label: string
  id: string
  disabled: boolean
  defaultLabel?: string
  defaultValue?: number
  required?: boolean
  autoFocus?: boolean
}

export default function ForeignField({
  loadData,
  fieldKey,
  error,
  control,
  label,
  id,
  disabled,
  defaultLabel,
  defaultValue,
  required,
  autoFocus,
}: Props) {
  const [open, setOpen] = useState(false)
  const [options, setOptions] = useState<Option[]>([])
  const [inputValue, setInputValue] = useState('')
  const [loading, setLoading] = useState(false)
  const classes = useStyles()
  const hasError = !!error

  useEffect(() => {
    setLoading(true)
    ;(async () => {
      const { data } = await loadData({
        page: 1,
        perPage: 5,
        order: fieldKey,
        search: inputValue,
      })

      setLoading(false)
      setOptions([
        { label: '', value: -1 },
        ...(data.map((item: any) => ({
          label: item[fieldKey],
          value: item.id,
        })) as Option[]),
      ])
    })()
  }, [inputValue, fieldKey, loadData])

  return (
    <Controller
      name={id}
      control={control}
      defaultValue={{
        label: defaultLabel || '',
        value: defaultValue || -1,
      }}
      render={({ onChange, value }) => (
        <Autocomplete
          noOptionsText="Não encontrado"
          id={id}
          style={{ width: 300 }}
          classes={{
            root: classes.root,
            inputRoot: classes.inputRoot,
            input: classes.input,
          }}
          open={open}
          onOpen={() => {
            setOpen(true)
          }}
          onClose={() => {
            setOpen(false)
          }}
          getOptionSelected={(option, value) => option.value === value.value}
          getOptionLabel={(option) => option.label}
          onChange={(event, newValue) => {
            onChange(newValue)
          }}
          onInputChange={debounce((event, newInputValue) => {
            setInputValue(newInputValue)
          }, 400)}
          value={value}
          disabled={disabled}
          options={options}
          loading={loading}
          renderInput={(params) => (
            <TextField
              {...params}
              label={label + (required ? ' *' : '')}
              helperText={error && error.message}
              error={hasError}
              InputProps={{
                ...params.InputProps,
                endAdornment: (
                  <>
                    {loading ? (
                      <CircularProgress color="inherit" size={20} />
                    ) : null}
                    {params.InputProps.endAdornment}
                  </>
                ),
                disableUnderline: true,
                autoFocus,
              }}
              InputLabelProps={{
                ...params.InputLabelProps,
                shrink: true,
                className: classes.label,
              }}
            />
          )}
        />
      )}
    />
  )
}
