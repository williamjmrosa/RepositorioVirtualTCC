# Repositório Virtual TCC

## Documentação do Sistema
 - [Documento de Visão](#ducumento)
 - [Glosarario](#glosario)
 - [Diagramas](#diagramas)
 - [Regras de Negócios e Mensagens do Sistema](#rnms)
 - [Protótipos](#prototipos)
 - [Requisitos Não Funcionais](#requisitos_nao_funcionais)
 - [Requisitos Funcionais](#requisitos_funcionais)
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

![Caso de Uso Repositorio VIrtual TCC](https://user-images.githubusercontent.com/24362264/235518720-adce7e25-7de6-4251-a3e0-ceeab1b67080.png)

3. Diagrama de Classe
4. Diagrama de Atividade
5. Diagrama de Sequencia
6. Diagrama Entidade Relacionamento
   + Diagrama Conceitual
   + Diagrama Lógico
   
<a name="requisitos_funcionais"/>

# Requisitos funcionais

## Atores do sistema

1. Visitante
2. Aluno
3. Bibliotecario
4. Administrador

## Gerenciar TCCs

**Resumo**: Responsavel por gerenciar as operações relacionadas aos TCCs.

**Atores**: Visitante ,Aluno ,Professor e Bibliotecario

  - **Listar TCCs**
  
    Atores: Visitante,Aluno,Professor
  
    Resumo: O Sistema permite a qualquer usuario logado ou não que veja os TCCs cadastrados no sistema.
    
  - **Buscar TCCs**
    
    Atores: Visitante,Aluno,Professor
    
    Resumo: O Sistema permite a qualquer usuario logado ou não buscar por TCCs.
    
  - **Add TCC**
  
    Atores: Bibliotecario
    
    Resumo: O Sistema permite a um bibliotecario logado e autenticado a cadastrar TCCs no sistema.
  
  - **Remover TCC**
  
    Atores: Bibliotecario
    
    Resumo: O Sistema permite a um bibliotecaro logado e autenticado a remover TCCs do sistema. 
  
  - **Alterar TCC**
  
    Atores: Bibliotecario
    
    Resumo: O Sistema permite a um bibliotecario logado e autenticado a alterar cadastros de TCCs.

## Gerenciar Alunos

**Resumo**: Reponsavel por gerenciar as operações relacionadas ao Aluno.

**Atores**: Administrador e Aluno

  - **Criar Aluno**
 
    Ator: Administrador
    
    Resumo: O sistema permite que um administrador logado e autenticado crie cadastros de alunos.
    
  - **Excluir Aluno**
 
    Ator: Administrador
    
    Resumo: O Sistema permite que um administrador logado e autenticado exclua um aluno.
    
  - **Buscar Aluno**
 
    Ator: Administrador
    
    Resumo: O sistema permite a um administrador logado e autenticado buscar por um aluno.
    
  - **Ver Aluno**
    
    Ator: Aluno
    
    Resumo: O Sistetema permite a um Aluno logado e autenticado ver seu cadastro.
    
  - **Alterar Aluno**

    Ator: Aluno
    
    Resumo: O Sistema permite a um Aluno logado e autenticado alterar seu cadastro.
    
## Gerenciar Filtros
 
**Resumo**: Responsavel por gerenciar as operações relacionadas aos filtros.
 
**Atores**: Todos
 
 - **Criar Filtros**
   
   Resumo: O Sistema permite que um bibliotecario ou adiministrador logado e autenticado cria um filtro.
 
   Altores: Bibliotecario e Administrador
 
 - **Buscar Filtros**
   
   Resumo: O sistema permite que qualquer usuario faça busca pelos filtros
 
   Atores: Todos
 
 - **Remover Filtros**
   
   Resumo: O Sistema permite que um bibliotecario ou administrador logado e autenticado remover um filtro.
 
   Atores: Bibliotecario e Administrador
 
 - **Alterar Filtro**
 
   Resumo: O Sistema permite que um bibliotecario ou administrador logado e autenticado alterar um filtro.
 
   Atores: Bibliotecario e Administrador
 
## Gerenciar Usuario
 
   **Resumo**: Responsavel por gerenciar as operações relacionadas ao usuario.
   
   **Atores**:Visitante e Administrador
   
   - **Cadastrar Usuario** 
       
       Resumo: O Sistema permite a um visitante não logado e autenticado criar um cadastro de usuario.
       
       Atores: Visitante
       
   - **Remover Usuario**
       
       Resumo: O Sistema permite a um visitante logado e autenticado excluir seu cadastro.
       
       Atores: Visitante
       
   - **Alterar Usuario**
       
       Resumo: O Sistema permite a um visitante logado e autenticado alterar seus dados de cadastro.
       
       Atores: Visitante
       
   - **Buscar Usuraio**
       
       Resumo: O Sitema permite ao administrador logado e autenticado buscar usuario.
       
       Atores: Administrador
       
## Favoritos

  **Resumo**: O Sistema permite aos usuario logados que acessem os seus TCCs marcados como favoritos.
  
  **Atores**: Visitante,Aluno e Professor.
  
## Salvar favoritos

  **Resumo**: O Sistema permite aos usuario logado marcar um TCC como favorito.
  
  **Atores**: Visitante,Aluno e Professor.
  
## Indicaões

  **Resumo**: O Sistema permite a um aluno logado ver as indicações de TCC de um professor.
  
  **Atores**: Aluno
  
## IndicarTCCs
  
  **Resumo**: O Sistema permite a um professor logado a fazer indicações de TCCs.
  
  **Atores**: Professor
  
## Logar-se

  **Resumo**: O Sistema deve permitir o login dos usuario e autenticalo de acordo.
  
  **Atores**: Todos
 
<a name="requisitos_nao_funcionais"/>

# Requisitos Não Funcionais

### Histórico da Revisão
Data|Versão|Descrição|Autor
-----|------|---------|-------
23/03/2023|1.0|Criando primeiro modelo|William José|
20/04/2023|1.1|Requisitos não funcionais|William José|

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

### RNG006

- O Sistema deve permitir salvar um TCC como favorito para todos os usuarios logados. Se o usuario não tiver logado, exibe mensagem [MSG006](#msg006)

### RNG007

- Ao fazer login se email ou senha forem inseridas incorretamente o sistema deve exibir a mensagem [MSG007](#msg007)

### RNG008

- Ao remeter um formulario de cadastro o sistema exibe a mensagem [MSG008](#msg008)

### RNG009

- Ao remeter um formulario de alteração de cadastro o sistema exibe a mensagem [MSG009](#msg009)

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

### MSG006

- Deve estar logado para salvar um favorito.

### MSG007

- Email e/ou senha incorreto

### MSG008

- Cadastro concluido com sucesso.

### MSG009

- Cadastro alterado com sucesso.

<a name="prototipos"/>

# Protótipo
 
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
