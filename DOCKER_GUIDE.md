# Docker Deployment Guide

## Quick Start

### 1. Clone/Download the Project
Ensure all project files are in the `attendance_sys` directory.

### 2. Deploy with Docker Compose
```bash
# Navigate to project directory
cd attendance_sys

# Build and start all services
docker-compose up -d

# Check if services are running
docker-compose ps
```

### 3. Access the Application
- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081 (optional database management)

### 4. Default Database Access
- **Host**: database (internal) / localhost:3306 (external)
- **Database**: attendance_system
- **Username**: attendance_user
- **Password**: attendance_pass
- **Root Password**: rootpassword

## Detailed Commands

### Building and Running
```bash
# Build images and start services
docker-compose up -d --build

# View logs
docker-compose logs -f

# View logs for specific service
docker-compose logs -f web
docker-compose logs -f database

# Stop services
docker-compose down

# Stop services and remove volumes (deletes database data)
docker-compose down -v
```

### Managing Services
```bash
# Restart specific service
docker-compose restart web

# Scale web service (multiple instances)
docker-compose up -d --scale web=3

# Execute commands in containers
docker-compose exec web bash
docker-compose exec database mysql -u root -p
```

## Service Details

### Web Service (PHP/Apache)
- **Port**: 8080
- **Technology**: PHP 8.1 + Apache
- **Features**: 
  - PHP extensions: PDO MySQL, GD, mbstring
  - Apache mod_rewrite enabled
  - Health checks configured
  - Auto-restart on failure

### Database Service (MySQL)
- **Port**: 3306
- **Technology**: MySQL 8.0
- **Features**:
  - Persistent data storage
  - Automatic schema initialization
  - Health checks configured
  - Sample data included

### phpMyAdmin Service
- **Port**: 8081
- **Purpose**: Database administration interface
- **Access**: Login with database credential

# Use with docker-compose
```bash
docker-compose up -d
```

### Custom Ports
Edit `docker-compose.yml` to change ports:
```yaml
services:
  web:
    ports:
      - "80:80"  # Change from 8080:80
  
  phpmyadmin:
    ports:
      - "8082:80"  # Change from 8081:80
```

## Monitoring and Maintenance

### Health Checks
Services include health checks that restart containers if they fail:
```bash
# Check service health
docker-compose ps

# View health check logs
docker inspect attendance_web | grep -A 10 -B 5 Health
```

### Log Management
```bash
# View all logs
docker-compose logs

# Follow logs in real-time
docker-compose logs -f --tail=100

# Export logs
docker-compose logs > deployment.log
```

## Clean Up

### Remove Everything
```bash
# Stop and remove containers, networks, and volumes
docker-compose down -v

# Remove built images
docker rmi attendance_sys_web

# Clean up Docker system
docker system prune -a
```