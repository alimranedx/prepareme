# Production Deployment Guide — PrepareMe.com

This guide provides step-by-step instructions for deploying PrepareMe.com to a production Linux server (Ubuntu 22.04 LTS / 24.04 LTS) using Nginx, PHP 8.3-FPM, MySQL 8.0, Redis, Supervisor, and Let's Encrypt SSL.

---

## 1. System Requirements & Package Installation

```bash
# Update repositories
sudo apt update && sudo apt upgrade -y

# Install Nginx, MySQL, Redis, Git, Curl, and Supervisor
sudo apt install -y nginx mysql-server redis-server git curl unzip supervisor

# Install Tesseract OCR and Language Packs for Bengali and English
sudo apt install -y tesseract-ocr tesseract-ocr-ben tesseract-ocr-eng

# Install PHP 8.3 and Required Extensions
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
                    php8.3-curl php8.3-gd php8.3-zip php8.3-redis php8.3-bcmath

# Verify Tesseract Bengali Pack
tesseract --list-langs
# Expected output: ben, eng, osd
```

---

## 2. Database Configuration

```sql
sudo mysql -u root

CREATE DATABASE prepareme_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'prepareme_user'@'localhost' IDENTIFIED BY 'StrongProductionPassword!2026';
GRANT ALL PRIVILEGES ON prepareme_db.* TO 'prepareme_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 3. Deploying Backend API (`prepareme-api`)

1. **Deploy Repository:**
   ```bash
   sudo mkdir -p /var/www/prepareme.com
   sudo chown -R $USER:$USER /var/www/prepareme.com
   cd /var/www/prepareme.com
   git clone <repository_url> .
   cd prepareme-api
   ```

2. **Composer Installation:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Configure Environment (`.env`):**
   ```bash
   cp .env.example .env
   nano .env
   ```
   Set production variables:
   ```env
   APP_NAME=PrepareMe
   APP_ENV=production
   APP_KEY=base64:... (generate with php artisan key:generate)
   APP_DEBUG=false
   APP_URL=https://api.prepareme.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prepareme_db
   DB_USERNAME=prepareme_user
   DB_PASSWORD=StrongProductionPassword!2026

   QUEUE_CONNECTION=redis
   CACHE_STORE=redis
   SESSION_DRIVER=redis

   OCR_DRIVER=tesseract
   OCR_TESSERACT_BINARY=/usr/bin/tesseract
   ```

4. **Permissions, Migrations & Caching:**
   ```bash
   php artisan key:generate
   php artisan migrate --force --seed
   php artisan config:cache
   php artisan route:cache
   php artisan event:cache

   sudo chown -R www-data:www-data /var/www/prepareme.com/prepareme-api/storage
   sudo chown -R www-data:www-data /var/www/prepareme.com/prepareme-api/bootstrap/cache
   sudo chmod -R 775 /var/www/prepareme.com/prepareme-api/storage
   ```

---

## 4. Deploying Frontend Single Page Application (`prepareme-web`)

1. **Build Client Bundle:**
   ```bash
   cd /var/www/prepareme.com/prepareme-web
   npm install
   npm run build
   ```
   *The optimized static assets will reside in `/var/www/prepareme.com/prepareme-web/dist/`.*

2. **Ensure Read Permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/prepareme.com/prepareme-web/dist
   sudo chmod -R 755 /var/www/prepareme.com/prepareme-web/dist
   ```

---

## 5. Queue Worker Configuration (Supervisor)

To reliably process OCR jobs asynchronously in the background, configure Supervisor:

1. Create `/etc/supervisor/conf.d/prepareme-worker.conf`:
   ```ini
   [program:prepareme-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/prepareme.com/prepareme-api/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --timeout=180
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/var/log/supervisor/prepareme-worker.log
   stopwaitsecs=3600
   ```

2. Start worker:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start prepareme-worker:*
   ```

---

## 6. Nginx Web Server Configuration

### 6.1 Frontend SPA Server Block (`/etc/nginx/sites-available/prepareme-web`)
```nginx
server {
    listen 80;
    server_name prepareme.com www.prepareme.com;
    root /var/www/prepareme.com/prepareme-web/dist;
    index index.html;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2|woff)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```

### 6.2 Backend API Server Block (`/etc/nginx/sites-available/prepareme-api`)
```nginx
server {
    listen 80;
    server_name api.prepareme.com;
    root /var/www/prepareme.com/prepareme-api/public;
    index index.php;

    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable configurations and test:
```bash
sudo ln -s /etc/nginx/sites-available/prepareme-web /etc/nginx/sites-enabled/
sudo ln -s /etc/nginx/sites-available/prepareme-api /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 7. SSL Provisioning (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d prepareme.com -d www.prepareme.com -d api.prepareme.com
```

Certbot will automatically install the SSL certificates and configure HTTPS redirection.

---

## 8. Automated Maintenance & Backups

1. **Daily Database Backup (`/etc/cron.daily/prepareme-db-backup`):**
   ```bash
   #!/bin/bash
   BACKUP_DIR="/var/backups/prepareme"
   mkdir -p $BACKUP_DIR
   mysqldump -u prepareme_user -p'StrongProductionPassword!2026' prepareme_db | gzip > "$BACKUP_DIR/db_$(date +\%F).sql.gz"
   find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +14 -delete
   ```
   ```bash
   sudo chmod +x /etc/cron.daily/prepareme-db-backup
   ```
