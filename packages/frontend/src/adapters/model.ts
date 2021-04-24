import { formatDateToPayload } from 'formatters/date'

const adaptForeignFieldToPayload = (option: any) => {
  if (!option || !option.value || option.value === -1) {
    return null
  }

  return option.value
}

export interface AdapterModelConfig {
  foreignKeys?: Array<string>
  dateFields?: Array<string>
  readOnlyFields?: Array<string>
  phoneFields?: Array<string>
  booleanFields?: Array<string>
  cpfFields?: Array<string>
}

export const modelToPayloadAdapter = ({
  foreignKeys,
  dateFields,
  readOnlyFields,
  phoneFields,
  booleanFields,
  cpfFields,
}: AdapterModelConfig) => (model: any) => {
  const result = { ...model }

  foreignKeys?.forEach((key) => {
    result[key] = adaptForeignFieldToPayload(result[key])
  })

  dateFields?.forEach((key) => {
    result[key] = formatDateToPayload(result[key])
  })

  readOnlyFields?.forEach((key) => {
    delete result[key]
  })

  phoneFields?.forEach((key) => {
    result[key] = result[key] && result[key].trim()
  })

  booleanFields?.forEach((key) => {
    result[key] = result[key] ? 'S' : 'N'
  })

  cpfFields?.forEach((key) => {
    if (result[key]) {
      result[key] = result[key].replaceAll('.', '').replaceAll('.', '')
    }
  })

  Object.keys(result).forEach((key) => {
    if (result[key] === '') {
      result[key] = null
    }
  })

  return result
}
