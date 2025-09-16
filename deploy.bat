@echo off
setlocal enabledelayedexpansion

echo.
echo  Attendance Management System - Docker Deployment
echo ==================================================
echo.

REM Check if Docker is installed
docker --version >nul 2>&1
if !errorlevel! neq 0 (
    echo Docker is not installed. Please install Docker Desktop first.
    pause
    exit /b 1
)

REM Check if Docker Compose is installed
docker-compose --version >nul 2>&1
if !errorlevel! neq 0 (
    echo  Docker Compose is not installed. Please install Docker Compose first.
    pause
    exit /b 1
)

echo  Checking Docker installation...
echo  Docker is installed

REM Stop existing containers if running
echo.
echo  Stopping any existing containers...
docker-compose down >nul 2>&1

REM Build and start services
echo.
echo   Building and starting services...
docker-compose up -d --build

if !errorlevel! neq 0 (
    echo  Failed to start services. Please check Docker logs.
    pause
    exit /b 1
)

REM Wait for services to be ready
echo.
echo  Waiting for services to be ready...
timeout /t 30 >nul

REM Display service status
echo.
echo  Service Status:
docker-compose ps

REM Display access information
echo.
echo  Access Information:
echo ================================
echo Main Application:    http://localhost:8080
echo phpMyAdmin:          http://localhost:8081
echo Database Host:       localhost:3306
echo.
echo Database Credentials:
echo - Database:          attendance_system
echo - Username:          attendance_user
echo - Password:          attendance_pass
echo - Root Password:     rootpassword
echo.

REM Test application accessibility
echo  Testing application...
timeout /t 5 >nul
curl -s -f http://localhost:8080 >nul 2>&1
if !errorlevel! equ 0 (
    echo   Application is accessible!
) else (
    echo   Application may not be ready yet. Please wait a moment and try accessing http://localhost:8080
)

echo.
echo    Deployment completed successfully!
echo.
echo    Quick commands:
echo    View logs:           docker-compose logs -f
echo    Stop services:       docker-compose down
echo    Restart services:    docker-compose restart
echo    Enter web container: docker-compose exec web bash
echo.
echo    For more information, see DOCKER_GUIDE.md
echo.
echo Opening application in browser...
start http://localhost:8080

pause