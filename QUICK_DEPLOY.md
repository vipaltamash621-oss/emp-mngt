# Quick Deployment Guide

## ❌ What NOT to Do
- **Don't use Netlify** - It's for static sites only
- **Don't use GitHub Pages** - It's for static sites only
- **Don't use Vercel** - It's optimized for frontend apps

---

## ✅ Recommended: Railway.app (Fastest)

### 1. Go to [railway.app](https://railway.app) and sign up

### 2. Click "Create New Project"

### 3. Select "Deploy from GitHub repo"

### 4. Connect and select this repository

### 5. Railway will auto-detect Laravel

### 6. Add MySQL database:
   - Click "Add Service"
   - Select "MySQL"
   - Railway creates it automatically

### 7. Set Environment Variables:
   In the Variables tab, add:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:xxxxx (copy from local .env)
   APP_URL=https://your-railway-url.railway.app
   ```

### 8. Deploy Trigger:
   - Railway auto-deploys on git push
   - Or manually trigger deployment

### 9. Run Database Setup:
   ```bash
   # In Railway terminal
   php artisan migrate
   php artisan db:seed
   ```

### 10. Access your app!
   - Railway provides your URL automatically

---

## Alternative: Docker Deployment (Local Testing)

### Run locally with Docker:
```bash
docker-compose up -d
```

Then access:
- **App:** http://localhost:8000
- **PhpMyAdmin:** http://localhost:8080
  - User: `emp_user`
  - Password: `emp_password`

---

## Quick Start Commands

### Local Development:
```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite
npm run dev
```

### Before Deployment:
```bash
composer install
npm install
npm run build
php artisan migrate
php artisan db:seed
```

---

## Files You Need:

✅ **DEPLOYMENT.md** - Detailed deployment guide
✅ **Dockerfile** - Docker configuration
✅ **docker-compose.yml** - Local Docker setup
✅ **Procfile** - Heroku/Railway configuration
✅ **netlify.toml** - Explains why Netlify won't work

---

## Common Issues & Fixes:

### "Deploy directory 'dist' does not exist"
→ You're using Netlify. Use Railway instead.

### "503 Service Unavailable"
→ Database not connected. Check environment variables.

### "Permission Denied"
→ Run: `php artisan storage:link`

### "Memory exhausted"
→ Run: `php artisan config:cache`

---

## Support Resources:
- **DEPLOYMENT.md** - Full deployment guide
- **Railway Docs:** https://docs.railway.app
- **Laravel Docs:** https://laravel.com/docs/10.x/deployment

---

**Choose Railway.app for fastest deployment!** 🚀
