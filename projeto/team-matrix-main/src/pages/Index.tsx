import { useState, useMemo, useEffect } from 'react';
import { mockEmployees, cycles } from '@/data/mockEmployees';
import { Employee, getCategory, NineBoxCategory } from '@/types/ninebox';
import { FilterBar } from '@/components/ninebox/FilterBar';
import { EmployeeCard } from '@/components/ninebox/EmployeeCard';
import { EmployeeDetailModal } from '@/components/ninebox/EmployeeDetailModal';
import { ThemeToggle } from '@/components/ninebox/ThemeToggle';
import { LayoutGrid, Users } from 'lucide-react';

const Index = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedDepartment, setSelectedDepartment] = useState('Todos');
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [selectedCycle, setSelectedCycle] = useState(cycles[0].id);
  const [selectedEmployee, setSelectedEmployee] = useState<Employee | null>(null);
  const [modalOpen, setModalOpen] = useState(false);

  // Initialize theme from localStorage
  useEffect(() => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      document.documentElement.classList.add('dark');
    }
  }, []);

  const filteredEmployees = useMemo(() => {
    return mockEmployees.filter((employee) => {
      // Search filter
      const matchesSearch =
        searchQuery === '' ||
        employee.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
        employee.role.toLowerCase().includes(searchQuery.toLowerCase());

      // Department filter
      const matchesDepartment =
        selectedDepartment === 'Todos' || employee.department === selectedDepartment;

      // Category filter
      const matchesCategory =
        selectedCategory === 'all' || getCategory(employee.position) === selectedCategory;

      return matchesSearch && matchesDepartment && matchesCategory;
    });
  }, [searchQuery, selectedDepartment, selectedCategory]);

  const handleEmployeeClick = (employee: Employee) => {
    setSelectedEmployee(employee);
    setModalOpen(true);
  };

  const handleCloseModal = () => {
    setModalOpen(false);
    setTimeout(() => setSelectedEmployee(null), 200);
  };

  // Stats
  const totalEmployees = mockEmployees.length;
  const filteredCount = filteredEmployees.length;

  return (
    <div className="min-h-screen bg-background">
      {/* Header */}
      <header className="sticky top-0 z-40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 border-b border-border">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="p-2 bg-primary/10 rounded-lg">
                <LayoutGrid className="h-6 w-6 text-primary" />
              </div>
              <div>
                <h1 className="text-xl font-bold text-foreground">Matriz 9Box</h1>
                <p className="text-sm text-muted-foreground">Avaliação de Desempenho e Potencial</p>
              </div>
            </div>
            <ThemeToggle />
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-6">
        {/* Filter Bar */}
        <FilterBar
          searchQuery={searchQuery}
          onSearchChange={setSearchQuery}
          selectedDepartment={selectedDepartment}
          onDepartmentChange={setSelectedDepartment}
          selectedCategory={selectedCategory}
          onCategoryChange={setSelectedCategory}
          selectedCycle={selectedCycle}
          onCycleChange={setSelectedCycle}
        />

        {/* Results Header */}
        <div className="flex items-center justify-between mt-6 mb-4">
          <div className="flex items-center gap-2 text-muted-foreground">
            <Users className="h-4 w-4" />
            <span className="text-sm">
              {filteredCount === totalEmployees ? (
                <span>{totalEmployees} colaboradores</span>
              ) : (
                <span>
                  {filteredCount} de {totalEmployees} colaboradores
                </span>
              )}
            </span>
          </div>
        </div>

        {/* Employee Grid */}
        {filteredEmployees.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            {filteredEmployees.map((employee, index) => (
              <EmployeeCard
                key={employee.id}
                employee={employee}
                onClick={handleEmployeeClick}
                animationDelay={index * 50}
              />
            ))}
          </div>
        ) : (
          <div className="flex flex-col items-center justify-center py-16 text-center animate-fade-in">
            <div className="p-4 bg-muted/50 rounded-full mb-4">
              <Users className="h-8 w-8 text-muted-foreground" />
            </div>
            <h3 className="text-lg font-semibold text-foreground mb-2">
              Nenhum colaborador encontrado
            </h3>
            <p className="text-sm text-muted-foreground max-w-md">
              Tente ajustar os filtros ou a busca para encontrar os colaboradores desejados.
            </p>
          </div>
        )}
      </main>

      {/* Employee Detail Modal */}
      <EmployeeDetailModal
        employee={selectedEmployee}
        open={modalOpen}
        onClose={handleCloseModal}
      />
    </div>
  );
};

export default Index;
