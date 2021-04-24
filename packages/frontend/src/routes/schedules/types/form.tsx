import { useNavigate } from 'react-router-dom'

import {
  postSchedulingType,
  getSchedulingType,
  putSchedulingTypes,
  SchedulingType,
} from 'services/api/schedules'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import TextField from 'atoms/form/TextField'

export default function SchedulesRoleForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/schedules/types')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<SchedulingType>({
    getModel: getSchedulingType,
    putModel: putSchedulingTypes,
    postModel: postSchedulingType,
    onSuccess: goBack,
    entityName: 'Tipo de agendamento',
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Tipo de agendamento"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      <TextField
        error={validationError?.description}
        defaultValue={model?.description}
        control={control}
        disabled={isPosting}
        id="description"
        label="Descrição"
        required
        autoFocus
      />
    </FormPage>
  )
}
