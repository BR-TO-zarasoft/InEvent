## Como rodar com docker

Pré-requisitos: **Docker Desktop** em execução.

```bash
# 1. Configure o ambiente do backend
cp backend/.env.example backend/.env

# 2. Edite backend/.env e informe sua chave da OpenWeather
#    OPENWEATHER_API_KEY=...

# 3. Suba os containers (mysql, backend, frontend, nginx)
docker compose up --build -d

# 4. Acesse
open http://localhost:8080
```

## Informações Importantes

### 1. Tratamento de erros

- Todos os erros que ocorrerem na api, são padronizados e retornados para o frontend por meio do middleware ApiErrorHandler
- Todos os erros que chegarem ao front, são recebidos pelo interceptor global, não sendo necessário uma tratativa individual
- Api utiliza Controller -> Service -> Repository apesar de ser uma aplicação pequena é um fluxo que possibilita melhor a escalada de regras
- A API tem job para mudança de status automaticamente por data para os eventos
- A aplicação bloqueia a edição de um evento se o mesmo já estiver finalizado
- A aplicação é enxuta em relação as regras de negocio