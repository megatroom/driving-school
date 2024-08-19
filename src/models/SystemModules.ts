type SystemModulePageRules = {
  id: number;
  name: string;
  active: boolean;
  path?: string;
};

type SystemModuleValueRules = {
  id: number;
  name: string;
  active: boolean;
  pages: Record<string, SystemModulePageRules>;
};

type SystemModuleRules = Record<string, SystemModuleValueRules>;

export const SYSTEM_MODULE_RULES: SystemModuleRules = {
  '1': {
    id: 1,
    name: 'Controles',
    active: true,
    pages: {
      '1': {
        id: 1,
        name: 'Serviços',
        active: false,
      },
      '2': {
        id: 2,
        name: 'Aulas Práticas',
        active: false,
      },
      '3': {
        id: 3,
        name: 'Aulas Teóricas',
        active: false,
      },
      '4': {
        id: 4,
        name: 'Exame Prático',
        active: false,
      },
      '5': {
        id: 5,
        name: 'Turmas',
        active: false,
      },
      '6': {
        id: 6,
        name: 'Avisos',
        active: true,
        path: '/notifications',
      },
      '7': {
        id: 7,
        name: 'Agendamentos',
        active: false,
      },
      '54': {
        id: 54,
        name: 'Observação Aluno',
        active: false,
      },
    },
  },
  '2': {
    id: 2,
    name: 'Cadastros',
    active: true,
    pages: {
      '8': {
        id: 8,
        name: 'Funções',
        active: true,
        path: '/employees/roles',
      },
      '9': {
        id: 9,
        name: 'Funcionários',
        active: true,
        path: '/employees',
      },
      '10': {
        id: 10,
        name: 'Alunos',
        active: false,
      },
      '11': {
        id: 11,
        name: 'Carros',
        active: false,
      },
      '12': {
        id: 12,
        name: 'Carros x Func.',
        active: false,
      },
      '13': {
        id: 13,
        name: 'Salas',
        active: false,
      },
      '14': {
        id: 14,
        name: 'Turnos',
        active: false,
      },
      '15': {
        id: 15,
        name: 'Expediente',
        active: false,
      },
      '16': {
        id: 16,
        name: 'Tipos de Agendamento',
        active: false,
      },
      '17': {
        id: 17,
        name: 'Tipos de Serviço',
        active: false,
      },
      '18': {
        id: 18,
        name: 'Tipos de Carros',
        active: false,
      },
      '30': {
        id: 30,
        name: 'Origens',
        active: false,
      },
      '45': {
        id: 45,
        name: 'Vales',
        active: true,
        path: '/vouchers',
      },
      '49': {
        id: 49,
        name: 'Bônus',
        active: false,
      },
      '53': {
        id: 53,
        name: 'Horário Exame Prático',
        active: false,
      },
    },
  },
  '3': {
    id: 3,
    name: 'Financeiro',
    active: false,
    pages: {
      '19': {
        id: 19,
        name: 'Contas a Receber',
        active: false,
      },
      '20': {
        id: 20,
        name: 'Comissão',
        active: false,
      },
      '21': {
        id: 21,
        name: 'Caixa',
        active: false,
      },
      '22': {
        id: 22,
        name: 'Controle de Caixas',
        active: false,
      },
      '35': {
        id: 35,
        name: 'Relatórios',
        active: false,
      },
      '41': {
        id: 41,
        name: 'Recibos',
        active: false,
      },
      '43': {
        id: 43,
        name: 'Declaração Pagto',
        active: false,
      },
    },
  },
  '4': {
    id: 4,
    name: 'Emissões',
    active: false,
    pages: {
      '31': {
        id: 31,
        name: 'Planilha do Instrutor',
        active: false,
      },
      '32': {
        id: 32,
        name: 'Declaração',
        active: false,
      },
      '36': {
        id: 36,
        name: 'Aulas Alunos',
        active: false,
      },
      '44': {
        id: 44,
        name: 'Aulas Teóricas',
        active: false,
      },
    },
  },
  '5': {
    id: 5,
    name: 'Relatórios',
    active: false,
    pages: {
      '33': {
        id: 33,
        name: 'Agendamentos',
        active: false,
      },
      '34': {
        id: 34,
        name: 'Exame Prático',
        active: false,
      },
      '38': {
        id: 38,
        name: 'Validade Processo',
        active: false,
      },
      '39': {
        id: 39,
        name: 'Ficha Aluno',
        active: false,
      },
      '40': {
        id: 40,
        name: 'Exame Prático Alunos',
        active: false,
      },
      '46': {
        id: 46,
        name: 'Vales',
        active: false,
      },
      '48': {
        id: 48,
        name: 'Caixa Por Usuário',
        active: false,
      },
      '50': {
        id: 50,
        name: 'Tipo de Serviços',
        active: false,
      },
      '51': {
        id: 51,
        name: 'Ranking Exame Prático',
        active: false,
      },
      '52': {
        id: 52,
        name: 'Aula Prática Duplicada',
        active: false,
      },
    },
  },
  '6': {
    id: 6,
    name: 'Configurações',
    active: false,
    pages: {
      '23': {
        id: 23,
        name: 'Usuários',
        active: false,
      },
      '24': {
        id: 24,
        name: 'Trocar Senha',
        active: false,
      },
      '25': {
        id: 25,
        name: 'Grupos de Usuário',
        active: false,
      },
      '26': {
        id: 26,
        name: 'Controle de Acessos',
        active: false,
      },
      '27': {
        id: 27,
        name: 'Ícones',
        active: false,
      },
      '28': {
        id: 28,
        name: 'Sistema',
        active: false,
      },
      '37': {
        id: 37,
        name: 'Menu',
        active: false,
      },
      '42': {
        id: 42,
        name: 'Backup',
        active: false,
      },
      '29': {
        id: 29,
        name: 'Sair (Logout)',
        active: false,
      },
      '47': {
        id: 47,
        name: 'Sobre',
        active: false,
      },
    },
  },
};
