'use server';

import {
  Notification,
  NotificationStatus,
  SystemModule,
} from '@/models/system';
import { client } from './_client';

interface SystemModulesResponseItem {
  pageId: number;
  moduleId: number;
  moduleDescription: string;
  pageName: string;
}

export const getSystemModules = async (): Promise<SystemModule[]> => {
  const sql = `
select a.id as pageId, a.idmodulo as moduleId, b.descricao as moduleDescription, a.descricao as pageName
from telas a, modulos b
where a.idmodulo = b.id
order by b.ordem, a.idmodulo, a.ordem
`;
  const result: SystemModule[] = [];
  const rows = (await client.query(sql, [])) as SystemModulesResponseItem[];

  let lastModuleId: Number | undefined;
  for (const row of rows) {
    if (lastModuleId === row.moduleId) {
      result[result.length - 1].pages.push({
        id: row.pageId,
        name: row.pageName,
      });
    } else {
      lastModuleId = row.moduleId;
      result.push({
        id: row.moduleId,
        description: row.moduleDescription,
        pages: [
          {
            id: row.pageId,
            name: row.pageName,
          },
        ],
      });
    }
  }

  return result;
};

export const getNotifications = async (
  recipientId: number,
): Promise<Notification[]> => {
  const sql = `
select a.id, a.mensagem as message, a.data as createdAt, a.prioridade as priority, a.remetente as sender
from vavisos a
where a.status = ? and a.iddestinatario = ?
order by a.data, a.prioridade
  `;
  const values = [NotificationStatus.ACTIVE, recipientId];

  return (await client.query(sql, values)) as Notification[];
};
