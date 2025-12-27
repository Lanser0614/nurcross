<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Local development with Docker Compose

1. Copy the environment template:
   ```bash
   cp .env.example .env
   ```
2. Install PHP dependencies inside the container (requires Docker):
   ```bash
   docker compose run --rm app composer install
   ```
3. Start the containers:
   ```bash
   docker compose up -d
   ```
4. Generate the application key and run database migrations inside the running container:
   ```bash
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
   ```
5. Install frontend dependencies using the Node service:
   ```bash
   docker compose run --rm node npm install
   ```
6. Run the Vite dev server inside the Node container (port 5173 is forwarded by default):
   ```bash
   docker compose run --rm -p ${VITE_PORT:-5173}:5173 node npm run dev -- --host 0.0.0.0 --port ${VITE_PORT:-5173}
   ```
   You can also build assets without the dev server:
   ```bash
   docker compose run --rm node npm run build
   ```
7. Visit the application at http://localhost:${APP_PORT:-8080}.

## CI/CD

- Continuous integration and delivery run through [GitHub Actions](.github/workflows/ci-cd.yml).
- Pushes and pull requests to `master` (and maintenance branches matching `*.x`) run backend tests on PHP 8.2 and build the Vite assets with Node 20.
- Successful runs on `master` also package a deployable tarball (vendor dependencies, compiled assets, and application source) as an artifact named `release-bundle` for downstream deployment.
- When deployment secrets are present, successful `master` runs automatically transfer the `release-bundle` to the production server, extract it, run database migrations, and warm Laravel caches.

### Deploy secrets

Add the following repository secrets to enable the production deployment stage:

- `DEPLOY_HOST`: SSH host for the production server.
- `DEPLOY_USER`: SSH user for deployment.
- `DEPLOY_PORT`: SSH port (set to `22` if not using a custom port).
- `DEPLOY_PATH`: Absolute path on the server where the release should be copied and extracted.
- `DEPLOY_SSH_KEY`: Private SSH key used for authentication.

Ensure the target path already contains a valid `.env` file and correct permissions for the web server/PHP-FPM user.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
