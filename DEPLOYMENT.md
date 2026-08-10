# Deployment Guide - Employee Management System

This is a **full-stack Laravel application** with MySQL database. It cannot be deployed on static site hosts like Netlify.

---

## 🚀 Recommended Deployment Platforms

### 1. **Railway.app** (Recommended - Easiest)

#### Steps:
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Create new project → GitHub Repository
4. Select this repository
5. Railway automatically detects Laravel
6. Add MySQL plugin to the project
7. Configure environment variables
8. Deploy!

**Environment Variables to Set:**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=your-domain.railway.app
DB_CONNECTION=mysql
DB_HOST=mysql-service-name
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-secure-password
```

---

### 2. **Render.com**

#### Steps:
1. Go to [render.com](https://render.com)
2. Sign up with GitHub
3. Create New → Web Service
4. Connect your GitHub repository
5. Configure:
   - **Build Command:** `composer install && npm install && npm run build && php artisan migrate`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`
   - **Environment:** Select "PHP"
6. Add MySQL database
7. Set environment variables
8. Deploy!

**Environment Variables:**
Same as Railway.app above

---

### 3. **Heroku** (Classic but costly now)

#### Prerequisites:
- Install Heroku CLI
- Have a Heroku account

#### Steps:
```bash
# Login to Heroku
heroku login

# Create app
heroku create your-app-name

# Add MySQL database
heroku addons:create cleardb:ignite

# Set environment variables
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_KEY=your-app-key
heroku config:set APP_URL=https://your-app-name.herokuapp.com

# Deploy
git push heroku main
```

---

### 4. **Traditional PHP Hosting** (cPanel, Plesk)

#### Requirements:
- PHP 8.1+ 
- MySQL 5.7+
- Composer installed on server

#### Steps:
1. Upload via FTP/SFTP:
   - Upload all files to `public_html` folder
   - Move contents of `public` folder to root
   - Keep config files outside web root

2. Setup Database:
   - Create MySQL database
   - Run migrations: `php artisan migrate`

3. Set permissions:
   ```bash
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   ```

4. Configure `.env`:
   ```
   APP_ENV=production
   APP_DEBUG=false
   DB_HOST=localhost
   DB_DATABASE=your_db
   DB_USERNAME=your_user
   DB_PASSWORD=your_pass
   ```

---

## 📋 Pre-Deployment Checklist

- [ ] Update `.env` with production settings
- [ ] Run `php artisan key:generate` (if not done)
- [ ] Set `APP_DEBUG=false`
- [ ] Ensure database credentials are set
- [ ] Run migrations on production
- [ ] Build frontend assets: `npm run build`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Optimize for production: `php artisan optimize`

---

## 🔐 Security Considerations

1. **Environment Variables:**
   - Never commit `.env` file
   - Use platform's secret management
   - Use strong database passwords

2. **SSL Certificate:**
   - Railway/Render provide free HTTPS
   - Enable HTTPS enforcement in Laravel

3. **Application Key:**
   - Generate unique key for production
   - Store securely in environment

4. **Database:**
   - Use strong passwords
   - Regular backups
   - Restrict database access

---

## 📊 Database Setup on Deployment

### Railway/Render:
Automatically creates MySQL database. Just run:
```bash
php artisan migrate
php artisan db:seed
```

### Traditional Hosting:
1. Create database via control panel
2. Create database user
3. Grant all privileges to user
4. Update `.env` with credentials
5. Run migrations

---

## 🔧 Environment Variables Template

```env
# App
APP_NAME="Employee Management System"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=db-service
DB_PORT=3306
DB_DATABASE=emp_management
DB_USERNAME=db_user
DB_PASSWORD=secure_password

# Mail (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Other
LOG_CHANNEL=stack
CACHE_DRIVER=file
SESSION_DRIVER=file
```

---

## 🚨 Troubleshooting

### "Deploy directory does not exist"
- ❌ Don't use Netlify (static site only)
- ✅ Use Railway, Render, or traditional PHP hosting

### "502 Bad Gateway"
- Check application logs
- Verify database connection
- Run `php artisan config:clear`

### "Database connection refused"
- Verify DB credentials in `.env`
- Check database server is running
- Verify network/firewall rules

### "Permission denied on storage"
- Set correct file permissions: `chmod 775 storage/`
- Ensure web server user owns directories

---

## 📞 Support & Resources

- [Laravel Deployment Guide](https://laravel.com/docs/10.x/deployment)
- [Railway Docs](https://docs.railway.app)
- [Render Docs](https://render.com/docs)
- [Heroku PHP Support](https://devcenter.heroku.com/articles/getting-started-with-php)

---

## Next Steps

1. Choose your deployment platform
2. Follow the steps for that platform
3. Set environment variables
4. Deploy!
5. Run migrations if not auto-run
6. Access your application

**Good luck with your deployment!** 🎉
