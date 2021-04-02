import { PropsWithChildren } from 'react'
import Button from '@material-ui/core/Button'
import Dialog from '@material-ui/core/Dialog'
import DialogActions from '@material-ui/core/DialogActions'
import DialogContent from '@material-ui/core/DialogContent'
import DialogContentText from '@material-ui/core/DialogContentText'
import DialogTitle from '@material-ui/core/DialogTitle'

interface Props {
  onCancel: () => void
  onConfirm: () => void
  id: string
  open: boolean
}

export default function ConfirmDialog({
  onCancel,
  onConfirm,
  id,
  open,
  children,
}: PropsWithChildren<Props>) {
  return (
    <Dialog
      open={open}
      onClose={onCancel}
      aria-labelledby={`${id}-dialog-title`}
      aria-describedby={`${id}-dialog-description`}
    >
      <DialogTitle id={`${id}-dialog-title`}>Confirmação</DialogTitle>
      <DialogContent>
        <DialogContentText id={`${id}-dialog-description`}>
          {children}
        </DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button onClick={onCancel} color="default">
          Cancelar
        </Button>
        <Button onClick={onConfirm} color="primary" autoFocus>
          Confirmar
        </Button>
      </DialogActions>
    </Dialog>
  )
}
