import parse from 'date-fns/parse'
import formatISO from 'date-fns/formatISO'

export const formatDateToPayload = (date: string) => {
  if (!date) {
    return null
  }

  let result

  try {
    result = formatISO(parse(date, 'dd/MM/yyyy', new Date()))
  } catch (err) {
    return null
  }

  return result
}
