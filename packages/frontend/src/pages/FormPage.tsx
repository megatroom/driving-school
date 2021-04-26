import { FC } from 'react'
import { makeStyles } from '@material-ui/core/styles'
import Container from '@material-ui/core/Container'
import Alert from '@material-ui/lab/Alert'
import AlertTitle from '@material-ui/lab/AlertTitle'

import Panel, { PanelBody, PanelButton } from 'molecules/Panel'

const useStyles = makeStyles((theme) => ({
  root: {
    '& .Mui-error > input': {
      borderColor: 'red',
    },
  },
}))

interface Props {
  onCancel: () => void
  onSubmit: () => void
  title: string
  isLoading?: boolean
  validationError?: any
  customError?: Error
}

const FormPage: FC<Props> = ({
  onCancel,
  onSubmit,
  children,
  title,
  isLoading,
  validationError,
  customError,
}) => {
  const classes = useStyles()

  return (
    <form onSubmit={onSubmit} className={classes.root}>
      <Container maxWidth="md">
        <Panel
          title={title}
          isLoading={isLoading}
          renderActions={() => (
            <>
              <PanelButton onClick={onCancel} type="button" color="default">
                Cancelar
              </PanelButton>
              <PanelButton type="submit" color="primary">
                Salvar
              </PanelButton>
            </>
          )}
        >
          {customError && (
            <Alert severity="error">
              <AlertTitle>Erro</AlertTitle>
              {customError.message}
            </Alert>
          )}
          {validationError && (
            <Alert severity="warning">
              <AlertTitle>Atenção</AlertTitle>
              <ul>
                {Object.entries(validationError).map(
                  ([key, error]: Array<any>) => (
                    <li key={`warn-validation-${key}`}>{error.message}</li>
                  )
                )}
              </ul>
            </Alert>
          )}
          <PanelBody>{!isLoading && children}</PanelBody>
        </Panel>
      </Container>
    </form>
  )
}

export default FormPage
