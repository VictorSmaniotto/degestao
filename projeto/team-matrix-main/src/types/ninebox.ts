export type NineBoxPosition = {
  performance: 1 | 2 | 3; // 1 = Baixo, 2 = Médio, 3 = Alto
  potential: 1 | 2 | 3;   // 1 = Baixo, 2 = Médio, 3 = Alto
};

export type NineBoxCategory = 
  | 'star'           // Alto Potencial + Alto Desempenho
  | 'high-performer' // Alto Desempenho + Médio Potencial
  | 'high-potential' // Alto Potencial + Médio Desempenho
  | 'core-player'    // Médio Potencial + Médio Desempenho
  | 'solid-performer'// Baixo Potencial + Alto Desempenho
  | 'inconsistent'   // Alto Potencial + Baixo Desempenho
  | 'risk'           // Baixo Potencial + Médio Desempenho
  | 'underperformer' // Baixo Potencial + Baixo Desempenho
  | 'enigma';        // Médio Potencial + Baixo Desempenho

export interface Employee {
  id: string;
  name: string;
  role: string;
  department: string;
  avatar?: string;
  position: NineBoxPosition;
  manager?: string;
  hireDate?: string;
  lastEvaluation?: string;
}

export const CATEGORY_CONFIG: Record<NineBoxCategory, {
  label: string;
  description: string;
  recommendation: string;
}> = {
  star: {
    label: 'Estrela',
    description: 'Alto potencial combinado com excelente desempenho',
    recommendation: 'Preparar para posições de liderança e projetos estratégicos'
  },
  'high-performer': {
    label: 'Alto Desempenho',
    description: 'Entrega consistente com potencial moderado de crescimento',
    recommendation: 'Manter engajado com desafios e reconhecimento'
  },
  'high-potential': {
    label: 'Alto Potencial',
    description: 'Grande potencial de crescimento com desempenho em desenvolvimento',
    recommendation: 'Investir em desenvolvimento e mentoria'
  },
  'core-player': {
    label: 'Mantenedor',
    description: 'Desempenho e potencial equilibrados',
    recommendation: 'Desenvolver habilidades específicas para crescimento'
  },
  'solid-performer': {
    label: 'Profissional Eficaz',
    description: 'Desempenho sólido com foco na função atual',
    recommendation: 'Valorizar expertise e considerar como referência técnica'
  },
  inconsistent: {
    label: 'Inconsistente',
    description: 'Alto potencial mas desempenho abaixo do esperado',
    recommendation: 'Investigar barreiras e oferecer suporte direcionado'
  },
  risk: {
    label: 'Questionável',
    description: 'Desempenho moderado com potencial limitado',
    recommendation: 'Avaliar adequação à função e plano de melhoria'
  },
  underperformer: {
    label: 'Insuficiente',
    description: 'Desempenho e potencial abaixo do esperado',
    recommendation: 'Plano de melhoria urgente ou realocação'
  },
  enigma: {
    label: 'Enigma',
    description: 'Potencial moderado mas desempenho inconsistente',
    recommendation: 'Compreender motivações e realinhar expectativas'
  }
};

export function getCategory(position: NineBoxPosition): NineBoxCategory {
  const { performance, potential } = position;
  
  if (potential === 3 && performance === 3) return 'star';
  if (potential === 2 && performance === 3) return 'high-performer';
  if (potential === 3 && performance === 2) return 'high-potential';
  if (potential === 2 && performance === 2) return 'core-player';
  if (potential === 1 && performance === 3) return 'solid-performer';
  if (potential === 3 && performance === 1) return 'inconsistent';
  if (potential === 1 && performance === 2) return 'risk';
  if (potential === 1 && performance === 1) return 'underperformer';
  return 'enigma'; // potential === 2 && performance === 1
}
