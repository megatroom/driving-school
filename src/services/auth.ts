'use server';

import { createSession, deleteSession } from '@/helpers/session';
import { client } from './_client';
import { RowDataPacket } from 'mysql2';
import { redirect } from 'next/navigation';
import { LoginFormSchema } from '@/models/system';
import { FormState } from '@/models/form';
import { LoginForm } from '@/models/auth';

interface UserQuery extends RowDataPacket {
  id: number;
  name: string;
  gender?: string;
}

export async function authenticate(
  values: LoginForm,
): Promise<FormState<LoginForm>> {
  const validatedFields = LoginFormSchema.safeParse(values);

  if (!validatedFields.success) {
    return {
      success: false,
      errors: validatedFields.error.flatten().fieldErrors,
    };
  }

  const users = await client.query<UserQuery[]>(
    `
select u.id, coalesce(u.nome, p.nome) as name, p.sexo as gender
from usuarios u
left join funcionarios f on f.id = u.idfuncionario
left join pessoas p on p.id = f.idpessoa
where login = ? and senha = md5(?) and f.status = 'A'
`,
    [values.username, values.password],
  );

  if (!users || users.length === 0) {
    return {
      success: false,
      message: 'Usuário não encontrado.',
    };
  }

  const { id, name, gender } = users[0];

  createSession({ id, name, gender });

  redirect('/');
}

export async function logout() {
  deleteSession();
  redirect('/login');
}
