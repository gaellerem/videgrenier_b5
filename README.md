# Vide Grenier en Ligne

Ce Readme.md est à destination des futurs repreneurs du site-web Vide Grenier en Ligne.

## Mise en place du projet back-end

1. Créez un VirtualHost pointant vers le dossier /public du site web (Apache)
2. Importez la base de données MySQL (sql/import.sql)
3. Connectez le projet et la base de données via les fichiers de configuration
4. Lancez la commande `composer install` pour les dépendances

## Mise en place du projet front-end
1. Lancez la commande `npm install` pour installer node-sass
2. Lancez la commande `npm run watch` pour compiler les fichiers SCSS

## Routing

Le [Router](Core/Router.php) traduit les URLs. 

Les routes sont ajoutées via la méthode `add`. 

En plus des **controllers** et **actions**, vous pouvez spécifier un paramètre comme pour la route suivante:

```php
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
```


## Vues

Les vues sont rendues grâce à **Twig**. 
Vous les retrouverez dans le dossier `App/Views`. 

```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Toto',
    'colours' => ['rouge', 'bleu', 'vert']
]);
```
## Models

Les modèles sont utilisés pour récupérer ou stocker des données dans l'application. Les modèles héritent de `Core
\Model
` et utilisent [PDO](http://php.net/manual/en/book.pdo.php) pour l'accès à la base de données. 

```php
$db = static::getDB();
```

## Lancement Docker
Les fichiers .env doivent être créés dans les dossiers docker/dev et docker/prod
Ils doivent contenir ces informations (⚠️ ne pas mettre d'espace après le "=")
```txt
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
Pour lancer :
```bash
./start.sh [dev|prod]
```

## Lancement des tests unitaires
```bash
 ./vendor/bin/phpunit tests
```

## Documentation API
La documentation OpenAPI est disponible à l'endpoint suivant :

/swagger-ui/

Le fichier source est situé dans public/swagger-ui/openapi.json.