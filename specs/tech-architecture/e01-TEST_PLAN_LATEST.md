# Test Plan — e01 Canary site scaffold (PHP)

Risk: P1.

| ID | Scenario | Level | Priority |
|----|----------|-------|----------|
| SC-e01s01-P0-01 | Footer-building function returns HTML containing the exact `VERSION` contents | unit (PHPUnit) | P0 |
| SC-e01s01-P0-02 | `php -l index.php` (lint) passes with no syntax errors | static | P0 |
| SC-e01s01-P1-01 | `test-build-release.yml` `test` job passes on push to `main` | CI | P1 |
| SC-e01s01-P1-02 | `release` job runs `big-release`, produces tag `v0.1.0` + CHANGELOG entry | CI | P1 |
| SC-e01s01-P1-03 | `deploy.yml` fires via `workflow_run` after Test Build Release succeeds on `main` | CI | P1 |
| SC-e01s01-P1-04 | `bigbase-deploy@v1` with `app_type: php` returns HTTP 2xx; health check logs `✅ Site LIVE` | CI | P1 |
| SC-e01s01-P1-05 | `curl https://php.bigbase.click` returns the deployed VERSION in the footer (body content) | manual | P1 |
| SC-e01s01-P2-01 | A second commit produces `v0.1.1` and a fresh successful deploy | manual | P2 |
| SC-e01s01-P2-02 | If deploy fails with `tool_missing` (php/composer absent on host), the exact error is captured for a bigbase bug report | manual (conditional) | P2 |

Fixtures: none — `VERSION` file is the only test input.
