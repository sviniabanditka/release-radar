# Setup
- Setup virtualhost to `public` folder
- `cd project_folder`
- `composer install`
- `php artisan migrate`
- `php artisan db:seed --class=AuthSeeder`

#Commands
- `php artisan spotify:sync_following` - sync users artists list
- `php artisan spotify:sync_releases` - sync artists releases
- `php artisan telegram:notify` - notify users in telegram
