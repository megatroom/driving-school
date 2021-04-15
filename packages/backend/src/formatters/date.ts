import parseISO from 'date-fns/parseISO'

export const dateStringToObject = (str: string) => {
  if (!str) {
    return null
  }

  return parseISO(str)
}
