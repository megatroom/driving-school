import { differenceInCalendarDays } from 'date-fns/differenceInCalendarDays';
import { formatDistanceToNow } from 'date-fns/formatDistanceToNow';
import { format } from 'date-fns/format';
import { ptBR } from 'date-fns/locale';

export const formatSimpleDate = (dateTime: Date): string => {
  return format(dateTime, 'dd/MM/yyyy');
};

export const distanceToNow = (dateTime: Date): string => {
  return formatDistanceToNow(dateTime, {
    includeSeconds: false,
    locale: ptBR,
  });
};

export type DaysLeft = {
  text: string;
  isPast: boolean;
};

export const daysLeftFromNow = (dateTime: Date): DaysLeft => {
  const today = new Date();
  const diffInDays = differenceInCalendarDays(dateTime, today);
  let text = '';

  if (diffInDays === 0) {
    text = 'hoje';
  } else if (diffInDays === 1) {
    text = 'amanhã';
  } else if (diffInDays > 1) {
    text = `faltam ${diffInDays} dias`;
  } else if (diffInDays === -1) {
    text = 'passou 1 dia';
  } else {
    text = `passou ${Math.abs(diffInDays)} dias`;
  }

  return {
    isPast: diffInDays < 0,
    text,
  };
};
