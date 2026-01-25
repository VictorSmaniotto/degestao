import { Employee } from '@/types/ninebox';

export const mockEmployees: Employee[] = [
  {
    id: '1',
    name: 'Ana Carolina Silva',
    role: 'Tech Lead',
    department: 'Tecnologia',
    position: { performance: 3, potential: 3 },
    manager: 'Ricardo Mendes',
    hireDate: '2020-03-15',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '2',
    name: 'Bruno Oliveira',
    role: 'Desenvolvedor Senior',
    department: 'Tecnologia',
    position: { performance: 3, potential: 2 },
    manager: 'Ana Carolina Silva',
    hireDate: '2021-06-01',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '3',
    name: 'Carla Mendonça',
    role: 'Product Manager',
    department: 'Produto',
    position: { performance: 2, potential: 3 },
    manager: 'Ricardo Mendes',
    hireDate: '2022-01-20',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '4',
    name: 'Daniel Santos',
    role: 'Designer UX',
    department: 'Design',
    position: { performance: 2, potential: 2 },
    manager: 'Fernanda Costa',
    hireDate: '2021-09-10',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '5',
    name: 'Elena Ferreira',
    role: 'Analista de Dados',
    department: 'Analytics',
    position: { performance: 3, potential: 1 },
    manager: 'Gustavo Lima',
    hireDate: '2019-11-05',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '6',
    name: 'Felipe Rodrigues',
    role: 'Desenvolvedor Pleno',
    department: 'Tecnologia',
    position: { performance: 1, potential: 3 },
    manager: 'Ana Carolina Silva',
    hireDate: '2023-02-14',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '7',
    name: 'Gabriela Almeida',
    role: 'Coordenadora de RH',
    department: 'Recursos Humanos',
    position: { performance: 2, potential: 1 },
    manager: 'Patricia Souza',
    hireDate: '2018-04-20',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '8',
    name: 'Henrique Costa',
    role: 'Estagiário',
    department: 'Tecnologia',
    position: { performance: 1, potential: 1 },
    manager: 'Bruno Oliveira',
    hireDate: '2023-08-01',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '9',
    name: 'Isabela Nunes',
    role: 'Analista Financeiro',
    department: 'Finanças',
    position: { performance: 1, potential: 2 },
    manager: 'Lucas Martins',
    hireDate: '2022-05-15',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '10',
    name: 'João Pedro Lima',
    role: 'DevOps Engineer',
    department: 'Tecnologia',
    position: { performance: 3, potential: 3 },
    manager: 'Ana Carolina Silva',
    hireDate: '2020-07-22',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '11',
    name: 'Larissa Campos',
    role: 'Marketing Manager',
    department: 'Marketing',
    position: { performance: 2, potential: 3 },
    manager: 'Ricardo Mendes',
    hireDate: '2021-03-08',
    lastEvaluation: '2024-01-10'
  },
  {
    id: '12',
    name: 'Marcos Vinícius',
    role: 'Desenvolvedor Junior',
    department: 'Tecnologia',
    position: { performance: 2, potential: 2 },
    manager: 'Bruno Oliveira',
    hireDate: '2023-01-10',
    lastEvaluation: '2024-01-10'
  }
];

export const cycles = [
  { id: '2024-1', label: 'Ciclo 2024.1', startDate: '2024-01-01', endDate: '2024-06-30' },
  { id: '2023-2', label: 'Ciclo 2023.2', startDate: '2023-07-01', endDate: '2023-12-31' },
  { id: '2023-1', label: 'Ciclo 2023.1', startDate: '2023-01-01', endDate: '2023-06-30' },
];

export const departments = [
  'Todos',
  'Tecnologia',
  'Produto',
  'Design',
  'Analytics',
  'Recursos Humanos',
  'Finanças',
  'Marketing'
];
