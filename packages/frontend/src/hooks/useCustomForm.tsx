import { useCallback, useEffect, useState } from 'react'
import { useQueryClient } from 'react-query'
import { useParams } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { useSnackbar } from 'notistack'

import { AdapterModelConfig, modelToPayloadAdapter } from 'adapters/model'

interface Props<T> {
  getModel: (id: number) => Promise<T>
  putModel: (id: number, payload: any) => Promise<T>
  postModel: (payload: any) => Promise<T>
  onSuccess: () => void
  entityName: string
  adapters?: AdapterModelConfig
}

export default function useCustomForm<T>({
  getModel,
  putModel,
  postModel,
  onSuccess,
  entityName,
  adapters,
}: Props<T>) {
  const queryClient = useQueryClient()
  const { control, handleSubmit, getValues, setValue } = useForm()
  const { enqueueSnackbar } = useSnackbar()
  const [validationError, setValidationError] = useState<Record<string, any>>()
  const [customError, setCustomError] = useState<Error>()
  const [model, setModel] = useState<T>()
  const [isLoading, toggleLoading] = useState(true)
  const [isPosting, togglePosting] = useState(false)
  const { id } = useParams()
  const isEditingMode = !!id

  const adaptModelToPayload = useCallback(
    (model) => modelToPayloadAdapter(adapters || {})(model),
    [adapters]
  )

  useEffect(() => {
    if (id) {
      getModel(parseInt(id, 10))
        .then((data) => {
          setModel(data)
          toggleLoading(false)
        })
        .catch(({ message }) => {
          toggleLoading(false)
          setCustomError(message)
        })
    } else {
      toggleLoading(false)
    }
  }, [id, getModel])

  const onSubmit = (payload: any) => {
    togglePosting(true)

    const promise = id
      ? putModel(parseInt(id, 10), adaptModelToPayload(payload))
      : postModel(adaptModelToPayload(payload))

    promise
      .then(() => {
        queryClient.invalidateQueries('car-types')
        togglePosting(false)
        enqueueSnackbar(
          `${entityName} ${id ? 'alterado' : 'cadastrado'} com sucesso`,
          { variant: 'success' }
        )
        onSuccess()
      })
      .catch(({ validation, message }) => {
        togglePosting(false)
        if (validation) {
          setValidationError(validation)
        } else {
          setCustomError(message)
        }
      })
  }

  return {
    handleSubmit: handleSubmit(onSubmit),
    getValues,
    setValue,
    isLoading,
    isPosting,
    isEditingMode,
    validationError,
    customError,
    control,
    model,
  }
}
