# Generelle Laravel-prinsipper (pakke)

Denne pakken publiserer dokumentasjonen om kodestandarder og beste praksis for Laravel.

## Installasjon

```bash
composer require iglebekk/generelle-laravel-prinsipper
```

## Publiser dokumentasjon

```bash
php artisan vendor:publish --tag=laravel-prinsipper-docs
```

Dette legger dokumentasjonen i `docs/laravel-prinsipper.md` i prosjektet ditt.

## Oppdatering

Når retningslinjene oppdateres i pakken, kjør:

```bash
composer update iglebekk/generelle-laravel-prinsipper
php artisan vendor:publish --tag=laravel-prinsipper-docs --force
```
