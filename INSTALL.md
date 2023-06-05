## Independent installation
### backend-server
#### composer packages
```
composer install --no-plugins --no-scripts
```
#### .env
`cp .env.example .env` and add Mysql information in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=openchat
DB_USERNAME=dbuser
DB_PASSWORD=dbpass
```
#### php artisan init
* `php artisan key:generate`
* `php artisan config:cache`
* `php artisan migrate --seed`
* `php artisan storage:link`
#### php server run:
`php artisan serve --host=0.0.0.0 --port=8000`
#### php server queue listen:
`php artisan queue:work --timeout=200`

#### change `/etc/hosts`, add:
`127.0.0.1   llm-server`

### llm-server
#### node modules
`npm install`
#### build next.js app
`npm run build`
#### start next.js app
`npm run start`
