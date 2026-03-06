# Generelle Laravel-prinsipper (pakke)

Denne pakken publiserer dokumentasjonen om kodestandarder og beste praksis for Laravel.

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

Standard targets:
- `docs/laravel-prinsipper.md` (overskrives)
- `AGENTS.md`, `CLAUDE.md`, `GEMINI.md` (oppdateres mellom markører)

Markører:
```
<!-- LARAVEL-PRINSIPPER:START -->
<!-- LARAVEL-PRINSIPPER:END -->
```

## Konfigurasjon

```bash
php artisan vendor:publish --tag=laravel-prinsipper-config
```

I `config/laravel-prinsipper.php` kan du justere source, targets og markører.

## Oppdatering

Når retningslinjene oppdateres i pakken, kjør:

```bash
composer update iglebekk/generelle-laravel-prinsipper
php artisan laravel-prinsipper:sync
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
