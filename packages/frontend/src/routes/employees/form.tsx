import { useNavigate } from 'react-router-dom'

import {
  postEmployee,
  getEmployee,
  getEmployeeRoles,
  Employee,
  putEmployee,
} from 'services/api/employees'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import AddressFields from 'organisms/AddressFields'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import FormDivider from 'atoms/form/FormDivider'
import TextField from 'atoms/form/TextField'
import DateField from 'atoms/form/DateField'
import ForeignField from 'atoms/form/ForeignField'
import SelectField from 'atoms/form/SelectField'
import PhoneField from 'atoms/form/PhoneField'
import CpfField from 'atoms/form/CpfField'

export default function EmployeeForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/employees')
  }
  const {
    handleSubmit,
    getValues,
    setValue,
    isLoading,
    isPosting,
    isEditingMode,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<Employee>({
    getModel: getEmployee,
    putModel: putEmployee,
    postModel: postEmployee,
    onSuccess: goBack,
    entityName: 'Funcionário',
    adapters: {
      foreignKeys: ['employeeRoleId'],
      dateFields: ['dateOfBirth'],
      readOnlyFields: ['enrollment'],
      phoneFields: ['phone', 'mobile', 'mobile2', 'mobile3'],
      cpfFields: ['cpf'],
    },
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Funcionário"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      <GridRow>
        {isEditingMode && (
          <GridCell column={4}>
            <TextField
              error={validationError?.enrollment}
              defaultValue={model?.enrollment}
              control={control}
              id="enrollment"
              label="Matrícula"
              disabled
            />
          </GridCell>
        )}
        <GridCell column={isEditingMode ? 8 : 12}>
          <TextField
            error={validationError?.name}
            defaultValue={model?.name}
            control={control}
            disabled={isPosting}
            maxLength={100}
            id="name"
            label="Nome"
            required
            autoFocus
          />
        </GridCell>
      </GridRow>
      <GridRow>
        <GridCell column={4}>
          <DateField
            error={validationError?.dateOfBirth}
            defaultValue={model?.dateOfBirth}
            control={control}
            disabled={isPosting}
            id="dateOfBirth"
            label="Data de nascimento"
          />
        </GridCell>
        <GridCell column={4}>
          <TextField
            error={validationError?.rg}
            defaultValue={model?.rg}
            control={control}
            disabled={isPosting}
            id="rg"
            label="RG"
          />
        </GridCell>
        <GridCell column={4}>
          <CpfField
            error={validationError?.cpf}
            defaultValue={model?.cpf}
            control={control}
            disabled={isPosting}
            id="cpf"
            label="CPF"
          />
        </GridCell>
      </GridRow>
      <GridRow>
        <GridCell column={4}>
          <ForeignField
            loadData={getEmployeeRoles}
            fieldKey="description"
            error={validationError?.employeeRoleId}
            defaultLabel={model?.employeeRoleDesc}
            defaultValue={model?.employeeRoleId}
            control={control}
            disabled={isPosting}
            id="employeeRoleId"
            label="Função"
          />
        </GridCell>
        <GridCell column={4}>
          <SelectField
            options={[
              { value: 'A', label: 'ATIVO' },
              { value: 'I', label: 'INATIVO' },
            ]}
            error={validationError?.status}
            defaultValue={isEditingMode ? model?.status : 'A'}
            control={control}
            disabled={isPosting}
            id="status"
            label="Status"
          />
        </GridCell>
      </GridRow>
      <FormDivider />
      <AddressFields
        getValues={getValues}
        setValue={setValue}
        validationError={validationError}
        model={model}
        control={control}
        isPosting={isPosting}
      />
      <FormDivider />
      <TextField
        error={validationError?.email}
        defaultValue={model?.email}
        control={control}
        disabled={isPosting}
        id="email"
        label="Email"
      />
      <GridRow>
        <GridCell column={4}>
          <PhoneField
            error={validationError?.phone}
            defaultValue={model?.phone}
            control={control}
            disabled={isPosting}
            id="phone"
            label="Telefone"
          />
        </GridCell>
        <GridCell column={8}>
          <TextField
            error={validationError?.phoneContact}
            defaultValue={model?.phoneContact}
            control={control}
            disabled={isPosting}
            id="phoneContact"
            label="Contato"
          />
        </GridCell>
      </GridRow>
      <GridRow>
        <GridCell column={4}>
          <PhoneField
            error={validationError?.mobile}
            defaultValue={model?.mobile}
            control={control}
            disabled={isPosting}
            id="mobile"
            label="Celular"
          />
        </GridCell>
        <GridCell column={4}>
          <PhoneField
            error={validationError?.mobile2}
            defaultValue={model?.mobile2}
            control={control}
            disabled={isPosting}
            id="mobile2"
            label="Celular 2"
          />
        </GridCell>
        <GridCell column={4}>
          <PhoneField
            error={validationError?.mobile3}
            defaultValue={model?.mobile3}
            control={control}
            disabled={isPosting}
            id="mobile3"
            label="Celular 3"
          />
        </GridCell>
      </GridRow>
    </FormPage>
  )
}
