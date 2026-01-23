# Arquitetura Técnica — TALLF Stack

## Stack
- Laravel 12+
- Livewire v3/v4
- Alpine.js (estado local)
- Tailwind CSS
- Filament v4/v5

## Estilo Arquitetural
Monólito modular orientado a domínio, eventos e CQRS leve.

## Camadas

### Domínio
Entidades, Value Objects, Regras e Eventos.

### Application
Actions (casos de uso) que orquestram o domínio.

### Eventos
Eventos de domínio e listeners para agregação e projeção.

### Agregação
Serviços que calculam os eixos de Performance e Potencial.

### Projeção (Read Models)
Modelos otimizados para leitura (9BOX, dashboards).

### UI (Filament)
Apenas consumo de projeções e execução de actions.

## Regras Técnicas
- Evidência é imutável
- Quadrante não é editável
- Filament não contém lógica de domínio
- Read models são reconstruíveis

## Objetivo
Garantir um sistema auditável, evolutivo e alinhado às boas práticas do ecossistema Laravel.

