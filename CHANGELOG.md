# Changelog

Alle merkbare endringer i denne pakken dokumenteres her.

Formatet følger [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) og prosjektet bruker [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Endret
- Agent-filer (`AGENTS.md`, `CLAUDE.md`, `GEMINI.md`) får nå kun en kort referanse til `docs/laravel-prinsipper.md` i stedet for full dupliserte prinsipper.
- Config-nøkler: `targets`/`overwrite` erstattet med `docs_target`, `reference_targets` og `reference_body`.
- Bygget om ServiceProvider på `spatie/laravel-package-tools`.

### Lagt til
- Pest-testsuite via `orchestra/testbench`.
- Pint-config, `.editorconfig`, `.gitattributes`.
- GitHub Actions workflow for tester.
