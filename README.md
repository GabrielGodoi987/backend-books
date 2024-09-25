# Sistema de Gerenciamento de Produtos com Log de Operações

Este projeto implementa um sistema de gerenciamento de produtos utilizando o banco de dados SQLite e registra logs de operações CRUD realizadas nos produtos. Utiliza validações para garantir a integridade dos dados e oferece endpoints para interagir com o sistema via API RESTful.

## Funcionalidades

- **Gerenciamento de Produtos**: Criação, leitura, atualização e exclusão de produtos no banco de dados.
- **Validação de Campos**:
  - O nome do produto deve ter no mínimo 3 caracteres.
  - O preço do produto deve ser um valor positivo.
  - O estoque do produto deve ser um número inteiro maior ou igual a zero.
- **Registro de Logs**: Toda operação CRUD realizada em um produto é registrada em uma tabela de logs.

## Estrutura do Banco de Dados

O banco de dados utilizado é o SQLite. Para facilitar a criação e manipulação do banco e tabelas, utilize o programa [DB Browser for SQLite](https://sqlitebrowser.org/).

### Tabelas

#### Produto
- **id** (INTEGER, PK)
- **nome** (TEXT, obrigatório, mínimo 3 caracteres)
- **descricao** (TEXT)
- **preco** (REAL, obrigatório, valor positivo)
- **estoque** (INTEGER, obrigatório, maior ou igual a 0)
- **userInsert** (INTEGER, obrigatório, FK PARA USUÁRIOS)
- **data_hora** (DATETIME, obrigatório)

#### Log
- **id** (INTEGER, PK)
- **acao** (TEXT, obrigatório, valores: 'CREATE', 'UPDATE', 'DELETE')
- **data_hora** (DATETIME, obrigatório)
- **produto_id** (INTEGER, FK PARA PRODUTOS)
- **userInsert** (INTEGER, obrigatório, FK PARA USUÁRIOS)

## Endpoints

### Produtos

#### 1. Listar todos os produtos
**GET /produtos**

Retorna uma lista com todos os produtos cadastrados.

#### 2. Obter produto por ID
**GET /produtos/{id}**

Retorna os detalhes de um produto específico com base no ID fornecido.

#### 3. Criar novo produto
**POST /produtos**

Cria um novo produto no banco de dados.

- Validações:
  - Nome: mínimo 3 caracteres.
  - Preço: valor positivo.
  - Estoque: número inteiro maior ou igual a zero.

**Exemplo de corpo da requisição**:
```json
{
  "nome": "Produto Teste",
  "descricao": "Descrição do produto",
  "preco": 100.50,
  "estoque": 10,
  "userInsert": "admin"
}
