import { useNavigate } from 'routes/navigate'

import {
  postStudent,
  getStudent,
  Student,
  putStudent,
  getStudentOrigins,
} from 'services/api/students'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import AddressFields from 'organisms/AddressFields'
import FormDivider from 'atoms/form/FormDivider'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import TextField from 'atoms/form/TextField'
import ForeignField from 'atoms/form/ForeignField'
import DateField from 'atoms/form/DateField'
import SelectField from 'atoms/form/SelectField'
import PhoneField from 'atoms/form/PhoneField'
import CheckboxField from 'atoms/form/CheckboxField'
import CpfField from 'atoms/form/CpfField'
import { useEffect, useState } from 'react'

export default function StudentForm() {
  const [isNoEmail, toggleIsEmail] = useState(false)
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/students')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
    getValues,
    setValue,
  } = useCustomForm<Student>({
    getModel: getStudent,
    putModel: putStudent,
    postModel: postStudent,
    onSuccess: goBack,
    entityName: 'Aluno',
    adapters: {
      foreignKeys: ['originId'],
      dateFields: ['dateOfBirth', 'rgPrintDate', 'processExpiration'],
      readOnlyFields: ['enrollment'],
      booleanFields: ['noEmail'],
      phoneFields: [
        'phone',
        'phone2',
        'phone3',
        'mobile',
        'mobile2',
        'mobile3',
      ],
      cpfFields: ['cpf'],
    },
  })

  useEffect(() => {
    if (model?.noEmail === 'S') {
      toggleIsEmail(true)
    }
  }, [model?.noEmail])

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Aluno"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      <GridRow>
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
        <GridCell column={4}>
          <TextField
            error={validationError?.enrollmentcfc}
            defaultValue={model?.enrollmentcfc}
            control={control}
            id="enrollmentcfc"
            label="Matrícula CFC"
            autoFocus
          />
        </GridCell>
        <GridCell column={4}>
          <ForeignField
            loadData={getStudentOrigins}
            fieldKey="description"
            error={validationError?.originId}
            defaultLabel={model?.originDesc}
            defaultValue={model?.originId}
            control={control}
            disabled={isPosting}
            id="originId"
            label="Função"
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
        required
      />
      <GridRow>
        <GridCell column={4}>
          <DateField
            error={validationError?.dateOfBirth}
            defaultValue={model?.dateOfBirth}
            control={control}
            id="dateOfBirth"
            label="Data de Nascimento"
          />
        </GridCell>
        <GridCell column={4}>
          <SelectField
            options={[
              { value: 'M', label: 'Masculino' },
              { value: 'F', label: 'Feminino' },
            ]}
            error={validationError?.gender}
            defaultValue={model?.gender}
            control={control}
            disabled={isPosting}
            id="gender"
            label="Sexo"
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
          <TextField
            error={validationError?.rgEmittingOrgan}
            defaultValue={model?.rgEmittingOrgan}
            control={control}
            disabled={isPosting}
            id="rgEmittingOrgan"
            label="Orgão Emissor"
          />
        </GridCell>
        <GridCell column={4}>
          <DateField
            error={validationError?.rgPrintDate}
            defaultValue={model?.rgPrintDate}
            control={control}
            id="rgPrintDate"
            label="Data de Emissão"
          />
        </GridCell>
      </GridRow>
      <GridRow>
        <GridCell column={4}>
          <TextField
            error={validationError?.workCard}
            defaultValue={model?.workCard}
            control={control}
            disabled={isPosting}
            id="workCard"
            label="Carteira de Trabalho"
          />
        </GridCell>
      </GridRow>
      <TextField
        error={validationError?.father}
        defaultValue={model?.father}
        control={control}
        disabled={isPosting}
        maxLength={100}
        id="father"
        label="Nome do Pai"
      />
      <TextField
        error={validationError?.mother}
        defaultValue={model?.mother}
        control={control}
        disabled={isPosting}
        maxLength={100}
        id="mother"
        label="Nome da Mãe"
      />
      <FormDivider />
      <GridRow>
        <GridCell column={4}>
          <TextField
            error={validationError?.renach}
            defaultValue={model?.renach}
            control={control}
            disabled={isPosting}
            id="renach"
            label="Renach"
          />
        </GridCell>
        <GridCell column={4}>
          <TextField
            error={validationError?.regcnh}
            defaultValue={model?.regcnh}
            control={control}
            disabled={isPosting}
            id="regcnh"
            label="Nº Registro CNH"
          />
        </GridCell>
      </GridRow>
      <GridRow>
        <GridCell column={4}>
          <TextField
            error={validationError?.currentCategory}
            defaultValue={model?.currentCategory}
            control={control}
            disabled={isPosting}
            id="currentCategory"
            label="Categoria Atual"
          />
        </GridCell>
        <GridCell column={4}>
          <DateField
            error={validationError?.processExpiration}
            defaultValue={model?.processExpiration}
            control={control}
            disabled={isPosting}
            id="processExpiration"
            label="Validade do Processo"
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
      <GridRow>
        <GridCell column={4}>
          <PhoneField
            error={validationError?.phone}
            defaultValue={model?.phone}
            control={control}
            disabled={isPosting}
            id="phone"
            label="Telefone Principal"
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
            error={validationError?.phone2}
            defaultValue={model?.phone2}
            control={control}
            disabled={isPosting}
            id="phone2"
            label="Telefone Secundário"
          />
        </GridCell>
        <GridCell column={8}>
          <TextField
            error={validationError?.phone2Contact}
            defaultValue={model?.phone2Contact}
            control={control}
            disabled={isPosting}
            id="phone2Contact"
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
      <TextField
        error={validationError?.email}
        defaultValue={model?.email}
        control={control}
        disabled={isPosting || isNoEmail}
        id="email"
        label="E-mail"
        disableMarginBottom
      />
      <CheckboxField
        defaultValue={model?.noEmail}
        control={control}
        disabled={isPosting}
        onChange={(event, checked) => {
          if (checked) {
            setValue('email', '')
          }
          toggleIsEmail(checked)
        }}
        id="noEmail"
        label="Não possui e-mail"
      />
      <FormDivider />
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
