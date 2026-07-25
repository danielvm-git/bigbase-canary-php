# Conventions — bigbase-canary-php

Governed by the [bigpowers](https://github.com/danielvm-git/bigpowers) methodology. This file is the project-local subset relevant to a minimal single-route canary — see bigpowers `CONVENTIONS.md` for the full doctrine.

## Conventional Commits & Semantic Versioning

All changes MUST follow [Conventional Commits 1.0.0](https://www.conventionalcommits.org/en/v1.0.0/). Versioning follows [Semantic Versioning 2.0.0](https://semver.org/), decided at release time by `big-release` from commit history — never hand-tracked.

**Format:** `<type>(<scope>): <description>` (space after colon mandatory).

## GitHub & Git Operations

- No direct work on `main`. Every task starts with a feature branch/worktree via `kickoff-branch`.
- Integrate (solo-git profile): `bash scripts/land-branch.sh <branch> "<conventional message>"` after `release-branch` gates.
- **Git Attribution:** NEVER include `Co-authored-by:` or any AI-agent attribution footer.
- Never call GitHub REST API directly (curl/fetch) — use `gh`. (bigbase's own REST API is a separate service and is fine to curl directly for provisioning.)
- Never create GitHub issues from automated workflows — produce local `.md` files in `specs/bugs/` instead.

## Pre-commit hook (`hook-commits`, adapted)

Fresh clone: `git config core.hooksPath .githooks`. `.githooks/pre-commit` runs `scripts/preflight.sh` before every commit.

## Always Green / Shift Left

**Preflight:** `composer lint && composer test` — must pass before kickoff, develop, or verify phases advance.

**CI green:** `gh pr checks` (or the Actions tab) must show passing before merge/land.

## Discovered Defects

1. **quick-fix** — trivial, single-file fixes within guardrails.
2. **fix-bug** — needs investigation (`specs/bugs/BUG-*.md` + TDD).
3. **Log** — only when reproduction is blocked after a good-faith attempt.

**Banned dismissive phrases:** "pre-existing", "unrelated to this session", "not introduced by my changes", "out of scope" (when ignoring a red gate).

## specs/ — All Planning Output Goes Here

- `specs/state.yaml`, `specs/release-plan.yaml`, `specs/execution-status.yaml`
- `specs/epics/e01-canary-site/` — this repo's one epic
- `specs/bugs/BUG-*.md` + `specs/bugs/registry.yaml` — never GitHub issues
- `specs/tech-architecture/tech-stack.md`

## Defensive Code Categories

None apply. Static version-footer endpoint, no untrusted input beyond the HTTP request, no external dependencies to guard.

## Known platform gotchas (learned on Go/Python/Node canaries — see the cross-repo plan)

- **PHP is NOT affected by the PORT env var issue** that hit Go and Node — bigbase's deploy engine (`components/deploy/engine.go`) runs `php -S 0.0.0.0:{port}` directly with the port as a CLI argument. Nothing to fix here, but worth knowing why PHP is different.
- **Patch `SITE_URL` in both CI workflow files.** No upstream template exists for PHP, so this repo authors both from scratch — get it right the first time by grepping for any placeholder before landing: `grep -rn CHANGE-ME .github/workflows/*.yml` must return nothing.
- **The CI "Preflight" step runs before dependency installation in the org's template pattern** (confirmed on Node: `npm test` failed with MODULE_NOT_FOUND because `scripts/preflight.sh` ran before `npm ci`). For PHP, mirror this defensively: `scripts/preflight.sh` should run `composer install` itself if `vendor/` is missing, not assume it's already there.
- **Verify by content, not just HTTP status.** A now-fixed bigbase bug (port allocator, `BUG-2026-07-25`) could make a failed deployment's proxy silently serve a *different* site's content with HTTP 200 — this was live and hit two of the four canaries before being fixed and deployed 2026-07-25. Always confirm the response body actually contains this app's own footer, and cross-check `GET /api/sites/{id}` shows `status: "running"` with the right `commit_sha`.
- **`components/deploy/engine.go` requires PHP + Composer on the deploy host.** If missing, deploy fails with a `tool_missing` coded error (`Install Composer (and PHP) on the deploy host before deploying AppType=php`). If hit, that's the real, narrow finding to investigate in `bigbase` — not "PHP unsupported" (the AppType handling itself is fully implemented and tested).

## Risk Tier

P2 — infrastructure/regression-signal repo. `plan-tests` still runs but BCP sizing stays lightweight.
