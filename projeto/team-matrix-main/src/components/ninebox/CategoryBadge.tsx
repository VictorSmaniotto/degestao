import { cn } from '@/lib/utils';
import { NineBoxCategory, CATEGORY_CONFIG } from '@/types/ninebox';

interface CategoryBadgeProps {
  category: NineBoxCategory;
  size?: 'sm' | 'md';
}

const categoryColorMap: Record<NineBoxCategory, { bg: string; text: string }> = {
  star: { bg: 'bg-ninebox-star-bg', text: 'text-ninebox-star' },
  'high-performer': { bg: 'bg-ninebox-high-performer-bg', text: 'text-ninebox-high-performer' },
  'high-potential': { bg: 'bg-ninebox-high-potential-bg', text: 'text-ninebox-high-potential' },
  'core-player': { bg: 'bg-ninebox-core-player-bg', text: 'text-ninebox-core-player' },
  'solid-performer': { bg: 'bg-ninebox-solid-performer-bg', text: 'text-ninebox-solid-performer' },
  inconsistent: { bg: 'bg-ninebox-inconsistent-bg', text: 'text-ninebox-inconsistent' },
  risk: { bg: 'bg-ninebox-risk-bg', text: 'text-ninebox-risk' },
  underperformer: { bg: 'bg-ninebox-underperformer-bg', text: 'text-ninebox-underperformer' },
  enigma: { bg: 'bg-ninebox-enigma-bg', text: 'text-ninebox-enigma' },
};

export function CategoryBadge({ category, size = 'md' }: CategoryBadgeProps) {
  const config = CATEGORY_CONFIG[category];
  const colors = categoryColorMap[category];

  return (
    <span
      className={cn(
        'inline-flex items-center font-semibold rounded-full transition-all duration-200',
        colors.bg,
        colors.text,
        size === 'sm' ? 'px-2 py-0.5 text-xs' : 'px-3 py-1 text-sm'
      )}
    >
      {config.label}
    </span>
  );
}
