#!/bin/bash

# Attendance System Docker Deployment Script
# This script helps deploy the attendance system quickly

set -e

echo "🎓 Attendance Management System - Docker Deployment"
echo "=================================================="

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Function to check if port is available
check_port() {
    local port=$1
    if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null 2>&1; then
        return 1
    else
        return 0
    fi
}

# Check required ports
echo "🔍 Checking port availability..."
ports=(8080 8081 3306)
for port in "${ports[@]}"; do
    if ! check_port $port; then
        echo "⚠️  Port $port is already in use. Please stop the service using this port or modify docker-compose.yml"
        exit 1
    else
        echo "✅ Port $port is available"
    fi
done

# Create backup of existing data if it exists
if [ -d "db_data" ]; then
    echo "📦 Creating backup of existing database..."
    timestamp=$(date +%Y%m%d_%H%M%S)
    cp -r db_data "db_data_backup_$timestamp"
    echo "✅ Backup created: db_data_backup_$timestamp"
fi

# Build and start services
echo "🏗️  Building and starting services..."
docker-compose down 2>/dev/null || true
docker-compose up -d --build

# Wait for services to be healthy
echo "⏳ Waiting for services to be ready..."
max_attempts=60
attempt=0

while [ $attempt -lt $max_attempts ]; do
    if docker-compose ps | grep -q "healthy"; then
        echo "✅ Services are healthy!"
        break
    fi
    
    if [ $((attempt % 10)) -eq 0 ]; then
        echo "   Still waiting... (attempt $attempt/$max_attempts)"
    fi
    
    sleep 5
    attempt=$((attempt + 1))
done

if [ $attempt -eq $max_attempts ]; then
    echo "❌ Services failed to start properly. Check logs with: docker-compose logs"
    exit 1
fi

# Display service status
echo ""
echo "📊 Service Status:"
docker-compose ps

# Display access information
echo ""
echo "🌐 Access Information:"
echo "================================"
echo "Main Application:    http://localhost:8080"
echo "phpMyAdmin:          http://localhost:8081"
echo "Database Host:       localhost:3306"
echo ""
echo "Database Credentials:"
echo "- Database:          attendance_system"
echo "- Username:          attendance_user"
echo "- Password:          attendance_pass"
echo "- Root Password:     rootpassword"
echo ""

# Test application accessibility
echo "🧪 Testing application..."
sleep 5
if curl -s -f http://localhost:8080 > /dev/null; then
    echo "✅ Application is accessible!"
else
    echo "⚠️  Application may not be ready yet. Please wait a moment and try accessing http://localhost:8080"
fi

echo ""
echo "🎉 Deployment completed successfully!"
echo ""
echo "💡 Quick commands:"
echo "   View logs:           docker-compose logs -f"
echo "   Stop services:       docker-compose down"
echo "   Restart services:    docker-compose restart"
echo "   Enter web container: docker-compose exec web bash"
echo "   Database backup:     docker-compose exec database mysqladmin -u attendance_user -p --single-transaction --routines --triggers dump attendance_system > backup.sql"
echo ""
echo "📚 For more information, see DOCKER_GUIDE.md"