import { cn } from '@/lib/utils';
import { NineBoxPosition, NineBoxCategory, getCategory } from '@/types/ninebox';

interface MiniMatrixProps {
  position: NineBoxPosition;
  size?: 'sm' | 'md' | 'lg';
  showLabels?: boolean;
}

const categoryColorMap: Record<NineBoxCategory, string> = {
  star: 'bg-ninebox-star',
  'high-performer': 'bg-ninebox-high-performer',
  'high-potential': 'bg-ninebox-high-potential',
  'core-player': 'bg-ninebox-core-player',
  'solid-performer': 'bg-ninebox-solid-performer',
  inconsistent: 'bg-ninebox-inconsistent',
  risk: 'bg-ninebox-risk',
  underperformer: 'bg-ninebox-underperformer',
  enigma: 'bg-ninebox-enigma',
};

const sizeConfig = {
  sm: {
    cell: 'w-4 h-4',
    gap: 'gap-0.5',
    container: 'p-1',
  },
  md: {
    cell: 'w-6 h-6',
    gap: 'gap-1',
    container: 'p-1.5',
  },
  lg: {
    cell: 'w-8 h-8',
    gap: 'gap-1.5',
    container: 'p-2',
  },
};

export function MiniMatrix({ position, size = 'md', showLabels = false }: MiniMatrixProps) {
  const config = sizeConfig[size];
  const category = getCategory(position);
  const activeColor = categoryColorMap[category];

  // Matrix is 3x3, rows represent potential (top = high), columns represent performance (right = high)
  const cells = [];
  for (let potential = 3; potential >= 1; potential--) {
    for (let performance = 1; performance <= 3; performance++) {
      const isActive = position.potential === potential && position.performance === performance;
      cells.push(
        <div
          key={`${potential}-${performance}`}
          className={cn(
            config.cell,
            'rounded-sm transition-all duration-300',
            isActive 
              ? cn(activeColor, 'ring-2 ring-foreground/20 shadow-sm') 
              : 'bg-muted/50 dark:bg-muted/30'
          )}
        />
      );
    }
  }

  return (
    <div className="flex flex-col items-center">
      {showLabels && (
        <span className="text-[10px] text-muted-foreground mb-1 font-medium">Potencial ↑</span>
      )}
      <div className="flex items-center">
        <div className={cn('grid grid-cols-3', config.gap, config.container, 'bg-muted/30 rounded-md')}>
          {cells}
        </div>
      </div>
      {showLabels && (
        <span className="text-[10px] text-muted-foreground mt-1 font-medium">Desempenho →</span>
      )}
    </div>
  );
}
