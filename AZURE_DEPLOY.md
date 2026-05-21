# Despliegue de CineLog en Azure App Service

## Variables de entorno

Copiar tal cual en **App Service → Settings → Environment variables → Application
settings** (una entrada por línea, separando nombre y valor):

```
APP_NAME=CineLog
APP_ENV=production
APP_KEY=base64:Epoff0xS7LxNYRyrqw++Ak6REUWls4SaaR/GUkihgaM=
APP_DEBUG=false
APP_URL=https://cinelog-api.azurewebsites.net
LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/home/site/wwwroot/database/database.sqlite
DB_FOREIGN_KEYS=true

BROADCAST_DRIVER=log
CACHE_STORE=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

FRONTEND_URL=https://cinelog-frontend.azurestaticapps.net
```

## Notas sobre estas variables

- **APP_KEY** ya generada con `php artisan key:generate --show`. No está en ningún
  fichero del repo — solo aquí, en este documento, que tampoco se versiona si lo
  excluyes (ver más abajo).
- **DB_CONNECTION=sqlite** con ruta absoluta a `/home/site/wwwroot/database/database.sqlite`.
  El fichero lo crea `startup.sh` en el primer arranque (`touch`) y `php artisan migrate
  --force` aplica el esquema.
- **No hay variables de Sanctum SPA** (`SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN`,
  `SESSION_SAME_SITE`) porque el frontend autentica con `Authorization: Bearer <token>`.
- **No hay variables de MySQL/Postgres**.
- **`SESSION_DRIVER=file`** está solo porque Laravel lo lee aunque no lo uses (las
  rutas API no inician sesión web). Si lo quitas, algunas peticiones podrían fallar
  al instanciar el guard.

## Si quieres que `AZURE_DEPLOY.md` no llegue al repo

```
echo AZURE_DEPLOY.md >> .gitignore
```

(Recomendado: la `APP_KEY` no es ultra-secreta — funciona como salt para sesiones y
firmados — pero tampoco hace falta tenerla en GitHub.)
