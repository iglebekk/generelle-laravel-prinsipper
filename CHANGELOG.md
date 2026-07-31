# Changelog

Alle merkbare endringer i denne pakken dokumenteres her.

Formatet følger [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) og prosjektet bruker [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.2] - 2026-07-31

### Lagt til
- Høyt prioriterte arbeidsregler for AI-assistenter: Think Before Coding, Simplicity First, Surgical Changes og Goal-Driven Execution.

## [3.0.1] - 2026-07-23

### Lagt til
- Prinsipp om at man alltid skal bruke `php artisan`-kommandoer når de finnes, f.eks. `make:*` for å opprette nye filer.

## [3.0.0] - 2026-07-03

### Endret
- Agent-filer (`AGENTS.md`, `CLAUDE.md`, `GEMINI.md`) får nå kun en kort referanse til `docs/laravel-prinsipper.md` i stedet for full dupliserte prinsipper.
- Config-nøkler: `targets`/`overwrite` erstattet med `docs_target`, `reference_targets` og `reference_body`.
- Bygget om ServiceProvider på `spatie/laravel-package-tools`.

### Lagt til
- Pest-testsuite via `orchestra/testbench`.
- Pint-config, `.editorconfig`, `.gitattributes`.
- GitHub Actions workflow for tester.
