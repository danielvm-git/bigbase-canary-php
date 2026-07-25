# story: e01s01
# bigbase-canary-php — AI Agents

> **Multi-agent context** — This file is the canonical project context for **Cline**, **Aider**, **OpenCode**, and other AGENTS.md-native tools. Claude Code and Cursor read it via the `CLAUDE.md` symlink.

Read CONVENTIONS.md before any GitHub or git operation.

<!-- BEGIN bigpowers:context-routing -->
## Context Routing

| Glob / trigger | Load first |
|-----------------|------------|
| `specs/epics/**` | Capsule `epic.yaml` + active story `-tasks.yaml` |
| `specs/tech-architecture/**` | `tech-stack.md` |
| Default / session start | This file → `CONVENTIONS.md` → `specs/state.yaml` |
<!-- END bigpowers:context-routing -->

<!-- BEGIN bigpowers:learned-preferences -->
## Learned User Preferences

- (none yet — updated via `session-state`)

## Workspace Facts

- (none yet — durable facts discovered across sessions)
<!-- END bigpowers:learned-preferences -->

<!-- BEGIN bigpowers:project -->
## Project

A minimal PHP HTTP canary site whose sole job is to be a regression signal proving that [big-release](https://github.com/danielvm-git/big-release) and the [bigbase-deploy](https://github.com/danielvm-git/.github) GitHub Action still work together end-to-end — and specifically to prove `app_type: php` support (real in bigbase's deploy engine, but with no upstream CI template and an action/contract doc that don't mention it yet). Not a product — deliberately as small as possible.
Stack: PHP 8.3+, no framework, Composer for dependency/script management only.

## Commands

| Action | Command |
|--------|---------|
| Run | `php -S localhost:8080` |
| Test | `composer test` (PHPUnit) |
| Build | N/A — no build step |
| Lint | `composer lint` (`php -l`) |
| Preflight | `composer lint && composer test` |
| CI | `gh pr checks` (when a PR is open) |

## Test

`composer test`

## Lint

`composer lint`

## Build

N/A

## Architecture

`index.php`: reads the `VERSION` file at request time and renders it into an HTML footer. `tests/FooterTest.php` covers the version-formatting logic via a small extracted function (`index.php` itself isn't unit-testable directly since it executes top-level).

## Observability

| What | Command |
|------|---------|
| Is the pipeline green? | `gh run list -R danielvm-git/bigbase-canary-php --limit 2` |
| Is the site live and on the expected version? | `curl -s https://php.bigbase.click` (footer shows the deployed `VERSION`) |
| Did the last deploy pass its health check? | Check the `Health check` step log in the `Deploy` workflow run — `✅ Site LIVE (HTTP 200)` or an `::error::` line |

No structured logging is wired in — the app has no logic worth logging beyond serving one static-ish response.

## Conventions

- **PHP is not affected by the PORT-env-var issue that bit Go/Node** — bigbase's deploy engine runs `php -S 0.0.0.0:{port}` directly, passing the port on the command line. Nothing in the app needs to read an env var for this.
- Keep `index.php` a single minimal file — no premature framework adoption.
- `VERSION` file is the only source of the running version at runtime; the real, authoritative version lives in git tags cut by `big-release` — never hand-edit `CHANGELOG.md`.
- Conventional Commits on every commit; no `Co-Authored-By:` trailers (CI rejects them).
- No upstream `test-build-release-php.yml`/`deploy-php.yml` templates exist — author them here first, then they get promoted into `danielvm-git/.github`'s shared templates once proven green.

## Never

- Never dismiss reproducible gate failures as pre-existing or out of scope
- Never proceed on red Preflight or red CI — invoke quick-fix or fix-bug first
- Never push directly to `main` — every change starts with `kickoff-branch` and lands via `release-branch` (solo-git `land-branch.sh`)
- Never add real product features (auth, persistence, routing, UI) — this repo exists only to exercise the release→deploy pipeline

## Agent Rules

- **Workflow Mandate:** Use bigpowers skills (e.g. `plan-work`, `develop-tdd`) for structured work.
- **Always Green:** Preflight and CI must be green before forward work.
- Read specs/ and CONVENTIONS.md before writing code.
- Write the minimum code that solves the stated problem.
- Run tests after every change. Show evidence before declaring done.
- All planning output goes in specs/.
<!-- END bigpowers:project -->
