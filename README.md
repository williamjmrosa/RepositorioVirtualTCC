# Repositório Virtual TCC

## Documentação do Sistema
 - [Documento de Visão](#ducumento)
 - [Glosarario](#glosario)
 - [Diagramas](#diagramas)
 - [Regras de Negócios e Mensagens do Sistema](#rnms)
 - [Protótipos](#prototipos)
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
24/03/2023|1.1|Entrada parcial modelo caso de uso|William José|

1. Diagrama caso de uso

![Caso de Uso Repositorio VIrtual TCC](https://user-images.githubusercontent.com/24362264/227618506-ea838534-8c40-4e43-ac6c-f29ea33cd017.png)


3. Diagrama de Classe
4. Diagrama de Atividade
5. Diagrama de Sequencia
6. Diagrama Entidade Relacionamento
   + Diagrama Conceitual
   + Diagrama Lógico

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|
20/04/2023|1.1|Requisitos não funcionais|William José|

<a name="requisitos_nao_funcionais"/>

# Requisitos Não Funcionais

## Usabilidade  
 
* RNF001. Os usuarios podem operar o sistema de forma intuitiva.

## Confiabilidade
  
* RNF002. O sistema deverá ter alta disponibilidade, p.exemplo, 99% do tempo.

## Performance  
 
* RNF003. O sistema deverá processar n requisições por um determinado tempo.

## Suportabilidade
 
* RNF004. Sendo a base do ? em Web, o sistema deverá executar em qualquer navegador recomendado.

## Interfaces com Usuário

* RNF005. A interface deve ser amigável aos usuários não tão experientes com comandos intuitivos de fácil acesso.

## Consistência das Interfaces com Usuário

## Interfaces com Sistemas Externos

* RNF006. Não ira se comunicar com nenhum sistema externo.

## Interfaces de Hardware

* RNF007. Sem interface.

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
20/04/2023|1.1|Regras de negócio|William José

## 1. Regras de Negócio

### RNG001

- O Sistema permite permite permite o cadastro apenas de visitantes. Caso o visitante tente cadastrar com um CPF ou email já cadastrados, exibe a mensagem. [MSG001](#msg001),[MSG002](#msg002)

### RNG002

- O Sistema permite que apenas o administrador e Bibliotecario cadastre alunos e professores no sistema. O cadastro de aluno ou professor será feito por email institucional, se um e-mail não institucional for inserido, exibe a mensagem. [MSG003](#msg3) e se o email já foi cadastrado exibe a mensagem. [MSG002](#msg002)

### RNG003

- O sistema permite que apenas o os alunos acessem as indicações. Caso ocorra uma tentativa de outro usuario exibe a mensagem. [MSG004](#msg4)

### RNG004

- O sistema deve impedir acesso a areas em que se deve estar logado. Caso ocorra a tentativa de acesso exibe a mensagem. [MSG005](#msg005)

### RNG005

- O Sistema deve permitir visualizar e buscar TCCs sem estar logado. Mas o filtro pelos favoritos ou indicações exige que se esteja logado. Caso não esteja exibe a mensagem [MSG005](#msg005)  

## 2. Mensagens do Sistema

### MSG001

- CPF já cadastrado no banco de dados

### MSG002

- Email já cadastrado no banxo de dados

### MSG003

- Email não é um email institucional

### MSG004

- Usuario deve estar logado como Aluno

### MSG005

- Deve estar logado para acessar.

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
