import { useNavigate } from 'react-router-dom'

import { postCar, getCar, Car, putCar, getCarTypes } from 'services/api/cars'
import { getEmployees } from 'services/api/employees'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import ForeignField from 'atoms/form/ForeignField'
import TextField from 'atoms/form/TextField'
import YearField from 'atoms/form/YearField'
import DateField from 'atoms/form/DateField'
import LicensePlateField from 'atoms/form/LicensePlateField'

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
    putModel: putCar,
    postModel: postCar,
    onSuccess: goBack,
    entityName: 'Carro',
    adapters: {
      foreignKeys: ['carTypeId', 'fixedEmployeeId'],
      dateFields: ['purchaseDate', 'saleDate'],
    },
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
        defaultLabel={model?.carTypeDescription}
        defaultValue={model?.carTypeId}
        control={control}
        disabled={isPosting}
        id="carTypeId"
        label="Tipo"
        required
        autoFocus
      />
      <TextField
        error={validationError?.description}
        defaultValue={model?.description}
        control={control}
        disabled={isPosting}
        maxLength={100}
        id="description"
        label="Descrição"
        required
      />
      <GridRow>
        <GridCell column={4}>
          <LicensePlateField
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
        loadData={getEmployees}
        fieldKey="name"
        error={validationError?.fixedEmployeeId}
        defaultLabel={model?.fixedEmployeeName}
        defaultValue={model?.fixedEmployeeId}
        control={control}
        disabled={isPosting}
        id="fixedEmployeeId"
        label="Instrutor fixo"
      />
    </FormPage>
  )
}
