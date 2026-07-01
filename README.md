# Generelle Laravel-prinsipper (pakke)

Denne pakken publiserer dokumentasjonen om kodestandarder og beste praksis for Laravel.

Den publiserte dokumentasjonen anbefaler Flux UI som standard for egenprodusert Blade-UI, alltid gjennom app-spesifikke komponenter.

Støttede Laravel-versjoner: `9.x`, `10.x`, `11.x`, `12.x`, `13.x`.

## Installasjon

```bash
composer require iglebekk/generelle-laravel-prinsipper --dev
```

## Publiser dokumentasjon

```bash
php artisan vendor:publish --tag=laravel-prinsipper-docs
```

Dette legger dokumentasjonen i `docs/laravel-prinsipper.md` i prosjektet ditt.

## Synkronisering til instruksjonsfiler

For å sikre at siste versjon alltid er tilgjengelig i prosjektet og AI-instruksjonsfiler, kjør:

```bash
php artisan laravel-prinsipper:sync
```

Standard atferd:
- `docs/laravel-prinsipper.md` overskrives med full prinsipptekst (kilde).
- `AGENTS.md`, `CLAUDE.md`, `GEMINI.md` får kun en kort referanse mellom markørene som peker til docs-fila — for å unngå duplisert innhold.

Markører:
```
<!-- LARAVEL-PRINSIPPER:START -->
<!-- LARAVEL-PRINSIPPER:END -->
```

## Konfigurasjon

```bash
php artisan vendor:publish --tag=laravel-prinsipper-config
```

I `config/laravel-prinsipper.php` kan du justere `source`, `docs_target`, `reference_targets`, `reference_body` og markører.

## Oppdatering

Når retningslinjene oppdateres i pakken, kjør:

```bash
composer update iglebekk/generelle-laravel-prinsipper
php artisan laravel-prinsipper:sync
```

## Utvikling

```bash
composer test     # Pest via orchestra/testbench
composer format   # Laravel Pint
```

## Automatisering via composer

Legg til i prosjektets `composer.json`:

```json
{
  "scripts": {
    "post-install-cmd": [
      "@php artisan laravel-prinsipper:sync"
    ],
    "post-update-cmd": [
      "@php artisan laravel-prinsipper:sync"
    ]
  }
}
```
