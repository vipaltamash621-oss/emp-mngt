# 🔧 Complete Troubleshooting Guide

## Database Connection Errors

### Error: `SQLSTATE[HY000] [2002] Connection refused`

**Cause:** MySQL not running or not ready

**Solution:**
```bash
# Check if MySQL is running
docker ps | grep emp_management_db

# If not running, restart services
docker-compose restart db
docker-compose restart app

# Wait 30-40 seconds, then check logs
docker-compose logs app
```

**Alternative Fix - Full Reset:**
```bash
# Stop everything
docker-compose down -v

# Remove MySQL volume to reset database
rm -rf dbdata

# Start fresh
docker-compose up -d

# Wait 40 seconds for startup
sleep 40

# Check status
docker-compose ps
```

---

### Error: `Access denied for user 'emp_user'`

**Cause:** Wrong username or password

**Solution:**
```bash
# Verify credentials in .env match docker-compose.yml
cat .env | grep DB_

# Check docker-compose.yml database credentials
# They should match:
# .env: emp_user / emp_password
# docker-compose.yml: MYSQL_USER=emp_user, MYSQL_PASSWORD=emp_password

# If mismatch, update .env and restart
docker-compose down
docker-compose up -d
```

---

### Error: `Unknown database 'laravel_hrms'`

**Cause:** Database name mismatch

**Solution:**
```bash
# Check database name
docker-compose exec db mysql -u emp_user -p emp_management -e "SHOW DATABASES;"

# Correct database should be: emp_management (not laravel_hrms)

# If wrong, update .env:
# DB_DATABASE=emp_management
```

---

## Application Startup Errors

### Error: `Application key missing`

**Solution:**
```bash
# Generate key manually
docker-compose exec app php artisan key:generate

# Or full restart (should auto-generate)
docker-compose restart app
```

---

### Error: `bootstrap/cache directory must be present and writable`

**Solution:**
```bash
# Fix permissions
docker-compose exec app chmod -R 755 bootstrap/cache
docker-compose exec app chown -R www-data:www-data bootstrap/cache

# Or create fresh
docker-compose exec app mkdir -p bootstrap/cache
docker-compose exec app chmod 755 bootstrap/cache
```

---

### Error: Migrations Not Running

**Symptoms:** 
- No tables in database
- "table 'migrations' doesn't exist"

**Solution:**
```bash
# Check migration status
docker-compose exec app php artisan migrate:status

# Run migrations manually
docker-compose exec app php artisan migrate --force

# Check if tables created
docker-compose exec app php artisan migrate:status

# Or reset database and run again
docker-compose exec app php artisan migrate:refresh --force
```

---

## Docker Build Errors

### Error: `Package 'mysql-client' has no installation candidate`

**Status:** ✅ FIXED in current Dockerfile

**Solution:** (Already applied)
- Changed to `mariadb-client` which is the modern replacement

---

### Error: `Composer running as root`

**Status:** ✅ FIXED in current Dockerfile

**Solution:** (Already applied)
- Added `ENV COMPOSER_ALLOW_SUPERUSER=1`

---

### Error: `Build Failed: build daemon returned an error`

**Solution:**
```bash
# Build with no cache
docker-compose build --no-cache

# Then start
docker-compose up -d

# View build logs
docker-compose logs app
```

---

## Port Conflicts

### Error: `Port 8000 already in use`

**Solution 1:** Change port in docker-compose.yml
```yaml
ports:
  - "8001:80"  # Changed from 8000:80
```

Then restart:
```bash
docker-compose up -d
```

**Solution 2:** Kill process using port 8000
```bash
# Find process
netstat -ano | findstr :8000

# Kill process (replace PID with actual process ID)
taskkill /PID <PID> /F
```

---

### Error: `Port 3306 already in use`

**Solution:** 
```bash
# Option 1: Stop existing MySQL
net stop MySQL80

# Option 2: Use different port in docker-compose.yml
# Change: "3306:3306" to "3307:3306"

# Then update .env
DB_PORT=3307
```

---

### Error: `Port 8080 (PhpMyAdmin) already in use`

**Solution:**
```yaml
# In docker-compose.yml, change PhpMyAdmin port
ports:
  - "8081:80"  # Changed from 8080:80
```

---

## Container Issues

### Container Keeps Restarting

**Solution:**
```bash
# View logs
docker-compose logs app

# Check specific error
docker-compose logs app | tail -50

# Restart with more time
docker-compose down
docker-compose up -d
sleep 60

# Check status
docker-compose ps
```

---

### Can't Access Application at http://localhost:8000

**Troubleshooting:**
```bash
# Check if container is running
docker-compose ps

# Check container health
docker-compose exec app curl -i http://localhost/

# Check Apache is running
docker-compose exec app ps aux | grep apache

# View Apache error logs
docker-compose exec app tail -f /var/log/apache2/error.log

# View Apache access logs
docker-compose exec app tail -f /var/log/apache2/access.log
```

---

### Slow Container Startup

**Solution:**
```bash
# Increase start period in docker-compose.yml
healthcheck:
  start_period: 60s  # Increased from 40s

# Restart
docker-compose down
docker-compose up -d

# Wait full time before checking
sleep 60
```

---

## Laravel Application Errors

### Error: `View [component] not found`

**Solution:**
```bash
# Clear view cache
docker-compose exec app php artisan view:clear

# Recompile views
docker-compose exec app php artisan optimize

# Restart
docker-compose restart app
```

