import parse from 'date-fns/parse'
import format from 'date-fns/format'
import formatISO from 'date-fns/formatISO'
import parseISO from 'date-fns/parseISO'

export const formatPayloadDate = (str: string) => {
  if (!str) {
    return ''
  }

  return format(parseISO(str), 'dd/MM/yyyy')
}

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
