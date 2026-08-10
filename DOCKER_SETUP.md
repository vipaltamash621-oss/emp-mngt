# Docker Setup Guide

This application includes Docker configuration for easy local development and deployment.

## Prerequisites

- **Docker Desktop** installed ([download](https://www.docker.com/products/docker-desktop))
- **Docker Compose** (included with Docker Desktop)

---

## Option 1: Local Development with Docker Compose

### Start the Application

```bash
docker-compose up -d
```

This creates three services:
- **Application** - PHP 8.2 with Apache (port 8000)
- **Database** - MySQL 8.0 (port 3306)
- **PhpMyAdmin** - Database management (port 8080)

### Access Services

| Service | URL | Credentials |
|---------|-----|-------------|
| Application | http://localhost:8000 | See below |
| PhpMyAdmin | http://localhost:8080 | User: `emp_user` / Pass: `emp_password` |
| Database | localhost:3306 | Host: `db`, User: `emp_user`, Pass: `emp_password` |

### Login to Application

**Admin User:**
- Email: `admin@email.com`
- Password: `secret`

**Employee User:**
- Email: `employee@gmail.com`
- Password: `employee`

### Run Migrations

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

### View Logs

```bash
docker-compose logs -f app
```

### Stop Services

```bash
docker-compose down
```

### Clean Everything

```bash
docker-compose down -v  # -v removes volumes (database data)
```

---

## Option 2: Build and Run Individual Docker Image

### Build Image

```bash
docker build -t emp-management:latest .
```

### Run Container

```bash
docker run -d \
  --name emp_app \
  -p 8000:80 \
  -e DB_HOST=db_host \
  -e DB_DATABASE=emp_management \
  -e DB_USERNAME=user \
  -e DB_PASSWORD=password \
  emp-management:latest
```

### View Logs

```bash
docker logs emp_app
```

### Stop Container

```bash
docker stop emp_app
docker rm emp_app
```

---

## Option 3: Deploy to Railway with Docker

### 1. Install Railway CLI

```bash
npm i -g @railway/cli
```

### 2. Link to Railway Project

```bash
railway link
```

### 3. Set Environment Variables

```bash
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set APP_KEY=base64:your-key
```

### 4. Deploy

```bash
railway up
```

### 5. Run Migrations

```bash
railway run php artisan migrate
railway run php artisan db:seed
```

---

## Dockerfile Explained

The `Dockerfile` contains:

1. **Base Image** - PHP 8.2 with Apache
2. **System Dependencies** - Git, curl, libraries for image processing
3. **PHP Extensions** - MySQL, mbstring, image handling, etc.
4. **Composer** - PHP dependency installer
5. **Node.js** - For frontend asset building
6. **Configuration** - Apache setup, permissions
7. **Entrypoint** - Runs migrations automatically
8. **Health Check** - Monitors application status

### Build Stages
- Single-stage build optimized for size and speed
- Combines all dependencies in one layer
- Removes package manager cache to reduce image size

---

## Docker Compose Services

### Web Service (PHP Application)
```yaml
- Image: Dockerfile (custom)
- Port: 8000:80
- Volumes: Current directory mapped to /var/www/html
- Environment: All Laravel variables
```

### Database Service (MySQL)
```yaml
- Image: mysql:8.0
- Port: 3306:3306
- Volumes: dbdata (persists between restarts)
- Database: emp_management
```

### PhpMyAdmin Service
```yaml
- Image: phpmyadmin:latest
- Port: 8080:80
- Connects to MySQL service
```

---

## Environment Variables

### In docker-compose.yml (pre-configured):

```env
APP_NAME=Employee Management System
APP_ENV=local
APP_DEBUG=true
DB_HOST=db
DB_PORT=3306
DB_DATABASE=emp_management
DB_USERNAME=emp_user
DB_PASSWORD=emp_password
```

### For Production (Railway):

Override with:
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key
LOG_CHANNEL=stack
CACHE_DRIVER=file
```

---

## Troubleshooting

### Container won't start
```bash
docker-compose logs app
```

### Port already in use
Change ports in `docker-compose.yml`:
```yaml
ports:
  - "8001:80"  # Changed from 8000:80
```

### Database connection refused
Wait for MySQL to start (usually 10-15 seconds):
```bash
docker-compose logs db
```

### Permission denied errors
Fix permissions:
```bash
docker-compose exec app chmod -R 755 storage/
docker-compose exec app chown -R www-data:www-data /var/www/html
```

### Reset everything
```bash
docker-compose down -v
docker-compose up -d
docker-compose exec app php artisan migrate --force
```

---

## Performance Tips

1. **Use Named Volumes** - Better performance than bind mounts
2. **Build Multi-stage** - Reduces final image size
3. **Exclude Unnecessary Files** - Use `.dockerignore`
4. **Cache Layers** - Order Dockerfile commands wisely
5. **Use Docker Desktop** - Better performance than Docker Toolbox

---

## Production Deployment

For production with Docker:

1. Use Railway.app (recommended)
2. Or Docker Hub → push image → deploy to server
3. Or build image on CI/CD server

Example Railway deployment:
```bash
railway add github-repo
railway variables set APP_ENV=production
railway up
```

---

## Useful Commands

```bash
# View all containers
docker ps -a

# View images
docker images

# Remove image
docker rmi image_name

# View Docker disk usage
docker system df

# Clean up unused resources
docker system prune

# Build with no cache
docker build --no-cache -t emp-management:latest .

# Run container interactively
docker run -it emp-management:latest /bin/bash
```

---

## Files Reference

- **Dockerfile** - Container image definition
- **docker-compose.yml** - Multi-container configuration
- **.dockerignore** - Files to exclude from image
- **railway.toml** - Railway.app configuration

---

## Additional Resources

- [Docker Documentation](https://docs.docker.com)
- [Docker Compose Documentation](https://docs.docker.com/compose)
- [Railway.app Documentation](https://docs.railway.app)
- [PHP Docker Image](https://hub.docker.com/_/php)

---

**Ready to containerize!** 🐳
