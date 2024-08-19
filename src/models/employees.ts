import { FormState } from './form';
import { z } from 'zod';
import { Person } from './people';

export interface EmployeeRole {
  id: number;
  description: string;
}

export interface EmployeeRoleForm {
  description: string;
}

export type EmployeeRoleFormState = FormState<EmployeeRoleForm>;

export const initialEmployeeRoleForm: EmployeeRoleForm = {
  description: '',
};

export const EmployeeRoleFormSchema = z.object({
  description: z
    .string()
    .min(2, { message: 'Este campo é obrigatório.' })
    .trim(),
});

export enum EmployeeStatus {
  'A' = 'Ativo',
  'I' = 'Inativo',
}

export interface Employee {
  id: number;
  person: Person;
  role: EmployeeRole;
  registration: string;
  status: EmployeeStatus;
}
