import { format } from 'date-fns';

export const formatSimpleDate = (dateTime: Date): string => {
  return format(dateTime, 'dd/MM/yyyy');
};
