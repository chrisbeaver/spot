# Spot

A marketplace application for buying and selling precious metals (gold, silver, platinum). Built with Laravel 12, Vue 3, Inertia.js, and TypeScript.

## Features

- **Reddit OAuth Authentication** - Users sign up and log in using their Reddit account
- **Buy/Sell Offers** - Create and browse offers to buy or sell precious metals
- **Metal Types** - Support for gold, silver, and platinum
- **Weight Units** - Offers can specify weight in ounces or grams
- **Two-Factor Authentication** - Optional 2FA via Laravel Fortify

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Vue 3, TypeScript, Inertia.js
- **Styling**: Tailwind CSS 4
- **Authentication**: Laravel Fortify + Laravel Socialite (Reddit OAuth)
- **Database**: SQLite/MySQL/PostgreSQL

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- npm

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd spot
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Reddit OAuth**

   Create a Reddit app at https://www.reddit.com/prefs/apps:
   - Select **"web app"** as the type
   - Set redirect URI to `http://localhost:8000/auth/reddit/callback`

   Add to your `.env`:
   ```env
   REDDIT_CLIENT_ID=your_reddit_client_id
   REDDIT_CLIENT_SECRET=your_reddit_client_secret
   REDDIT_REDIRECT_URI=http://localhost:8000/auth/reddit/callback
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

## Development

Start the development server with all services (web server, queue, logs, Vite):

```bash
composer run dev
```

This runs:
- Laravel development server on `http://localhost:8000`
- Queue worker
- Laravel Pail for log tailing
- Vite dev server for hot module replacement

### Individual Commands

```bash
# Start Laravel server only
php artisan serve

# Run Vite dev server
npm run dev

# Run queue worker
php artisan queue:listen

# Watch logs
php artisan pail
```

## Testing

```bash
# Run PHP tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## Code Quality

```bash
# Lint PHP code
composer run lint

# Lint JavaScript/TypeScript
npm run lint

# Format code
npm run format
```

## Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Welcome page |
| GET | `/signup` | Reddit OAuth signup page |
| GET | `/auth/reddit` | Redirect to Reddit OAuth |
| GET | `/auth/reddit/callback` | Reddit OAuth callback |
| GET | `/login` | Login page |
| GET | `/dashboard` | User dashboard (auth required) |
| GET | `/offers/buy` | Browse buy offers |
| GET | `/offers/buy/create` | Create buy offer form |
| POST | `/offers/buy` | Store buy offer |
| GET | `/offers/sell` | Browse sell offers |
| GET | `/offers/sell/create` | Create sell offer form |
| POST | `/offers/sell` | Store sell offer |

## Database Schema

### Users
- `id` - Primary key
- `name` - User's display name
- `email` - Email address
- `password` - Hashed password (nullable for OAuth users)
- `reddit_id` - Reddit user ID (unique)
- `reddit_username` - Reddit username
- `reddit_token` - OAuth access token
- `reddit_refresh_token` - OAuth refresh token
- Two-factor authentication columns

### Offers
- `id` - Primary key
- `user_id` - Foreign key to users
- `type` - `buy` or `sell`
- `description` - Offer description
- `metal` - `gold`, `silver`, or `platinum`
- `weight` - Decimal weight value
- `weight_unit` - `oz` or `gram`
- `timestamps`

## License

MIT
