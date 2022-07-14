## Setup

The setup for the app is quite simple:
- `git clone https://github.com/richbowen/laravel-developer-task.git`
- `cd laravel-developer-task`
- `composer install`
- `cp .env.example .env`; create your database and `php artisan migrate`
- `php artisan key:generate`
- `yarn` then`yarn run prod`
- Serve the application via `php artisan serve` or if you have valet installed, https://laravel-developer-task.test
- Seed the database. Everything `php artisan db:seed`, or individually, `php artisan db:seed --class={Field,Subscriber}Seeder`

That's it.

## API Documentation

There's some basic documentation for the API at the root API endpoint, `/api`.

**Examples**

- Filter where `state` is unconfirmed and where `name` has 'Fan' in it:
```
/api/subscribers?filter[state]=unconfirmed&filter[name]=Fan
```

- Sort by the `state` where the `email` address has 'gmail' in it:
```
/api/subscribers?filter[email]=gmail&sort=-state
```

