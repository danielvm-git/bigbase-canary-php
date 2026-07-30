# bigbase-canary-php

A minimal PHP HTTP canary site that proves [bigbase-deploy](https://github.com/danielvm-git/.github) and [big-release](https://github.com/danielvm-git/big-release) still work end-to-end for `app_type: php`.

Not a product — deliberately as small as possible.

## Quick start

```sh
composer install
php -S localhost:8080
```

Visit `http://localhost:8080`.

## Commands

| Action     | Command                        |
|------------|--------------------------------|
| Run        | `php -S localhost:8080`        |
| Test       | `composer test`                |
| Lint       | `composer lint`                |
| Preflight  | `composer lint && composer test` |

## How it works

`index.php` reads the `VERSION` file at request time and renders it into an HTML footer. `tests/FooterTest.php` covers the version-formatting logic.

## License

[MIT](LICENSE)
