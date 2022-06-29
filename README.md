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

## API Documentation

There's some basic documentation for the api at the root API endpoint, /api.

**Examples**

- Filter for unconfirmed states, where the name has 'Fan' in it:
```
/api/subscribers?filter[state]=unconfirmed&filter[name]=Fan
```

- Sort by the state where the email address is gmail:
```
/api/subscribers?filter[email]=gmail&sort=-state
```

