import debounce from 'lodash.debounce'

import { ResponseCEP, getAddressFromCEP } from 'services/cep'
import { brazilianStates } from 'constants/adress'
import GridRow from 'atoms/form/GridRow'
import GridCell from 'atoms/form/GridCell'
import TextField from 'atoms/form/TextField'
import CEPField from 'atoms/form/CEPField'
import SelectField from 'atoms/form/SelectField'
import ConfirmDialog from 'atoms/ConfirmDialog'
import { useState } from 'react'

interface Props {
  getValues: (naem: string) => string
  setValue: (name: string, value: string | number) => void
  validationError: any
  model: any
  control: any
  isPosting: boolean
}

export default function AddressFields({
  getValues,
  setValue,
  validationError,
  model,
  control,
  isPosting,
}: Props) {
  const [confirmData, setConfirmData] = useState<ResponseCEP>()

  const updateAddress = (data: ResponseCEP) => {
    setValue('state', data.uf)
    setValue('city', data.localidade)
    setValue('neighborhood', data.bairro)
    setValue('address', data.logradouro)
  }

  const getFieldWithText = () => {
    const result = []

    if (getValues('city')) result.push('cidade')
    if (getValues('neighborhood')) result.push('bairro')
    if (getValues('address')) result.push('endereço')

    if (result.length) {
      return result.join(', ')
    }

    return false
  }

  const handleCEPChange = debounce((event: any) => {
    const cep = event.target.value || ''
    if (cep.length === 9) {
      getAddressFromCEP(cep)
        .then((data) => {
          if (getFieldWithText()) {
            setConfirmData(data)
          } else if (data) {
            updateAddress(data)
          }
        })
        .catch((err) => {
          console.error('Error to get CEP from API: ', err.message)
        })
    }
  }, 300)

  return (
    <>
      <GridRow>
        <GridCell column={4}>
          <CEPField
            onChange={handleCEPChange}
            error={validationError?.cep}
            defaultValue={model?.cep}
            control={control}
            disabled={isPosting}
            id="cep"
            label="CEP"
          />
        </GridCell>
        <GridCell column={8}>
          <TextField
            error={validationError?.address}
            defaultValue={model?.address}
            control={control}
            disabled={isPosting}
            maxLength={100}
            id="address"
            label="Endereço"
          />
        </GridCell>
      </GridRow>
      <GridRow>
        <GridCell column={4}>
          <TextField
            error={validationError?.neighborhood}
            defaultValue={model?.neighborhood}
            control={control}
            disabled={isPosting}
            maxLength={100}
            id="neighborhood"
            label="Bairro"
          />
        </GridCell>
        <GridCell column={4}>
          <TextField
            error={validationError?.city}
            defaultValue={model?.city}
            control={control}
            disabled={isPosting}
            maxLength={100}
            id="city"
            label="Cidade"
          />
        </GridCell>
        <GridCell column={4}>
          <SelectField
            options={brazilianStates}
            error={validationError?.state}
            defaultValue={model?.state || 'RJ'}
            control={control}
            disabled={isPosting}
            id="state"
            label="Estado"
          />
        </GridCell>
      </GridRow>
      <ConfirmDialog
        id="confirm-adress"
        onCancel={() => {
          setConfirmData(undefined)
        }}
        onConfirm={() => {
          confirmData && updateAddress(confirmData)
          setConfirmData(undefined)
        }}
        open={!!confirmData}
      >
        <p>Novo endereço encontrado a partir do CEP {getValues('cep')}.</p>
        <p>Confirma substituição do(s) campo(s): {getFieldWithText()}</p>
      </ConfirmDialog>
    </>
  )
}
