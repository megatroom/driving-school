import { useNavigate } from 'react-router-dom'

import { postCarType, getCarType, CarType, putCarType } from 'services/api/cars'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import TextField from 'atoms/form/TextField'
import MoneyField from 'atoms/form/MoneyField'

export default function CarsTypesForm() {
  const navigate = useNavigate()
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
    onSuccess: () => {
      navigate('/cars/types')
    },
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={() => {
        navigate('/cars/types')
      }}
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
        id="description"
        label="Descrição"
      />
      <MoneyField
        error={validationError?.commission}
        defaultValue={model?.commission}
        control={control}
        disabled={isPosting}
        id="commission"
        label="Comissão"
      />
    </FormPage>
  )
}
