import { useNavigate } from 'routes/navigate'

import {
  postStudentOrigin,
  getStudentOrigin,
  StudentOrigin,
  putStudentOrigin,
} from 'services/api/students'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import TextField from 'atoms/form/TextField'

export default function StudentOriginForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/students/origins')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<StudentOrigin>({
    getModel: getStudentOrigin,
    putModel: putStudentOrigin,
    postModel: postStudentOrigin,
    onSuccess: goBack,
    entityName: 'Origem',
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Origem"
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
