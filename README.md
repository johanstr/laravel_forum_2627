# AO Forum

Een discussieforum gebouwd met Laravel, ontwikkeld tijdens de Laravel-workshops voor de opleiding Software Developer (Alfa-college, schooljaar 2026-2027). Het project groeit per workshop stap voor stap; deze repository bevat de stand van zaken na de laatste workshop.

## Techniek

| Onderdeel       | Versie / keuze                                         |
|-----------------|--------------------------------------------------------|
| PHP             | 8.3+ (lokaal 8.5 via MAMP)                             |
| Framework       | Laravel 13                                             |
| Authenticatie   | Laravel Breeze (Blade-variant)                         |
| Database        | SQLite (standaard in `.env.example`); MySQL kan via `.env` |
| Testen          | Pest 5                                                 |
| Code style      | Laravel Pint                                           |
| Frontend forum  | Statische CSS/JS in `public/` (Materialize-achtige stijl) |
| Frontend Breeze | Tailwind CSS + Alpine.js via Vite                      |
| AI-tooling      | Laravel Boost (MCP-server + skills)                    |

## Installatie

```bash
composer run setup        # composer install, .env, key, migrate, npm install + build
php artisan migrate:fresh --seed
composer run dev          # start server, queue, logs en Vite
```

De app draait daarna op <http://localhost:8000>.

### Testaccount

De seeder maakt een vast account aan:

- E-mail: `admin@admin.com`
- Wachtwoord: `password`

Daarnaast worden 9 extra gebruikers, 20 threads, 50 topics en 200 replies met nepdata aangemaakt.

Het testaccount is admin (`is_admin = true`) en komt na het inloggen op het dashboard. De andere gebruikers zijn geen admin en komen op de landing page; hun wachtwoord is ook `password`.

## Datamodel

```
User 1───* Thread 1───* Topic 1───* Reply
  │                       │           │
  └──────────* (auteur via user_id) ──┘
```

| Model    | Velden                                     | Relaties                                        |
|----------|--------------------------------------------|-------------------------------------------------|
| `User`   | name, email, password, `is_admin`          | `threads()`, `topics()`, `replies()`, `isAdmin()` |
| `Thread` | title, content, user_id                    | `author()` (User), `topics()`                   |
| `Topic`  | title, content, user_id, thread_id         | `author()`, `thread()`, `replies()`             |
| `Reply`  | content, user_id, topic_id                 | `author()`, `topic()`                           |

- Een **thread** is een categorie (bijv. "PHP"); de auteur wordt in de view getoond als *moderator*.
- Een **topic** is een onderwerp/vraag binnen een thread.
- Een **reply** is een reactie op een topic.
- Mass assignment wordt geregeld met het `#[Fillable([...])]`-attribuut op de models.
- De foreign keys zijn `unsignedBigInteger`-kolommen zonder database-constraints.

## Routes

| Methode | URL              | Naam            | Actie                     |
|---------|------------------|-----------------|---------------------------|
| GET     | `/`              | `threads.index` | Overzicht van alle threads met aantal topics |
| GET     | `/threads/{id}`  | `threads.show`  | Topics van één thread met aantal replies     |
| GET     | `/topics/{id}`   | `topics.show`   | Eén topic met replies en replyformulier      |
| POST    | `/topics/{id}/replies` | `replies.store` | Reply plaatsen (`auth`)                |
| GET     | `/dashboard`     | `dashboard`     | Breeze-dashboard, alleen voor admins (`auth`, `verified`, `admin`) |
| GET/PATCH/DELETE | `/profile` | `profile.*` | Profielbeheer (Breeze)                    |

Authenticatieroutes (login, register, wachtwoord-reset, e-mailverificatie) staan in `routes/auth.php`.

De middleware `admin` (`EnsureUserIsAdmin`, alias in `bootstrap/app.php`) stuurt gebruikers die geen admin zijn door naar `threads.index`.

## Projectstructuur (relevante delen)

```
app/Http/Controllers/ThreadController.php   # threads (index, show)
app/Http/Controllers/TopicsController.php   # topicpagina (show)
app/Http/Controllers/RepliesController.php  # reply plaatsen (store)
app/Http/Middleware/EnsureUserIsAdmin.php   # alleen admins doorlaten
app/Models/{User,Thread,Topic,Reply}.php
database/migrations/2026_10_06_*            # forumtabellen + is_admin
database/factories/                         # factories voor alle models
database/seeders/DatabaseSeeder.php
resources/views/layouts/main.blade.php      # layout van het forum
resources/views/thread/{index,show}.blade.php
resources/views/topic/show.blade.php
resources/views/auth, profile, layouts/app  # Breeze (Tailwind)
public/css, public/js, public/img           # statische forum-assets
```

Het forum gebruikt de eigen layout `layouts.main` (`@extends` / `@section`); de Breeze-pagina's gebruiken de componentlayouts `<x-app-layout>` en `<x-guest-layout>`.

## Status

Klaar:

- [x] Models, migraties, factories en seeder voor threads, topics en replies
- [x] Threadoverzicht met aantal topics (eager loading + `withCount`)
- [x] Threadpagina met topics, auteur en aantal replies
- [x] Topicpagina met replies, bereikbaar vanaf de threadpagina
- [x] Reply plaatsen met CKEditor (alleen ingelogd; HTML wordt als platte tekst getoond)
- [x] Registreren, inloggen en profielbeheer via Breeze
- [x] Dashboard alleen voor admins (`admin`-middleware)
- [x] Admin-account in de seeder (`is_admin = true`)
- [x] Menu-links voor profiel, beheer en afmelden

Nog te doen (o.a.):

- [ ] Threads en topics aanmaken; threads, topics en replies wijzigen en verwijderen (de controllers hebben lege resource-methodes)
- [ ] Opmaak in replies veilig tonen (vraagt een pakket dat HTML schoonmaakt)
- [ ] Replies met alleen spaties weigeren (CKEditor maakt er `<p>&nbsp;</p>` van)
- [ ] Na aanmelden terug naar de topic
- [ ] Beheerfuncties op het dashboard (nu nog de standaard Breeze-pagina)
- [ ] Foreign key constraints in de migraties
- [ ] Footerpagina's (regels, contact, disclaimer, privacy, cookies)
- [ ] Feature test voor het threadoverzicht

## Testen

```bash
php artisan test --compact
vendor/bin/pint --dirty
```

Naast de standaard Breeze-tests (authenticatie en profiel) zijn er:

- `DashboardTest`: toegang tot het dashboard, menu-links en afmeldformulier
- `ThreadControllerTest`: links van een thread naar de topics
- `TopicsControllerTest`: topicpagina met replies
- `RepliesControllerTest`: reply plaatsen, validatie en veilig tonen

Tests draaien op SQLite in het geheugen.
