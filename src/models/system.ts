import { z } from 'zod';

export interface SystemPage {
  id: number;
  name: string;
}

export interface SystemModule {
  id: number;
  description: string;
  pages: SystemPage[];
}

export enum NotificationStatus {
  ACTIVE = 'A',
  CONCLUDED = 'C',
}

export enum NotificationPriority {
  HIGH = '0',
  MEDIUM = '1',
  LOW = '2',
}

export interface Notification {
  id: number;
  message: string;
  createdAt: Date;
  priority: NotificationPriority;
  sender: string;
}

export const castPriorityToText = (priority: NotificationPriority): string => {
  switch (priority) {
    case NotificationPriority.HIGH:
      return 'Alta';
    case NotificationPriority.MEDIUM:
      return 'Média';
    default:
      return 'Baixa';
  }
};

export type PriorityBadge = {
  text: string;
  color: string;
};

export const castPriorityToBadge = (
  priority: NotificationPriority,
): PriorityBadge => {
  switch (priority) {
    case NotificationPriority.HIGH:
      return {
        text: 'Alta',
        color: 'red',
      };
    case NotificationPriority.MEDIUM:
      return {
        text: 'Média',
        color: 'purple',
      };
    default:
      return {
        text: 'Baixa',
        color: 'gray',
      };
  }
};

export const LoginFormSchema = z.object({
  username: z.string().min(2, { message: 'Este campo é obrigatório.' }).trim(),
  password: z.string().min(2, { message: 'Este campo é obrigatório.' }).trim(),
});
