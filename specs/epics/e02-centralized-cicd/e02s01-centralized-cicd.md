# Story e02s01: Migrate Workflows to Reusable Centralized Templates with `big-release`

**type:** refactor  
**risk:** P1  
**context:** infra  
**Context**: Adapt `bigbase-canary-php` to consume centralized reusable CI/CD workflows published in `danielvm-git/.github` (v4.1.0 update, Discussion #32), replacing custom local workflow files with reusable workflow calls while preserving `big-release` execution.

## Requirements

#### MODIFIED: CI/CD Pipeline Workflow Inheritance
**Before:** `.github/workflows/test-build-release.yml` and `.github/workflows/deploy.yml` contained standalone, custom workflow logic authored locally.  
**After:** Workflows call upstream `danielvm-git/.github/.github/workflows/test-build-release-php.yml@main` (and associated deploy workflow) passing `release_tool: "big-release"` and `site_url: "https://php.bigbase.click"`.

## Implementation Steps

1. Configure `.github/workflows/ci-cd.yml` to call `danielvm-git/.github/.github/workflows/test-build-release-php.yml@main` with `site_url: "https://php.bigbase.click"` and `release_tool: "big-release"` → verify: `grep -q 'uses: danielvm-git/\.github/\.github/workflows/test-build-release-php\.yml@main' .github/workflows/*.yml`
2. Validate workflow input parameter `release_tool: "big-release"` → verify: `grep -q 'release_tool: "big-release"' .github/workflows/*.yml`
3. Execute preflight validation suite to ensure local linting and tests pass cleanly → verify: `composer lint && composer test`

## Verification Script (Step-by-Step)

1. Inspect `.github/workflows/ci-cd.yml` to confirm it targets the centralized `danielvm-git/.github` repository.
2. Confirm `release_tool: "big-release"` is set in the `with:` block.
3. Run `composer lint` to verify PHP lint checks.
4. Run `composer test` to verify PHPUnit test suite.

## Out of Scope

- Modifying upstream reusable workflows inside `danielvm-git/.github`.
- Changing application code in `index.php` or unit tests in `tests/`.

## Risks

- Upstream workflow branch reference (`@main`) must be accessible to GitHub Actions runtime.
- Environment secrets (`BIGBASE_SITE_ID`, `BIGBASE_DEPLOY_TOKEN`, `GITHUB_TOKEN`) must be passed via `secrets: inherit`.
