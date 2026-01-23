# Modelo Conceitual de Domínio

## Princípios Inegociáveis
- Evidências são fatos imutáveis
- Performance ≠ Potencial
- O 9BOX é derivado, nunca editável
- Desempenho só existe em contexto

## Entidades Centrais

### Pessoa
Representa o indivíduo avaliado. Não possui score próprio.

### Contexto de Atuação
Onde a pessoa atua. Possui nível de complexidade e grau de estruturação. É independente da pessoa.

### Ciclo de Avaliação
Recorte temporal explícito no qual evidências são observadas.

### Evidência
Registro factual e imutável de um comportamento observado, associado a uma pessoa, contexto e ciclo.

Tipos:
- Performance
- Potencial

### Agregadores
Serviços de domínio responsáveis por interpretar evidências e calcular os eixos.

### Projeções
Modelos de leitura derivados (ex.: 9BOX), reconstruíveis a partir de eventos.

## Relações
- Pessoa atua em Contextos
- Contextos existem em Ciclos
- Evidências surgem da interação Pessoa–Contexto–Ciclo
- Agregadores interpretam evidências
- Projeções exibem estados derivados

