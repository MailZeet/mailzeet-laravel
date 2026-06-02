# CLAUDE.md

Guidance for Claude Code and other AI agents working in this repository.

## Project overview

This repo is the official Laravel SDK for the MailZeet email delivery API. It is a Composer package (`mailzeet/mailzeet-laravel`) that wraps the core `mailzeet/mailzeet-php` client and provides a Laravel service provider, Facade, queued job, and Artisan install command. Laravel applications require it via Composer to send transactional emails through the MailZeet API.

## Tech stack

- PHP ^8.1
- Laravel 9 or 10 (tested via `orchestra/testbench ^7.29`)
- `mailzeet/mailzeet-php ^0.1.7` — underlying HTTP client (Guzzle-based)
- PHPUnit 9.6 — test framework
- PHPStan (larastan) level 4 — static analysis
- `axazara/php-cs` / php-cs-fixer — code style enforcement
- `insolita/unused-scanner` — dependency audit

## Getting started

```bash
composer install

# To use the package in a host app:
composer require mailzeet/mailzeet-laravel
php artisan mailzeet:install
```

After install, set credentials in `.env`:

```env
MAILZEET_API_KEY=your-api-key
MAILZEET_QUEUE=default
MAILZEET_ENV=live          # live | test
MAILZEET_DEV_MODE=false
MAILZEET_DEV_BASE_URL=https://api.mailzeet.com
```

## Common commands

| Task | Command |
|---|---|
| Test | `composer test` |
| Lint (dry-run) | `composer sniff` |
| Format | `composer format` |
| Static analysis | `composer analyse` |
| Unused dependency check | `composer unused` |

## Architecture

The package is intentionally small (KISS principle):

- `src/MailZeet.php` — main entry point; exposes `send()` (queued), `sendNow()` (synchronous), and `sendAfterResponse()` methods.
- `src/Config.php` — reads and validates all `config('mailzeet.*')` values; throws `InvalidPayloadException` on missing/invalid config.
- `src/Jobs/SendEmailJob.php` — queued Laravel job that dispatches email via the PHP client; silently no-ops when `MAILZEET_ENV=test`.
- `src/Facades/MailZeet.php` — `MailZeet` Facade registered as `mailzeet` binding.
- `src/Providers/MailZeetServiceProvider.php` — registers the binding, publishes config, and registers the `mailzeet:install` Artisan command.
- `src/Console/InstallCommand.php` — publishes `config/mailzeet.php` and appends env vars to `.env`.
- `config/mailzeet-laravel.php` — published config template.
- `tests/` — PHPUnit tests using Testbench; covers Config, MailZeet main class, and SendEmailJob.

## Conventions

- Code style is enforced by `php-cs-fixer` using the `axazara/php-cs` ruleset. Run `composer format` before every commit.
- Static analysis runs at PHPStan level 4 with Larastan; run `composer analyse` before pushing.
- Every change must include test coverage (`tests/` directory, PHPUnit with `--testdox`).
- When `MAILZEET_ENV=test`, all send methods silently log rather than dispatch — use this in test suites to avoid real API calls.
- Dev mode (`MAILZEET_DEV_MODE=true`) redirects API calls to `MAILZEET_DEV_BASE_URL` for local/staging API testing.
- The changelog, README, and version number must be updated with every change.

## Git Conventions

### 1. Branch names

Enforced regex (`branch_name_pattern`):
```
^(feature|fix|hotfix|chore|docs|refactor|test|ci|perf|build|style)/[a-z0-9._-]+$
```

- Lowercase only, kebab-case after the prefix, **max 50 characters** total.
- Use the full word `feature/` — **never** `feat/` (the short `feat` form is only for commit message types).
- Include the ticket id when relevant: `feature/AXA-123-add-stripe` (the ticket id is lowercased to satisfy the pattern — e.g. `feature/axa-123-add-stripe`).
- **Never** use a `claude/` prefix or any prefix outside the allowed set.
- `main`, `release`, `staging` are permanent protected branches — never push to them directly.
- If a branch is misnamed, rename it before pushing: `git branch -m <old> <new>`.

### 2. Commit messages
Enforced regex (`commit_message_pattern`), applied to **every** commit:
```
^(feat|fix|docs|style|refactor|perf|test|build|ci|chore|revert)(\([^)]+\))?!?: .+
```
- Lowercase type, optional scope in parens, optional `!` for breaking changes, subject after `: `.
- Subject starts with a lowercase letter and has no trailing period.
- Examples: `feat(checkout): add Apple Pay support`, `fix(api): handle expired tokens`, `chore(deps): bump axios from 1.7.2 to 1.15.2`, `refactor!: drop Node 18 support`.
- Do not rewrite Dependabot commits — `chore(deps): bump X from a to b` is already enforced via `.github/dependabot.yml`.

### 3. Files that are always rejected
Never stage or commit:
- `.env`, `.env.*` (only `.env.example` and `.env.sample` are allowed), `**/.env`, `**/.env.*`
- Private keys: `**/id_rsa{,.pub}`, `**/id_dsa`, `**/id_ecdsa`, `**/id_ed25519`, `**/.ssh/id_*`
- Credentials: `**/.aws/credentials`, `**/credentials.json`, `**/service-account.json`, `**/firebase-adminsdk-*.json`, `**/secrets.{yml,yaml}`
- Extensions: `*.pem`, `*.key`, `*.p12`, `*.pfx`, `*.jks`, `*.keystore`, `*.ppk`, `*.asc`, `*.gpg`
- Any file larger than 100 MB (use git LFS)
If a secret is needed, use `.env.example` for env vars and an external secret manager for credentials.

### Pull requests targeting `main`, `release`, `staging`
All three are protected — a PR is required (direct push blocked):
- 1 approval, all conversations resolved, **squash or rebase merge only** (linear history enforced — no merge commits).
- Commits must be GPG- or SSH-signed. Signing is required for `main` (`required-signatures-main` ruleset).
- The PR **title** becomes the squash commit message and must match the commit-message regex above (enforced on all three branches).

**Required workflows run on PRs whose base is `main` only** (not `release`/`staging`): `Branch naming convention`, `PR title — Conventional Commits`, and `PR size labeler`.
If a check shows `Waiting for workflow to run` for over a minute, the third-party action is likely missing from the enterprise allowlist.

When the branch-naming or PR-title check fails, the baseline bot auto-posts rename/title suggestions, following the enforced regex patterns.
If the bot's suggestions are incorrect, edit the PR title or branch name to match the required format.

### Pre-push checklist
Before running `git push`:
1. Branch name matches the regex.
2. Every commit in `origin/main..HEAD` matches the commit pattern (`git log --format=%s origin/main..HEAD`).
3. No staged file is in the blocked paths/extensions list.
4. Commits are signed if the target is `main`.

If any check fails, fix it locally rather than letting the server reject the push.
