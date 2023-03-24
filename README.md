# Repositório Virtual TCC

## Documentação do Sistema
 - [Documento de Visão](#ducumento)
 - [Glosarario](#glosario)
 - [Diagramas](#diagramas)
 - [Regras de Negócios e Mensagens do Sistema](#rnms)
 - [Protótipos](#prototipos)
 - [Requisitos Funcionais](#requisitos_funcionais)
 - [Requisitos Não Funcionais](#requisitos_nao_funcionais)

### [Especificações de Caso de Uso](#especificacoes_de_caso_de_uso)
### [Teste de Software](#teste)

<a name="ducumento"/>

# Documento de Visão

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|
23/03/2023|1.1|Começando a preencher|William José|

## 1. Objetivos

O propósito deste documento é coletar, analisar e definir as necessidades de alto-nível e características do sistema, focando nas potencialidades requeridas pelos afetados e usuários-alvo, e como estes requisitos foram abordados no sistema. A visão do sistema documenta o ambiente geral de processos desenvolvidos para o sistema, fornecendo a todos os envolvidos uma descrição compreensível deste e suas macro-funcionalidades. O Documento de Visão documenta as necessidades e funcionalidades do sistema

## 2. Descrição do Problema

<sub>_Dificuldade de encontrar TCCs no sistemas virtuais geralmente usados nas faculdades._</sub>  

O problema de|**Buscar TCCs** 
-------------|-------------------------------------
afeta|**Alunos e Usuarios comuns.**  
cujo impacto é|**Não conseguir encontrar material para sua pesquisa ou trabalho.** 
uma boa solução seria|**A implementação de um repositorio com filtros eficientes para buscar os TCC.**

## 3. Definição das Partes Interesadas
### Cliente
Nome|Descrição|Responsabilidades
-----|------|---------
### Time de Desenvolvimento
## 4. Descrição do Produto

Para| **Instituições de Ensino**  
----|------  
Que | **Necessita de um sistema para busca de TCCs.**   
O   | **?????**   
É um| **Sistema de classificação de TCCs como interface TCC/usuário.**   
Que | **Este produto possui um conjunto de filtros que visão permitir ao usuario buscar por TCCs dentro da plataforma, assim como tem outros recursos de indicações de de TCCs pelos professores para aluno, e possibilita a marcação de TCC em favoritos.**   
Ao contrário| **Pergamum e outros sistemas usado por faculdades.**   
Nosso produto| **É um sistema ágil e de fácil acesso que permite aos usaurios procurarem conteudo dentro dele.**   
## 5. Necessidades e Funcionamento do Produto

1. Gerenciar TCCs
   - **Benefício:** Critico
   - **Funcionalidades**:
       1. Inclusão de Novos TCCs: Administrados entra com os dados do TCCs para cadastro.
          - Atores Envolvidos: Adminstrador
       2. Pesquisa/Listagem de TCCs: Usuario/Aluno entra com o titulo do TCC ou faz uso dos filtros e o sistema retorna o resultado ou possiveis resultados.
          - Atores Envolvidos: Usuario/Aluno e Repositório
       3. Alteração de TCCs: Altera qualquer informação do TCC ou versão dele.
          - Atores Envolvidos: Administrador
       4. Exclussão de TCCs: Excluir TCCs do sistema e suas informações.
          - Atores Envolvidos: Administrador

2. Gerenciar filtros dos TCCs
   - **Benefício:** Critico
   - **Funcionalidades**:
       1. Criar filtro: Cria novos filtros para TCCs.
          - Atores Envolvidos: administrador
       2. Alterar filtro: Altera informações de filtros cadastrados.
          - Atores Envolvidos: administrador
       3. Excluir filtro: Exclui um filtro existente.
          - Atores Envolvidos: administrador

3. Controle Usuario/Aluno
   - **Benefício:** Critico
   - **Funcionalidades**:
     1. Inclusão de Aluno: cadastra alunos matriculados na instituição.
        - Atores Envolvidos: Sistema
     3. Cadastro de Usuario: Usuario prenche o formulario de cadastro.
        - Atores Envolvidos: Sistema, Usuario
     4. Exclusão de Usuario: Muito tempo de inatividade.
        - Atores Envolvidos: Sistema
     5. Exclusão de Aluno: Jubilação do curso, saida da instituição ou conclusão do curso.
        - Atores Envolvidos: Sistema
     6. Alteração de Usuario/Aluno: Alteração em dados cadastrados pelo Usuario/Aluno.
        - Atores Envolvidos: Sistema, Usuario
     7. Favoritos Usuario/Aluno: Controla favoritos de cada usuario/aluno.
        - Atores Envolvidos

## 6. Proposta de Solução Tecnológica Escolhida
O desenvolvimento da aplicação utilizará a linguagem de marcação HTML(HyperText Markup Language), CSS (Cascading Style Sheets) juntos com linguagem de programação PHP(PHP Hypertext Preprocessor/Personal Home Page), javaScript, assim como bibliotecas Jquery, Bootstrap e outras que se enquadram às necessidades do desenvolvimento. Como banco de dados será utilizado o MySQL.

A aplicação será testada com testes unitários e com o público alvo para verificar a aceitação da aplicação.
      
      
## 7. Restrições
  
**Funcionais/Negócio** - O sistema não permite que perfis cadastrados tenham acesso aos dados de outros perfis cadastrados, a não ser que possuía acesso de administrador.

**Tecnológicas** - O aplicativo poderá ser executado nos navegadores, Microsoft Edge, Mozilla Firefox, Opera e Google Chrome.

**Operacionais** - A funcionalidade de favoritos só é permitido para perfis devidamente registrados no banco de dados. A lista de TCCs está disponível para todos os tipos de perfis, até para os não registrados.

<a name="glosario"/>

# Glossário

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

## 1. Informações Gerais
## 2. Definições

<a name="diagramas"/>

# Diagramas

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

1. Diagrama caso de uso
2. Diagrama UML 
   + Diagrama de Classe
   + Diagrama de Atividade
   + Diagrama de Sequencia
3. Diagrama Entidade Relacionamento
   + Diagrama Conceitual
   + Diagrama Lógico
<a name="requisitos_funcionais"/>

# Requisitos Funcionais

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

<a name="requisitos_nao_funcionais"/>

# Requisitos Não Funcionais

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

<a name="rnms"/>

# Especificações de Regras de Negócio e Mensagens do Sistema

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

## 1. Regras de Negócio
## 2. Mensagens do Sistema

<a name="prototipos"/>

# Protótipo
 
### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

 <a name="especificacoes_de_caso_de_uso"/>
 
# Especificação de Casos de Uso

 ### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

<a name="teste"/>

# Teste de Software

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|

## Plano de Teste
