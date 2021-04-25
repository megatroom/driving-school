import { useNavigate } from 'react-router-dom'

import {
  getSchedulingTypes,
  getScheduling,
  postScheduling,
  putScheduling,
  Scheduling,
} from 'services/api/schedules'
import { getStudents } from 'services/api/students'
import useCustomForm from 'hooks/useCustomForm'
import FormPage from 'pages/FormPage'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import DateField from 'atoms/form/DateField'
import TimeField from 'atoms/form/TimeField'
import SelectField from 'atoms/form/SelectField'
import ForeignField from 'atoms/form/ForeignField'

export default function SchedulesRoleForm() {
  const navigate = useNavigate()
  const goBack = () => {
    navigate('/schedules')
  }
  const {
    handleSubmit,
    isLoading,
    isPosting,
    validationError,
    customError,
    control,
    model,
  } = useCustomForm<Scheduling>({
    getModel: getScheduling,
    putModel: putScheduling,
    postModel: postScheduling,
    onSuccess: goBack,
    entityName: 'Agendamento',
    adapters: {
      foreignKeys: ['studentId', 'schedulingTypeId'],
      dateFields: ['date'],
    },
  })

  return (
    <FormPage
      onSubmit={handleSubmit}
      onCancel={goBack}
      title="Agendamento"
      isLoading={isLoading}
      validationError={validationError}
      customError={customError}
    >
      <ForeignField
        loadData={getStudents}
        fieldKey="name"
        error={validationError?.studentId}
        defaultLabel={model?.studentName}
        defaultValue={model?.studentId}
        control={control}
        disabled={isPosting}
        id="studentId"
        label="Aluno"
        autoFocus
        required
      />
      <ForeignField
        loadData={getSchedulingTypes}
        fieldKey="description"
        error={validationError?.schedulingTypeId}
        defaultLabel={model?.description}
        defaultValue={model?.schedulingTypeId}
        control={control}
        disabled={isPosting}
        id="schedulingTypeId"
        label="Tipo de agendamento"
        required
      />
      <GridRow>
        <GridCell column={4}>
          <DateField
            error={validationError?.date}
            defaultValue={model?.date}
            control={control}
            disabled={isPosting}
            id="date"
            label="Data"
            required
          />
        </GridCell>
        <GridCell column={4}>
          <TimeField
            error={validationError?.time}
            defaultValue={model?.time}
            control={control}
            disabled={isPosting}
            id="time"
            label="Hora"
            required
          />
        </GridCell>
        <GridCell column={4}>
          <SelectField
            options={[
              { value: 'N', label: 'Não se Aplica' },
              { value: 'A', label: 'Aprovado' },
              { value: 'C', label: 'Cancelado Aluno' },
              { value: 'F', label: 'Falta' },
              { value: 'T', label: 'Retirado' },
              { value: 'M', label: 'Não Marcado' },
              { value: 'R', label: 'Reprovado' },
            ]}
            error={validationError?.approved}
            defaultValue={model?.approved}
            control={control}
            disabled={isPosting}
            id="approved"
            label="Status"
          />
        </GridCell>
      </GridRow>
    </FormPage>
  )
}
