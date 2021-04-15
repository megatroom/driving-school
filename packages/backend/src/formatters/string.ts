export const pluralize = (count: number, str: string, zeroText?: string) => {
  if (count > 0) {
    return `${count} ${str}${count === 1 ? '' : 's'}`
  }
  return zeroText || str
}
