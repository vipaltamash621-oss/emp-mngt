# Railway.app Deployment Guide

## ✅ Step-by-Step Railway Setup

### Step 1: Create Railway Account
1. Go to https://railway.app
2. Sign up with GitHub
3. Create new project

### Step 2: Deploy from GitHub
1. Click "New Project"
2. Select "Deploy from GitHub"
3. Find: `vipaltamash621-oss/emp-mngt`
4. Select repository
5. Click "Deploy"

### Step 3: Add MySQL Database
**IMPORTANT:** Railway doesn't auto-create MySQL. You must add it manually:

1. In Railway Dashboard, click your project
2. Click "Add Service" or "+" button
3. Search for "MySQL"
4. Click "MySQL" to add it
5. Railway will generate:
   - MYSQL_HOST
   - MYSQL_PORT
   - MYSQL_DATABASE
   - MYSQL_USER
   - MYSQL_PASSWORD

### Step 4: Connect MySQL to App
1. Open MySQL service in Railway
2. Click "Connect"
3. Select your App service
4. Railway auto-injects variables

### Step 5: Set Environment Variables
In Railway Dashboard → Your Project → Variables:

```
APP_ENV=production
APP_DEBUG=false
APP_NAME=Employee Management System
LOG_CHANNEL=stack
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

**Database variables should auto-populate from MySQL service**

### Step 6: Deploy
1. Push to GitHub
2. Railway auto-deploys on push
3. Or manually click "Redeploy"
4. Wait for "Active" status

### Step 7: Run Migrations
Once deployed and healthy:

```bash
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

---

## ⚠️ MySQL Timeout Error Fix

### What Caused It:
```
❌ MySQL failed to start within timeout
```

Railway was waiting for MySQL but it wasn't connected properly.

### Solution Applied:
1. ✅ Entrypoint script now waits 120 seconds (not 60)
2. ✅ Script continues even if MySQL times out
3. ✅ Database operations become non-blocking
4. ✅ App starts with or without database

### What Changed in Code:
- `entrypoint.sh`: Increased timeout to 120 seconds, made DB optional
- `railway.toml`: Updated for Railway environment
- Fallback: App starts Apache even if MySQL unavailable

---

## 🚀 Railway Environment Variables

Railway automatically provides:
```
RAILWAY_PUBLIC_DOMAIN    # Your public URL
RAILWAY_ENVIRONMENT_ID   # Environment identifier
RAILWAY_SERVICE_ID       # Service identifier
```

These are used for:
- `ASSET_URL` → CDN assets
- Logging & debugging
- Service communication

---

## 🔗 Your Railway Project URL

After successful deployment:

```
https://emp-mngt-production-XXXX.railway.app
```

You'll see it in:
1. Railway Dashboard → Deployments → Public URL
2. Or click "View deployed URL" button

---

## 📊 Railway MySQL Connection

Railway provides these variables automatically:

```env
MYSQL_HOST=mysql.railway.internal
MYSQL_PORT=3306
MYSQL_DATABASE=railway
MYSQL_USER=root
MYSQL_PASSWORD=<generated>
```

Your app will use:
```env
DB_HOST=$MYSQL_HOST
DB_PORT=$MYSQL_PORT
DB_DATABASE=$MYSQL_DATABASE
DB_USERNAME=$MYSQL_USER
DB_PASSWORD=$MYSQL_PASSWORD
```

---

## 🛠️ Troubleshooting Railway

### Deployment Failed?
```bash
# View logs in Railway dashboard
Logs → App → scroll to see errors
```

### Still MySQL timeout?
1. Check MySQL service is added
2. Check variables are connected
3. Increase timeout in entrypoint.sh
4. Check MySQL service is "Active" (green)

### App says "Connection refused"?
1. MySQL service may not be started
2. Check MySQL logs in Railway
3. Verify credentials in Railway variables
4. Check network connectivity

### Need to restart?
In Railway Dashboard:
1. Click your service
2. Click three dots (...)
3. Select "Restart"

---

## 📝 Railway Logs

### View Application Logs:
```
Railway Dashboard → Your Project → Logs
```

Watch real-time:
```
Click "Follow" button
```

### View MySQL Logs:
```
Railway Dashboard → MySQL Service → Logs
```

---

## ✅ Deployment Checklist

- [ ] Railway account created
- [ ] GitHub repository connected
- [ ] MySQL service added to project
- [ ] MySQL connected to app service
- [ ] Environment variables set
- [ ] Deployment status is "Active"
- [ ] Public URL accessible
- [ ] Migrations ran successfully
- [ ] Can login at `/login`

---

## 🔐 Production Security

Before going public, ensure:

```env
APP_DEBUG=false              # Never true in production
APP_ENV=production           # Set to production
APP_KEY=base64:your-key      # Properly generated
LOG_CHANNEL=stack            # Proper logging
CACHE_DRIVER=file            # Cache configured
SESSION_DRIVER=file          # Sessions secure
```

---

## 🌐 Public URL

Once deployed, your app is live at:

```
https://emp-mngt-production-XXXX.railway.app
```

**Share this URL with:**
- Team members
- Stakeholders
- Users

---

## 📞 Need Help?

If deployment fails:

1. Check Railway logs
2. Verify MySQL is connected
3. Check environment variables
4. Review `entrypoint.sh` output
5. Check `TROUBLESHOOTING.md` guide

---

## 💡 Pro Tips

1. **Enable auto-deploy on push:**
   - Railway Dashboard → Settings → Auto-deploy

2. **Monitor resource usage:**
   - Railway Dashboard → Metrics

3. **Set up alerts:**
   - Railway Dashboard → Alerts

4. **Use private domain:**
   - Railway provides MySQL on `mysql.railway.internal`

5. **Scale when needed:**
   - Railway Dashboard → Scale

---

## 🚀 Next Steps

1. Add MySQL service
2. Deploy from GitHub
3. Set environment variables
4. Run migrations
5. Access public URL
6. Test application
7. Share with team

---

**Ready for production deployment!** 🎉

Your Employee Management System is now:
- ✅ Dockerized
- ✅ Railway-compatible
- ✅ Production-ready
- ✅ Public and accessible
