'use server';

import {
  Notification,
  NotificationForRecipient,
  NotificationPriority,
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

export const getNotificationsForRecipient = async (
  recipientId: number,
): Promise<NotificationForRecipient[]> => {
  const sql = `
select a.id, a.mensagem as message, a.data as createdAt, a.prioridade as priority, a.remetente as sender
from vavisos a
where a.status = ? and a.iddestinatario = ?
order by a.data, a.prioridade
  `;
  const values = [NotificationStatus.ACTIVE, recipientId];

  return (await client.query(sql, values)) as NotificationForRecipient[];
};

interface NotificationQueryResult {
  id: number;
  status: string;
  mensagem: string;
  data: Date;
  prioridade: NotificationPriority;
  iddestinatario: number;
  destinatario: string;
  idremetente: number;
  remetente: string;
}

export const getAllNotifications = async (): Promise<Notification[]> => {
  const sql = `
select a.id, a.iddestinatario, a.destinatario, a.idremetente,  a.remetente,
  a.mensagem, a.data, a.prioridade, a.status
from vavisos a
order by a.data, a.prioridade
  `;

  const rows = (await client.query(sql, [])) as NotificationQueryResult[];

  return rows.map<Notification>((row) => ({
    id: row.id,
    status: row.status,
    message: row.mensagem,
    createdAt: row.data,
    priority: row.prioridade,
    recipient: {
      id: row.iddestinatario,
      name: row.destinatario,
    },
    sender: {
      id: row.idremetente,
      name: row.remetente,
    },
  }));
};
