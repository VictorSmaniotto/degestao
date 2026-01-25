import { Search, Filter } from 'lucide-react';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { departments, cycles } from '@/data/mockEmployees';
import { NineBoxCategory, CATEGORY_CONFIG } from '@/types/ninebox';

interface FilterBarProps {
  searchQuery: string;
  onSearchChange: (value: string) => void;
  selectedDepartment: string;
  onDepartmentChange: (value: string) => void;
  selectedCategory: string;
  onCategoryChange: (value: string) => void;
  selectedCycle: string;
  onCycleChange: (value: string) => void;
}

const allCategories: Array<{ value: string; label: string }> = [
  { value: 'all', label: 'Todas as Categorias' },
  ...Object.entries(CATEGORY_CONFIG).map(([key, config]) => ({
    value: key,
    label: config.label,
  })),
];

export function FilterBar({
  searchQuery,
  onSearchChange,
  selectedDepartment,
  onDepartmentChange,
  selectedCategory,
  onCategoryChange,
  selectedCycle,
  onCycleChange,
}: FilterBarProps) {
  return (
    <div className="bg-card border border-border rounded-lg p-4 animate-fade-in">
      <div className="flex flex-col lg:flex-row gap-4">
        {/* Search */}
        <div className="relative flex-1">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar colaborador..."
            value={searchQuery}
            onChange={(e) => onSearchChange(e.target.value)}
            className="pl-10 bg-background"
          />
        </div>

        {/* Filters */}
        <div className="flex flex-wrap gap-3">
          {/* Cycle Selector */}
          <Select value={selectedCycle} onValueChange={onCycleChange}>
            <SelectTrigger className="w-[160px] bg-background">
              <SelectValue placeholder="Ciclo" />
            </SelectTrigger>
            <SelectContent>
              {cycles.map((cycle) => (
                <SelectItem key={cycle.id} value={cycle.id}>
                  {cycle.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>

          {/* Department Selector */}
          <Select value={selectedDepartment} onValueChange={onDepartmentChange}>
            <SelectTrigger className="w-[160px] bg-background">
              <Filter className="h-4 w-4 mr-2 text-muted-foreground" />
              <SelectValue placeholder="Departamento" />
            </SelectTrigger>
            <SelectContent>
              {departments.map((dept) => (
                <SelectItem key={dept} value={dept}>
                  {dept}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>

          {/* Category Selector */}
          <Select value={selectedCategory} onValueChange={onCategoryChange}>
            <SelectTrigger className="w-[180px] bg-background">
              <SelectValue placeholder="Categoria" />
            </SelectTrigger>
            <SelectContent>
              {allCategories.map((cat) => (
                <SelectItem key={cat.value} value={cat.value}>
                  {cat.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
      </div>
    </div>
  );
}
