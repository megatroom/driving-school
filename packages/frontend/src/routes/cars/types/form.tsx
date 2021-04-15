import { useNavigate } from 'react-router-dom'

import { postCarType, getCarType, CarType, putCarType } from 'services/api/cars'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import TextField from 'atoms/form/TextField'
import MoneyField from 'atoms/form/MoneyField'

export default function CarTypeForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/cars/types')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<CarType>({
    getModel: getCarType,
    putModel: putCarType,
    postModel: postCarType,
    onSuccess: goBack,
    entityName: 'Tipo de Carro',
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Tipo de carro"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      <TextField
        error={validationError?.description}
        defaultValue={model?.description}
        control={control}
        disabled={isPosting}
        maxLength={100}
        id="description"
        label="Descrição"
        required
        autoFocus
      />
      <MoneyField
        error={validationError?.commission}
        defaultValue={model?.commission}
        control={control}
        disabled={isPosting}
        id="commission"
        label="Comissão"
        required
      />
    </FormPage>
  )
}
