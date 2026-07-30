---
bug_id: BUG-2026-07-30T090000
status: open
severity: high
scope: ci
title: VERSION file goes stale after release tag cut
---

# BUG-2026-07-30T090000: VERSION file goes stale on subsequent release tag cuts

## Problem

When `big-release` cuts a new version tag (e.g., `v0.1.1`), the `VERSION` file in the repository root (which `index.php` reads at runtime to render the version footer) remains unchanged. Nothing updates `VERSION` post-release, so the deployed footer silently freezes at the initial version (`0.1.0`).

In addition, `tests/FooterTest.php` uses a synthetic temporary fixture file rather than testing the real `VERSION` file, missing drift detection in tests.

## Security Impact

Security impact: NONE — no security exploit path identified.

## Root Cause Analysis

- `big-release` computes and tags the next version and updates `CHANGELOG.md`, but does not mutate `VERSION`.
- `.github/workflows/test-build-release.yml`'s `release` job runs `big-release`, but does not have a post-release step to get the latest git tag (`git describe --tags --abbrev=0 | sed 's/^v//'`), write it to `VERSION`, and commit/push with `[skip ci]`.
- Risk level: Medium.

## TDD Fix Plan

1. **RED**: Add an integration test in `tests/FooterTest.php` that validates the repository's root `VERSION` file directly (reads `VERSION`, asserts non-empty, valid semver format `x.y.z`).
   **GREEN**: Ensure `VERSION` exists and is formatted as valid semver.
   **verify**: `composer test`

2. **RED**: Add post-release `Update VERSION file` step to `.github/workflows/test-build-release.yml` after `Run big-release`:
   ```yaml
   - name: Update VERSION file
     run: |
       NEW_VER=$(git describe --tags --abbrev=0 | sed 's/^v//')
       echo "$NEW_VER" > VERSION
       git config user.name "big-release-bot"
       git config user.email "big-release-bot@danielvm-git.github.io"
       git add VERSION
       git commit -m "chore(release): update VERSION to $NEW_VER [skip ci]" || echo "No VERSION change"
       git push origin main
   ```
   **GREEN**: Apply step in `.github/workflows/test-build-release.yml`.
   **verify**: `grep -q "Update VERSION file" .github/workflows/test-build-release.yml`

## Acceptance Criteria

- [x] Integration test in `tests/FooterTest.php` validates root `VERSION` file format.
- [x] `.github/workflows/test-build-release.yml` includes post-release step to update `VERSION` and push `[skip ci]`.
- [x] All unit and integration tests pass cleanly via `composer test`.
- [x] `composer lint` passes cleanly.

## Resolution

<!-- filled in by validate-fix -->
