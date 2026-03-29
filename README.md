# 🔧 ServiceAuto Platform - MVP

## Lucrare de licență - Automatică și Informatică Aplicată

**Platformă digitală pentru gestionarea service-urilor auto**

---

## 🚀 Stack Tehnologic

- **Backend:** Laravel 13 + Filament v5
- **Frontend:** Blade + Tailwind CSS + Alpine.js
- **Grafice:** Chart.js (line + doughnut)
- **Hartă:** Leaflet.js (OpenStreetMap)
- **PDF:** DomPDF (facturi)
- **Auth:** Laravel Breeze

---

## 📦 Instalare

```bash
# Clonează proiectul
git clone <repo-url>
cd licenta

# Instalează dependențele PHP
composer install

# Instalează dependențele JS
npm install

# Copiază .env
cp .env.example .env
php artisan key:generate

# Rulează migrațiile + seed
php artisan migrate:fresh --seed

# Compilează assets
npm run build

# Pornește serverul
php artisan serve
```

Accesează: **http://localhost:8000**

---

## 🔑 Conturi Demo

| Email              | Parolă   | Rol     | Acces                                  |
| ------------------ | -------- | ------- | -------------------------------------- |
| `admin@test.com`   | `123456` | Admin   | `/admin` - Panou complet               |
| `vlad@test.com`    | `123456` | Client  | `/client/dashboard` - Dashboard client |
| `maria@test.com`   | `123456` | Client  | `/client/dashboard` - Dashboard client |
| `autopro@test.com` | `123456` | Service | `/admin` - Doar intervențiile proprii  |
| `speedy@test.com`  | `123456` | Service | `/admin` - Doar intervențiile proprii  |

---

## 📄 Pagini

### Publice (fără login)

- `/` - Landing page (hero, stats, service-uri top, hartă, testimoniale)
- `/services` - Lista tuturor service-urilor
- `/services/{id}` - Detalii service + Google Maps embed

### Client (după login)

- `/client/dashboard` - Dashboard cu mașini, ultimele reparații
- `/client/cars` - Lista mașinilor cu CRUD
- `/client/cars/{id}` - Detalii mașină cu grafice Chart.js

### Admin Panel (Filament - `/admin`)

- **Service-uri** - CRUD complet
- **Mașini** - Vizualizare (read-only pt service)
- **Intervenții** - CRUD + acțiuni (Start/Complete/Factură)
- **Facturi** - CRUD + download PDF

---

## 🗂️ Structura Proiectului

```
app/
├── Models/              # User, Service, Car, Intervention, Invoice
├── Http/Controllers/    # Landing, Client, Service, Auth
├── Filament/Resources/  # ServiceResource, CarResource, InterventionResource, InvoiceResource
├── Providers/Filament/  # AdminPanelProvider

resources/views/
├── landing.blade.php    # Landing page cu Leaflet
├── client/              # Dashboard, Cars (index, show, create, edit)
├── services/            # Index, Show
├── invoices/            # PDF template

database/
├── migrations/          # Users, Services, Cars, Interventions, Invoices
├── seeders/             # Date reale demo
```

---

## 📊 Funcționalități Cheie

- **Autentificare cu roluri** (client/service/admin)
- **Dashboard client** cu carduri mașini și tabel reparații
- **Grafice Chart.js** (evoluție km + tipuri reparații)
- **Hartă Leaflet** cu markere service-uri
- **Panou admin Filament** cu resurse complete
- **Filtrare intervenții** pe rol (service vede doar ale lui)
- **Generare facturi PDF** cu DomPDF
- **Acțiuni workflow** (Start → Complete → Factură)

---

## 📝 Licență

Proiect de licență - uz educațional.

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
