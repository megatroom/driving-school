import { useNavigate } from 'routes/navigate'

import { User, getUser, postUser, putUser } from 'services/api/users'
import { getEmployees } from 'services/api/employees'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import TextField from 'atoms/form/TextField'
import ForeignField from 'atoms/form/ForeignField'

export default function StudentForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/users')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<User>({
    getModel: getUser,
    putModel: putUser,
    postModel: postUser,
    onSuccess: goBack,
    entityName: 'Usuário',
    adapters: {
      foreignKeys: ['employeeId'],
    },
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Usuário"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      <GridRow>
        <GridCell column={4}>
          <TextField
            error={validationError?.login}
            defaultValue={model?.login}
            control={control}
            id="login"
            label="Login"
            required
            autoFocus
          />
        </GridCell>
      </GridRow>
      <TextField
        error={validationError?.name}
        defaultValue={model?.name}
        control={control}
        disabled={isPosting}
        maxLength={100}
        id="name"
        label="Nome"
      />
      <ForeignField
        loadData={getEmployees}
        fieldKey="name"
        error={validationError?.employeeId}
        defaultLabel={model?.employeeName}
        defaultValue={model?.employeeId}
        control={control}
        disabled={isPosting}
        id="employeeId"
        label="Funcionário"
      />
      <TextField
        error={validationError?.observations}
        defaultValue={model?.observations}
        control={control}
        disabled={isPosting}
        id="observations"
        label="Observações"
        rows={4}
        multiline
      />
    </FormPage>
  )
}
