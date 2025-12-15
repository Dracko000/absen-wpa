# Deploying Laravel Attendance App to Vercel

## Prerequisites

Before deploying your Laravel application to Vercel, you'll need:

1. A Vercel account
2. Vercel CLI installed (`npm i -g vercel`)
3. Database credentials (if using external database)

## Configuration Files

This project includes the following configuration files:
- `vercel.json` - Main Vercel configuration
- `api/index.php` - Entry point for Vercel routing

## Environment Variables Setup

Add the following environment variables in your Vercel dashboard under Settings > Environment Variables:

### Required Variables
Based on your current .env file, set these variables:
```
APP_KEY=base64:eBtcUJbLyvscatq/e3PbrZU6qWymq0o+ALAaq6GSFEI= (from your .env file)
APP_URL=https://your-domain.vercel.app
DB_CONNECTION=pgsql
DB_HOST=metro.proxy.rlwy.net
DB_PORT=15744
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=rXpJBaXzNSjfqNmbnoUJQfMRfmjVoeZg
SESSION_DRIVER=cookie         # Recommended for Vercel (change from 'database')
CACHE_DRIVER=array           # Recommended for Vercel (change from 'file')
```

### Optional Variables
```
APP_DEBUG=false
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
```

## Deployment Steps

### 1. Local Testing (Optional)
```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate application key
php artisan key:generate --force

# Clear config cache
php artisan config:clear
```

### 2. Deploy to Vercel
Using Vercel CLI:
```bash
cd /path/to/attendance-app
vercel --prod
```

Or link your GitHub repository to Vercel for automatic deployments.

### 3. Post-Deployment Tasks

After deployment, you may need to run certain Laravel commands:

```bash
# Run migrations
vercel run php artisan migrate --force

# Cache configuration (optional)
vercel run php artisan config:cache
vercel run php artisan route:cache
```

## Important Notes

1. **Database**: Vercel doesn't provide persistent storage, so you'll need an external database (like MySQL on AWS/RDS, DigitalOcean, or similar)

2. **Storage**: File uploads should be stored in cloud storage (AWS S3, etc.) rather than local storage

3. **Session Handling**: The configuration uses cookie-based sessions which work better in serverless environments

4. **Caching**: Cache driver is set to 'array' which works for individual requests but won't persist across requests

5. **Queue System**: Queue connection is set to 'sync' which processes jobs immediately rather than in background (serverless limitation)

## Limitations

- Serverless functions timeout after 10 seconds (configurable up to 900 seconds on paid plans)
- No persistent file storage (use databases/cloud storage instead)
- Sessions are cookie-based rather than file/database based
- Artisan commands must be run differently in serverless environment

## Alternative: Hybrid Approach

For better performance and features, consider building your frontend separately and using Laravel as an API backend:
1. Build static frontend (React/Vue/Nuxt/Next)
2. Deploy Laravel as API with API routes only
3. Connect frontend to Laravel API via HTTP requests

This approach is more scalable and takes advantage of Vercel's strengths with static hosting and serverless functions.