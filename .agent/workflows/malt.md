---
description: Iniciar Novo Módulo MALT
---

[cite_start]Este workflow guia a criação de um recurso completo seguindo o Método MALT, respeitando domínio, eventos e arquitetura TALLF[cite: 34].

## Passo 1: Modelagem (M)

* [cite_start]Pergunte qual problema de decisão organizacional o módulo resolve[cite: 115].
* [cite_start]Identifique se o problema envolve Performance, Potencial ou ambos[cite: 118].
* [cite_start]Esboce entidades, Value Objects e eventos envolvidos[cite: 121].
* Valide se o 9BOX é apenas consumidor do resultado.

## Passo 2: Ação (A)

* [cite_start]Defina Actions (casos de uso) explícitas[cite: 127].
* Identifique comandos de escrita e queries de leitura.
* Defina quais eventos de domínio serão disparados.

## Passo 3: Lógica (L)

* [cite_start]Implemente regras de negócio no domínio ou em agregadores[cite: 135].
* Evite lógica em controllers, Livewire ou Filament.
* Garanta separação entre fato e interpretação.

## Passo 4: Teste (T)

* [cite_start]Crie testes de domínio para regras críticas[cite: 147].
* Teste eventos, agregadores e projeções.
* Valide cenários de borda (ex.: sem evidência, alta complexidade).

---