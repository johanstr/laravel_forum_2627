# CLAUDE.md

Laravel Boost is al geïnstalleerd (`boost.json`, `.mcp.json`, skills in `.claude/skills`). De algemene Laravel/Boost-richtlijnen staan in AGENTS.md:

@AGENTS.md

## Over dit project

**AO Forum** — een discussieforum dat stap voor stap wordt gebouwd tijdens Laravel-workshops voor studenten Software Developer (Alfa-college). Zie `README.md` voor datamodel, routes en status.

- Code is lesmateriaal: houd oplossingen eenvoudig en leesbaar voor beginnende studenten. Geen extra architectuurlagen (services, repositories, actions) tenzij gevraagd; queries staan direct in de controller.
- Commentaar in code en UI-teksten zijn in het **Nederlands**.
- Uitgecommentarieerde code in controllers (bijv. `ThreadController@show`) is bewust lesmateriaal om alternatieven te tonen; niet opruimen zonder te vragen.

## Opbouw

- Hiërarchie: `Thread` → `Topic` → `Reply`; elk heeft `author()` (BelongsTo `User` via `user_id`).
- Models gebruiken het `#[Fillable([...])]`-attribuut (Laravel 13), geen `$fillable`-property.
- Routes gebruiken `{id}`-parameters met `findOrFail($id)`, geen route model binding.
- Admin-routes gebruiken de middleware `admin` (`EnsureUserIsAdmin`, alias in `bootstrap/app.php`), altijd samen met `auth`. Wie geen admin is, gaat naar `threads.index`. In views: `Auth::user()->isAdmin()` binnen `@auth`; in tests: `User::factory()->admin()`.
- Controllers: `ThreadController` (enkelvoud), `TopicsController` en `RepliesController` (meervoud; `TopicsController` is zo door de docent gekozen, `RepliesController` volgt dat); niet hernoemen zonder te vragen.
- Formulieren: inline `$request->validate()` met Nederlandse meldingen, `user_id` altijd uit `$request->user()`, daarna een redirect.
- Replies bevatten HTML uit CKEditor: toon ze met `{{ html_entity_decode(strip_tags(...)) }}`, nooit met `{!! !!}` zonder HTML-sanitizer.
- Eager loading met `with()` en tellingen met `withCount()` om N+1 te voorkomen.
- Forum-views: `resources/views/thread/*` en `resources/views/topic/*` met `@extends('layouts.main')` en statische assets uit `public/css`, `public/js`, `public/img` via `asset()`. Geen Tailwind in de forum-views. Extra scripts per pagina met `@push('scripts')`.
- Breeze-views (`auth/`, `profile/`, `dashboard`) gebruiken Tailwind + `<x-app-layout>` / `<x-guest-layout>`.
- Database: SQLite standaard (`.env.example`), lokaal kan `.env` MySQL gebruiken; tests draaien op SQLite in het geheugen. Na wijzigingen aan migraties/seeders: `php artisan migrate:fresh --seed`.

## Commando's

```bash
composer run dev                      # dev-server + Vite
php artisan migrate:fresh --seed      # database opnieuw opbouwen
php artisan test --compact            # tests (Pest)
vendor/bin/pint --dirty --format agent
```

## Bekende aandachtspunten

- Het seed-account `admin@admin.com` / `password` is de enige admin; na het inloggen gaat iedereen eerst naar `/dashboard`, waarna de middleware gewone gebruikers doorstuurt.
- Factories kiezen `user_id`/`thread_id`/`topic_id` met vaste `numberBetween`-bereiken; die moeten passen bij de aantallen in `DatabaseSeeder`.
- De footer-links in `layouts/main.blade.php` verwijzen nog naar statische `.html`-bestanden uit het oorspronkelijke HTML-template (`../design`, buiten deze repo).
