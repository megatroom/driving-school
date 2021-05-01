import parse from 'date-fns/parse'
import format from 'date-fns/format'
import formatISO from 'date-fns/formatISO'
import parseISO from 'date-fns/parseISO'
import startOfToday from 'date-fns/startOfToday'

const formatPayloadWithFormat = (str: string, customFormat: string) => {
  if (!str) {
    return ''
  }

  try {
    return format(parseISO(str), customFormat)
  } catch (err) {
    console.error('Invalidade date/time: ', str)
    return str
  }
}

export const formatPayloadDateTime = (str: string) =>
  formatPayloadWithFormat(str, 'dd/MM/yyyy HH:mm')

export const formatPayloadDate = (str: string) =>
  formatPayloadWithFormat(str, 'dd/MM/yyyy')

export const formatPayloadTime = (str: string) =>
  formatPayloadWithFormat(str, 'HH:mm')

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

export const getStartOfTheDay = () => formatISO(startOfToday())
