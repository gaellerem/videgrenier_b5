# 📦 Docker Configuration

Ce dossier contient les fichiers nécessaires pour lancer l'application dans différents environnements à l'aide de Docker.

## 📁 Structure

```
docker/
├── dev/
│   ├── docker-compose.yml
│   └── .env
├── prod/
│   ├── docker-compose.yml
│   └── .env
```

- `dev/` : Environnement de développement (code monté en volume, logs visibles, erreurs affichées, etc.)
- `prod/` : Environnement de production (images optimisées, erreurs cachées, configuration sécurisée)

---

## 🚀 Lancer l'application

### Environnement de développement

```bash
cd docker/dev
docker compose -p videgrenier-dev --env-file .env up --build -d
```

### Environnement de production

```bash
cd docker/prod
docker compose -p videgrenier-prod --env-file .env up --build -d
```

---

## 🔐 Variables d'environnement

Les fichiers `.env` contiennent les informations sensibles (base de données, mot de passe, etc.).  
**Ne pas les versionner** dans Git. Utiliser plutôt des modèles :

```
/docker/dev/.env
/docker/prod/.env
```

Exemple de `.env` :

```env
APACHE_CONF=[dev|prod].conf
APP_ENV=[dev|prod]
SERVER_PORT=

DB_PORT=
DB_HOST=
DB_ROOT_PASSWORD=
DB_NAME=
DB_USER=
DB_PASSWORD=
SHOW_ERROS=[true|false]
SQL_FILE=../../sql/[dev|prod]_import.sql
```
