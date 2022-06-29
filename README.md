## Setup

The setup for the app is quite simple:
- `git clone https://github.com/richbowen/laravel-developer-task.git`
- `composer install`
- `cd laravel-developer-task`
- `cp .env.example .env`; create your database and `php artisan migrate`
- `php artisan key:generate`
- `yarn` then`yarn run prod`
- Serve the application via `php artisan serve` or if you have valet installed, https://laravel-developer-task.test

That's it.
