import { useNavigate } from 'react-router-dom'

import {
  postEmployeeRole,
  getEmployeeRole,
  EmployeeRole,
  putEmployeeRole,
} from 'services/api/employees'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import TextField from 'atoms/form/TextField'

export default function EmployeeRoleForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/employees/roles')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<EmployeeRole>({
    getModel: getEmployeeRole,
    putModel: putEmployeeRole,
    postModel: postEmployeeRole,
    onSuccess: goBack,
    entityName: 'Função',
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Função"
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
