# e01s01 — Stand up the PHP canary site end-to-end

## Story

As the bigbase platform maintainer, I want a minimal PHP site deployed through the real
big-release + bigbase-deploy pipeline, so that any future change to either tool has an
immediate, low-noise regression signal — and so that app_type: php, which is fully
implemented in bigbase's deploy engine but has never been exercised through real CI, is
finally proven end-to-end.

## Acceptance criteria

1. `GET /` on the running app returns HTML containing the exact contents of `VERSION`.
2. `test-build-release.yml` lint/test job passes on a push to `main` (authored from scratch, no upstream template).
3. The `release` job runs `big-release release --verbose` and produces a real git tag (`v0.1.0`) + `CHANGELOG.md` entry.
4. `deploy.yml` fires via `workflow_run` and its `bigbase-deploy@v1` step returns HTTP 2xx with `app_type: php`.
5. The deploy job's health-check step logs `✅ Site LIVE`.
6. `curl https://php.bigbase.click` returns the footer with the deployed `VERSION` — content-verified, and cross-checked against `GET /api/sites/{id}` showing `status: "running"`.
7. If the deploy fails with a `tool_missing` error for php/composer, that's the real, narrow finding — file it as a bigbase bug (not "php unsupported"), don't treat it as blocking this story's CI/template work.
8. A second Conventional Commit produces `v0.1.1` and a fresh deploy.
9. Once green, `test-build-release.yml`/`deploy.yml` get promoted into `danielvm-git/.github`'s `workflow-templates/` as the fifth official pair (tracked as a separate shared-resource task).

## Out of scope

Anything in `specs/product/SCOPE_LATEST.yaml`'s `out_of_scope` list.
