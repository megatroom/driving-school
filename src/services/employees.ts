'use server';

import {
  Employee,
  EmployeeRoleForm,
  EmployeeRoleFormSchema,
  EmployeeRoleFormState,
  EmployeeStatus,
} from '@/models/employees';
import { client } from './_client';
import { PersonQueryResult } from '@/models/people';
import {
  buildLimit,
  buildOrderBy,
  DataTableQueryParam,
  DataTableState,
} from '@/models/datatable';
import { SortingState } from '@tanstack/react-table';

export async function createEmployeeRole(
  form: EmployeeRoleForm,
): Promise<EmployeeRoleFormState> {
  const validatedFields = EmployeeRoleFormSchema.safeParse(form);

  if (!validatedFields.success) {
    return {
      success: false,
      errors: validatedFields.error.flatten().fieldErrors,
    };
  }

  const result = await client.execute(
    `
INSERT INTO funcoes
(descricao)
VALUES(?)
`,
    [form.description],
  );

  return {
    success: true,
    ...result,
  };
}

function buildEmployeeSort(sorting: SortingState): string {
  return sorting.map(({ id, desc }) => {
    return;
  });
}

type AllEmployeesQueryResult = PersonQueryResult & {
  id: number;
  idfuncao: number;
  idpessoa: number;
  matricula: string;
  status: EmployeeStatus;
  descricao: string;
};

function castIdToField(id: string): string {
  switch (id) {
    case 'registration':
      return 'f.matricula';
    case 'person_name':
      return 'p.nome';
    case 'person_phone':
      return 'p.telefone';
    case 'person_cellphone':
      return 'p.celular';
    default:
      throw new Error(`Field id not found: ${id}`);
  }
}

export async function getAllEmployees(
  params: DataTableQueryParam,
): Promise<DataTableState<Employee>> {
  const sqlRowCount = `
SELECT count(f.id) as singleValue
FROM funcionarios f
LEFT JOIN pessoas p on f.idpessoa = p.id
LEFT JOIN funcoes fc on f.idfuncao = fc.id
`;
  const sqlData = `
SELECT
  f.id, f.idfuncao, f.idpessoa, f.matricula, f.status, fc.descricao,
  p.nome, p.dtnascimento, p.sexo, p.rg, p.orgaoemissor, p.rgdataemissao, p.cpf,
  p.carteiradetrabalho, p.endereco, p.cep, p.bairro, p.cidade, p.estado, p.telefone,
  p.celular, p.email, p.pai, p.mae, p.telcontato, p.telefone2, p.tel2contato,
  p.celular2, p.celular3
FROM funcionarios f
LEFT JOIN pessoas p on f.idpessoa = p.id
LEFT JOIN funcoes fc on f.idfuncao = fc.id
${buildOrderBy(params.sorting, castIdToField)}
${buildLimit(params.pagination)}
`;

  const [rowCount, rows] = await Promise.all([
    client.querySingleValue<number>(sqlRowCount, []),
    client.query<AllEmployeesQueryResult[]>(sqlData, []),
  ]);

  return {
    rowCount,
    rows: rows.map<Employee>((row) => ({
      id: row.id,
      registration: row.matricula,
      status: row.status,
      role: {
        id: row.idfuncao,
        description: row.descricao,
      },
      person: {
        id: row.idpessoa,
        name: row.nome,
        birthDate: row.dtnascimento,
        gender: row.sexo as unknown,
        rg: row.rg,
        orgaoEmissor: row.orgaoemissor,
        rgDataEmissao: row.rgdataemissao,
        cpf: row.cpf,
        carteiraDeTrabalho: row.carteiradetrabalho,
        address: row.endereco,
        zipCode: row.cep,
        neighborhood: row.bairro,
        city: row.cidade,
        state: row.estado,
        email: row.telefone,
        father: row.celular,
        mother: row.email,
        phone: row.pai,
        phoneContact: row.mae,
        phone2: row.telcontato,
        phoneContact2: row.telefone2,
        cellphone: row.tel2contato,
        cellphone2: row.celular2,
        cellphone3: row.celular3,
      },
    })),
  };
}
