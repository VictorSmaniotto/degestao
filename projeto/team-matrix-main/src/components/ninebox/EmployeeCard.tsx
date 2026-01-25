import { Employee, getCategory, CATEGORY_CONFIG } from '@/types/ninebox';
import { Card, CardContent } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { MiniMatrix } from './MiniMatrix';
import { CategoryBadge } from './CategoryBadge';
import { cn } from '@/lib/utils';

interface EmployeeCardProps {
  employee: Employee;
  onClick: (employee: Employee) => void;
  animationDelay?: number;
}

function getInitials(name: string): string {
  return name
    .split(' ')
    .map((n) => n[0])
    .slice(0, 2)
    .join('')
    .toUpperCase();
}

export function EmployeeCard({ employee, onClick, animationDelay = 0 }: EmployeeCardProps) {
  const category = getCategory(employee.position);
  const config = CATEGORY_CONFIG[category];

  return (
    <Card
      onClick={() => onClick(employee)}
      className={cn(
        'cursor-pointer hover-lift group',
        'border border-border/50 hover:border-border',
        'bg-card hover:bg-card/80',
        'animate-fade-in opacity-0'
      )}
      style={{ animationDelay: `${animationDelay}ms`, animationFillMode: 'forwards' }}
    >
      <CardContent className="p-5">
        <div className="flex items-start gap-4">
          {/* Avatar */}
          <Avatar className="h-12 w-12 ring-2 ring-background shadow-md">
            <AvatarImage src={employee.avatar} alt={employee.name} />
            <AvatarFallback className="bg-primary/10 text-primary font-semibold text-sm">
              {getInitials(employee.name)}
            </AvatarFallback>
          </Avatar>

          {/* Info */}
          <div className="flex-1 min-w-0">
            <h3 className="font-semibold text-foreground truncate group-hover:text-primary transition-colors">
              {employee.name}
            </h3>
            <p className="text-sm text-muted-foreground truncate">{employee.role}</p>
            <p className="text-xs text-muted-foreground/70 mt-0.5">{employee.department}</p>
          </div>

          {/* Mini Matrix */}
          <div className="flex-shrink-0">
            <MiniMatrix position={employee.position} size="sm" />
          </div>
        </div>

        {/* Category Badge and Description */}
        <div className="mt-4 pt-4 border-t border-border/50">
          <div className="flex items-center justify-between gap-3">
            <CategoryBadge category={category} size="sm" />
            <span className="text-xs text-muted-foreground text-right line-clamp-1">
              {config.description.split(' ').slice(0, 4).join(' ')}...
            </span>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
