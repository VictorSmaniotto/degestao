import { Employee, getCategory, CATEGORY_CONFIG } from '@/types/ninebox';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { MiniMatrix } from './MiniMatrix';
import { CategoryBadge } from './CategoryBadge';
import { Calendar, Building2, User, Briefcase, Star, Target, Lightbulb } from 'lucide-react';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

interface EmployeeDetailModalProps {
  employee: Employee | null;
  open: boolean;
  onClose: () => void;
}

function getInitials(name: string): string {
  return name
    .split(' ')
    .map((n) => n[0])
    .slice(0, 2)
    .join('')
    .toUpperCase();
}

function formatDate(dateStr?: string): string {
  if (!dateStr) return '-';
  return format(new Date(dateStr), "dd 'de' MMMM 'de' yyyy", { locale: ptBR });
}

const performanceLabels = ['', 'Baixo', 'Médio', 'Alto'];
const potentialLabels = ['', 'Baixo', 'Médio', 'Alto'];

export function EmployeeDetailModal({ employee, open, onClose }: EmployeeDetailModalProps) {
  if (!employee) return null;

  const category = getCategory(employee.position);
  const config = CATEGORY_CONFIG[category];

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-[500px] p-0 overflow-hidden">
        {/* Header with gradient */}
        <div className="bg-gradient-to-br from-primary/10 via-primary/5 to-transparent p-6 pb-4">
          <DialogHeader>
            <div className="flex items-center gap-4">
              <Avatar className="h-16 w-16 ring-4 ring-background shadow-lg">
                <AvatarImage src={employee.avatar} alt={employee.name} />
                <AvatarFallback className="bg-primary text-primary-foreground font-bold text-lg">
                  {getInitials(employee.name)}
                </AvatarFallback>
              </Avatar>
              <div className="flex-1">
                <DialogTitle className="text-xl font-bold">{employee.name}</DialogTitle>
                <p className="text-muted-foreground mt-1">{employee.role}</p>
              </div>
            </div>
          </DialogHeader>
        </div>

        {/* Content */}
        <div className="p-6 pt-2 space-y-6">
          {/* Info Grid */}
          <div className="grid grid-cols-2 gap-4">
            <div className="flex items-center gap-2 text-sm">
              <Building2 className="h-4 w-4 text-muted-foreground" />
              <span className="text-muted-foreground">Departamento:</span>
              <span className="font-medium">{employee.department}</span>
            </div>
            <div className="flex items-center gap-2 text-sm">
              <User className="h-4 w-4 text-muted-foreground" />
              <span className="text-muted-foreground">Gestor:</span>
              <span className="font-medium">{employee.manager || '-'}</span>
            </div>
            <div className="flex items-center gap-2 text-sm">
              <Briefcase className="h-4 w-4 text-muted-foreground" />
              <span className="text-muted-foreground">Admissão:</span>
              <span className="font-medium">{formatDate(employee.hireDate)}</span>
            </div>
            <div className="flex items-center gap-2 text-sm">
              <Calendar className="h-4 w-4 text-muted-foreground" />
              <span className="text-muted-foreground">Avaliação:</span>
              <span className="font-medium">{formatDate(employee.lastEvaluation)}</span>
            </div>
          </div>

          {/* Matrix Section */}
          <div className="bg-muted/30 rounded-xl p-5">
            <div className="flex items-center justify-between gap-6">
              <div className="flex-1">
                <div className="flex items-center gap-2 mb-3">
                  <Star className="h-5 w-5 text-primary" />
                  <h4 className="font-semibold">Posição na Matriz</h4>
                </div>
                <div className="space-y-2">
                  <div className="flex items-center justify-between text-sm">
                    <span className="text-muted-foreground flex items-center gap-2">
                      <Target className="h-3.5 w-3.5" />
                      Desempenho:
                    </span>
                    <span className="font-semibold">{performanceLabels[employee.position.performance]}</span>
                  </div>
                  <div className="flex items-center justify-between text-sm">
                    <span className="text-muted-foreground flex items-center gap-2">
                      <Lightbulb className="h-3.5 w-3.5" />
                      Potencial:
                    </span>
                    <span className="font-semibold">{potentialLabels[employee.position.potential]}</span>
                  </div>
                </div>
              </div>
              <MiniMatrix position={employee.position} size="lg" showLabels />
            </div>
          </div>

          {/* Category Info */}
          <div className="space-y-3">
            <div className="flex items-center gap-3">
              <span className="text-sm text-muted-foreground">Classificação:</span>
              <CategoryBadge category={category} />
            </div>
            <p className="text-sm text-muted-foreground leading-relaxed">
              {config.description}
            </p>
            <div className="bg-primary/5 border border-primary/10 rounded-lg p-4">
              <h5 className="text-sm font-semibold text-primary mb-1">Recomendação</h5>
              <p className="text-sm text-foreground/80">{config.recommendation}</p>
            </div>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}
