# Consefeedz

## Instalar as dependências

1. `$ cd consefeedz`
2. `$ composer install`
3. `Executar base.sql no mysql localizado dentro de database\base.sql`
4. `Configurar os dados de acesso e base de dados no arquivo src/config.php`

### Rodar projeto:

1. `$ cd my-app`
2. `$ php -S 0.0.0.0:8888 -t public public/index.php`
3. Browse to http://localhost:8888

## Diretórios

- `app`: Código da aplicação
- `app/src`: Classes com namespace APP
- `app/templates`: Templates do TWIG
- `cache/twig`: Arquivos de cache
- `log`: Arquivos de LOG
- `public`: Diretório padrão
- `vendor`: Dependências do Composer