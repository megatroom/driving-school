import { useNavigate } from 'react-router-dom'

import { postCar, getCar, Car, putCar, getCarTypes } from 'services/api/cars'
import { formatDateToPayload } from 'formatters/date'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import ForeignField from 'atoms/form/ForeignField'
import TextField from 'atoms/form/TextField'
import YearField from 'atoms/form/YearField'
import DateField from 'atoms/form/DateField'

const formatModelToPayload = ({
  carTypeId,
  fixedEmployeeId,
  purchaseDate,
  saleDate,
  ...rest
}: any) => ({
  ...rest,
  carTypeId: carTypeId.value,
  fixedEmployeeId: fixedEmployeeId.value,
  purchaseDate: formatDateToPayload(purchaseDate),
  saleDate: formatDateToPayload(saleDate),
})

export default function CarForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/cars')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<Car>({
    getModel: getCar,
    putModel: (id, model) => {
      return putCar(id, formatModelToPayload(model))
    },
    postModel: (model) => {
      return postCar(formatModelToPayload(model))
    },
    onSuccess: goBack,
    entityName: 'Carro',
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Carro"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      <ForeignField
        loadData={getCarTypes}
        fieldKey="description"
        error={validationError?.carTypeId}
        defaultValue={{
          label: model?.carTypeDescription || '',
          value: model?.carTypeId || -1,
        }}
        control={control}
        disabled={isPosting}
        id="carTypeId"
        label="Tipo"
        required
      />
      <TextField
        error={validationError?.description}
        defaultValue={model?.description}
        control={control}
        disabled={isPosting}
        id="description"
        label="Descrição"
        required
      />
      <GridRow>
        <GridCell column={4}>
          <TextField
            error={validationError?.licensePlate}
            defaultValue={model?.licensePlate}
            control={control}
            disabled={isPosting}
            id="licensePlate"
            label="Placa"
            required
          />
        </GridCell>
        <GridCell column={4}>
          <YearField
            error={validationError?.year}
            defaultValue={model?.year}
            control={control}
            disabled={isPosting}
            id="year"
            label="Ano Fabricação"
            required
          />
        </GridCell>
        <GridCell column={4}>
          <YearField
            error={validationError?.modelYear}
            defaultValue={model?.modelYear}
            control={control}
            disabled={isPosting}
            id="modelYear"
            label="Ano Modelo"
            required
          />
        </GridCell>
      </GridRow>
      <GridRow>
        <GridCell column={4}>
          <DateField
            error={validationError?.purchaseDate}
            defaultValue={model?.purchaseDate}
            control={control}
            disabled={isPosting}
            id="purchaseDate"
            label="Data de compra"
            required
          />
        </GridCell>
        <GridCell column={4}>
          <DateField
            error={validationError?.saleDate}
            defaultValue={model?.saleDate}
            control={control}
            disabled={isPosting}
            id="saleDate"
            label="Data de venda"
          />
        </GridCell>
      </GridRow>
      <ForeignField
        loadData={getCarTypes}
        fieldKey="description"
        error={validationError?.fixedEmployeeId}
        defaultValue={{
          label: model?.fixedEmployeeName || '',
          value: model?.fixedEmployeeId || -1,
        }}
        control={control}
        disabled={isPosting}
        id="fixedEmployeeId"
        label="Instrutor fixo"
      />
    </FormPage>
  )
}
