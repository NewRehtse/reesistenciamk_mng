# Backend

Backend para gestionar la entrada y salida de elementos imprimibles por el movimiento Reesistencia.


### Requisitos

* Mysql / Sqlite (local)
* Php 7.2
* Yarn
* Symfony 4.4

## Despliege local

var directory must be writable
Copy .env.dist to .env and run symfony:
Install vendors
Run

1. Copy .env.dist to .env
```bash
cp .env.dist .env
```

2. Install vendors with composer
```bash
composer install
```

3. Create db  scheme with doctrine
```bash
php bin/console doctrine:schema:create
```

4. Insert some data (answer yes)
```bash
php bin/console doctrine:fixtures:load
```
5. Install assets
```bash
yarn install
yarn encore dev
```

6. Run symfony
```bash
symfony server:start
```

You can see it in:
http://localhost:8000

Usuarios: 
Maker: mk@makers.es pass mk
Admin: admin@makers.es pass admin