---

### Error: `TokenMismatchException` or CSRF errors

**Solution:**
```bash
# Generate new APP_KEY
docker-compose exec app php artisan key:generate --force

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache

# Restart
docker-compose restart app

# Clear browser cache (Ctrl+Shift+Delete) and try again
```

---

### Error: `Class not found` or `Use of undefined constant`

**Solution:**
```bash
# Rebuild autoloader
docker-compose exec app composer dump-autoload

# Clear all caches
docker-compose exec app php artisan optimize:clear

# Restart
docker-compose restart app
```

---

### Error: Permission Denied on Storage

**Solution:**
```bash
# Fix storage permissions
docker-compose exec app chmod -R 755 storage
docker-compose exec app chmod -R 755 bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage
docker-compose exec app chown -R www-data:www-data bootstrap/cache
```

---

## Database Issues

### Can't Access PhpMyAdmin

**Solution:**
```bash
# Check PhpMyAdmin container
docker ps | grep phpmyadmin

# Check if MySQL is healthy
docker-compose ps db

# Try accessing at http://localhost:8080

# If still not working:
docker-compose restart phpmyadmin
```

---

### No Data in Database After Seeding

**Solution:**
```bash
# Check if seeding ran
docker-compose exec app php artisan db:seed

# Or refresh database with seeding
docker-compose exec app php artisan migrate:refresh --force --seed

# Verify data exists
docker-compose exec db mysql -u emp_user -p emp_management -e "SELECT COUNT(*) FROM users;"
```

---

### Database File Getting Corrupted

**Solution:**
```bash
# Reset database completely
docker-compose down -v

# Remove volume
docker volume rm emp_network_dbdata

# Or manually
rm -rf dbdata

# Start fresh
docker-compose up -d

# Wait 40 seconds
sleep 40

# Verify
docker-compose ps
```

---

## Performance Issues

### Application Running Slow

**Solution:**
```bash
# Check resource usage
docker stats

# Increase Docker resources (Docker Desktop settings)
# Settings → Resources → increase CPU/Memory

# Clear caches
docker-compose exec app php artisan optimize:clear

# Rebuild autoloader
docker-compose exec app composer dump-autoload --optimize
```

---

### Build Taking Too Long

**Solution:**
```bash
# Build in background and monitor
docker-compose build --progress=plain

# Or use BuildKit
DOCKER_BUILDKIT=1 docker-compose build

# Check Docker disk usage
docker system df

# Clean up unused resources
docker system prune -a --volumes
```

---

## Network Issues

### Container Can't Reach External URLs

**Solution:**
```bash
# Check network
docker network ls

# Inspect network
docker network inspect emp_network

# Check if container has internet
docker-compose exec app ping 8.8.8.8

# Check DNS
docker-compose exec app cat /etc/resolv.conf
```

---

### Containers Can't Communicate

**Solution:**
```bash
# Check network connectivity
docker-compose exec app ping db

# If fails, restart network
docker-compose down
docker network prune
docker-compose up -d
```

---

## Monitoring and Debugging

### View All Logs

```bash
# Real-time logs
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f phpmyadmin

# Last 100 lines
docker-compose logs --tail=100 app

# Timestamps
docker-compose logs -f --timestamps
```

---

### Check Application Health

```bash
# Health status
docker-compose ps

# Detailed status
docker ps --no-trunc

# Service logs
docker-compose logs app

# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log
```

---

### Execute Commands in Container

```bash
# Interactive bash
docker-compose exec app bash

# Run artisan command
docker-compose exec app php artisan tinker

# Run migrations
docker-compose exec app php artisan migrate --force

# Check PHP version
docker-compose exec app php -v
```

---

## Quick Fix Commands

```bash
# Everything not working? Full reset:
docker-compose down -v
docker volume prune -f
docker system prune -f
docker-compose up -d
sleep 40

# Just restart app:
docker-compose restart app

# Just restart database:
docker-compose restart db

# Clear all caches:
docker-compose exec app php artisan optimize:clear

# Rebuild everything:
docker-compose up -d --build

# Check everything:
docker-compose ps
docker-compose logs
```

---

## Support Resources

📚 **Documentation Files:**
- `DATABASE_CONNECTION_FIX.md` - Database connection issues
- `DOCKERFILE_FIX.md` - Docker build issues
- `DOCKER_SETUP.md` - Docker setup guide
- `DEPLOYMENT.md` - Deployment options
- `QUICK_DEPLOY.md` - Quick start

🔗 **External Resources:**
- Docker: https://docs.docker.com
- Laravel: https://laravel.com/docs
- MySQL: https://dev.mysql.com/doc

---

## Still Having Issues?

1. **Check the logs first:**
   ```bash
   docker-compose logs -f
   ```

2. **Try full reset:**
   ```bash
   docker-compose down -v
   docker-compose up -d
   sleep 40
   ```

3. **Review relevant documentation:**
   - DATABASE_CONNECTION_FIX.md
   - DOCKERFILE_FIX.md
   - DOCKER_SETUP.md

4. **Check GitHub issues:**
   - https://github.com/vipaltamash621-oss/emp-mngt/issues

---

**All common issues covered! ✅**

If you encounter an issue not listed here, check the logs and documentation files for guidance.

🚀 Ready to deploy!
