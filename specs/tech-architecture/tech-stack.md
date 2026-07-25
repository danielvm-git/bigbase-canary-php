# Tech Stack — bigbase-canary-php

- **Language/runtime:** PHP 8.3+, no framework, Composer for dependency/script management.
- **Layout:** `index.php` — reads `VERSION`, writes an HTML footer. `tests/FooterTest.php` (PHPUnit) covers the footer-building logic via an extracted pure function.
- **CI:** GitHub Actions, `.github/workflows/test-build-release.yml` + `.github/workflows/deploy.yml`, authored from scratch (no upstream `.github` template exists for PHP), mirroring the Python pair's job shape.
- **Release:** [big-release](https://github.com/danielvm-git/big-release).
- **Deploy:** `danielvm-git/.github/actions/bigbase-deploy@v1` → bigbase site `php` → `https://php.bigbase.click`, `app_type: php`.
- **Not affected by PORT env var handling** — bigbase's `engine.go` runs `php -S 0.0.0.0:{port}` directly with the port as a CLI arg.
- **Deploy-host risk:** `components/deploy/engine.go` requires `php`/`composer` on the deploy host — real risk, not yet confirmed present or absent.
