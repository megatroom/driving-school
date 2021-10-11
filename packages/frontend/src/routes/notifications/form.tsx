import { useNavigate } from 'react-router-dom'

import {
  getNotification,
  postNotification,
  putNotification,
  Notification,
} from 'services/api/notifications'
import { getUsers } from 'services/api/users'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import DateField from 'atoms/form/DateField'
import SelectField from 'atoms/form/SelectField'
import ForeignField from 'atoms/form/ForeignField'

export default function SchedulesRoleForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/notifications')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<Notification>({
    getModel: getNotification,
    putModel: putNotification,
    postModel: postNotification,
    onSuccess: goBack,
    entityName: 'Aviso',
    adapters: {
      foreignKeys: ['senderId'],
      dateFields: ['date'],
    },
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Aviso"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      continuar daqui
      <ForeignField
        loadData={getUsers}
        fieldKey="name"
        error={validationError?.senderId}
        defaultLabel={model?.senderName}
        defaultValue={model?.senderId}
        control={control}
        disabled={isPosting}
        id="senderId"
        label="Usuário (que vai receber o aviso)"
        autoFocus
        required
      />
      <GridRow>
        <GridCell column={4}>
          <DateField
            error={validationError?.date}
            defaultValue={model?.date}
            control={control}
            disabled={isPosting}
            id="date"
            label="Data"
            required
          />
        </GridCell>
        <GridCell column={4}></GridCell>
        <GridCell column={4}>
          <SelectField
            options={[
              { value: 'N', label: 'Não se Aplica' },
              { value: 'A', label: 'Aprovado' },
              { value: 'C', label: 'Cancelado Aluno' },
              { value: 'F', label: 'Falta' },
              { value: 'T', label: 'Retirado' },
              { value: 'M', label: 'Não Marcado' },
              { value: 'R', label: 'Reprovado' },
            ]}
            error={validationError?.status}
            defaultValue={model?.status}
            control={control}
            disabled={isPosting}
            id="approved"
            label="Status"
          />
        </GridCell>
      </GridRow>
    </FormPage>
  )
}
