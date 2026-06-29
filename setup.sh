#!/bin/bash
set -euo pipefail

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

info()  { echo -e "${BLUE}[INFO]${NC} $1"; }
ok()    { echo -e "${GREEN}[OK]${NC} $1"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
err()   { echo -e "${RED}[ERR]${NC} $1"; }

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$PROJECT_DIR"

echo -e "${GREEN}"
echo "============================================"
echo "  Bike Ecommerce - Docker Setup"
echo "============================================"
echo -e "${NC}"

# --------------------------------------------------
# Step 1: Prerequisites
# --------------------------------------------------
echo -e "\n${YELLOW}[1/9] Checking prerequisites...${NC}"

if ! command -v docker &> /dev/null; then
    err "Docker not found."
    echo "  Please install Docker Desktop for Windows:"
    echo "  https://docs.docker.com/desktop/setup/install/windows-install/"
    echo ""
    echo "  After installing:"
    echo "    1. Enable WSL 2 backend in Docker Desktop Settings"
    echo "    2. Go to Settings -> Resources -> WSL Integration"
    echo "    3. Enable integration with your Ubuntu distro"
    echo "    4. Restart your WSL terminal"
    exit 1
fi
ok "Docker found: $(docker --version)"

if ! docker compose version &> /dev/null; then
    err "Docker Compose v2 not available."
    exit 1
fi
ok "Docker Compose found: $(docker compose version)"

if ! docker info &> /dev/null; then
    err "Docker daemon is not running."
    echo "  Please start Docker Desktop and try again."
    exit 1
fi
ok "Docker daemon is running"

# --------------------------------------------------
# Step 2: .env file
# --------------------------------------------------
echo -e "\n${YELLOW}[2/9] Setting up .env file...${NC}"

if [ ! -f .env ]; then
    cp .env.example .env

    sed -i "s/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/" .env
    sed -i "s|# DB_HOST=127.0.0.1|DB_HOST=db|" .env
    sed -i "s|# DB_PORT=3306|DB_PORT=5432|" .env
    sed -i "s|# DB_DATABASE=laravel|DB_DATABASE=laravel|" .env
    sed -i "s|# DB_USERNAME=root|DB_USERNAME=sail|" .env
    sed -i "s|# DB_PASSWORD=$|DB_PASSWORD=password|" .env

    sed -i "s/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/" .env
    sed -i "s/TYPESENSE_HOST=localhost/TYPESENSE_HOST=typesense/" .env

    ok ".env created with Docker service settings"
else
    warn ".env already exists — skipping (make sure DB/REDIS/TYPESENSE hosts point to Docker service names)"
fi

# --------------------------------------------------
# Step 3: Node.js
# --------------------------------------------------
echo -e "\n${YELLOW}[3/9] Checking Node.js...${NC}"

if ! command -v node &> /dev/null; then
    info "Node.js not found — installing Node.js 22..."
    sudo apt update && sudo apt install -y ca-certificates curl
    curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
    sudo apt install -y nodejs
    ok "Node.js $(node --version) installed"
else
    ok "Node.js $(node --version) found"
fi

if ! command -v npm &> /dev/null; then
    sudo apt install -y npm
fi
ok "npm $(npm --version) found"

# --------------------------------------------------
# Step 4: Build Docker images
# --------------------------------------------------
echo -e "\n${YELLOW}[4/9] Building Docker images (first time may take 5-10 minutes)...${NC}"

docker compose build app
ok "Docker images built"

# --------------------------------------------------
# Step 5: Start containers
# --------------------------------------------------
echo -e "\n${YELLOW}[5/9] Starting containers...${NC}"

docker compose up -d
ok "All containers started"

# --------------------------------------------------
# Step 6: Wait for PostgreSQL
# --------------------------------------------------
echo -e "\n${YELLOW}[6/9] Waiting for PostgreSQL to be ready...${NC}"

DB_USER="${DB_USERNAME:-sail}"
DB_NAME="${DB_DATABASE:-laravel}"

for i in $(seq 1 30); do
    if docker compose exec -T db pg_isready -U "$DB_USER" -d "$DB_NAME" &> /dev/null; then
        ok "PostgreSQL is ready"
        break
    fi
    if [ "$i" -eq 30 ]; then
        err "PostgreSQL did not become ready in time"
        echo "  Check logs: docker compose logs db"
        exit 1
    fi
    sleep 2
done

# --------------------------------------------------
# Step 7: Install Composer dependencies
# --------------------------------------------------
echo -e "\n${YELLOW}[7/9] Installing Composer dependencies...${NC}"

docker compose exec -T app composer install --prefer-dist --no-interaction
ok "Composer dependencies installed"

# --------------------------------------------------
# Step 8: Generate app key + storage link
# --------------------------------------------------
echo -e "\n${YELLOW}[8/9] Configuring application...${NC}"

docker compose exec -T app php artisan key:generate --force
ok "Application key generated"

docker compose exec -T app php artisan storage:link --force || true
ok "Storage link created"

# --------------------------------------------------
# Step 9: Migrate + seed
# --------------------------------------------------
echo -e "\n${YELLOW}[9/9] Running migrations and seeders...${NC}"

docker compose exec -T app php artisan migrate --seed --force
info "Running bike product seeder..."
docker compose exec -T app php artisan db:seed --class=BikeProductSeeder --force
ok "Database migrated and seeded"

# --------------------------------------------------
# Bonus: Install npm dependencies
# --------------------------------------------------
echo -e "\n${YELLOW}[Bonus] Installing npm dependencies for Vite...${NC}"

npm install
ok "npm dependencies installed"

# --------------------------------------------------
# Summary
# --------------------------------------------------
echo -e "\n${GREEN}"
echo "============================================"
echo "  Setup Complete!"
echo "============================================"
echo -e "${NC}"
echo ""
echo "  App:      http://localhost:8000"
echo ""
echo "  Commands:"
echo "    docker compose logs -f    # View all logs"
echo "    docker compose down       # Stop everything"
echo "    docker compose up -d      # Start again"
echo "    docker compose exec app php artisan ...   # Run artisan"
echo "    npm run dev               # Vite HMR (hot reload)"
echo ""
echo "  Services:"
echo "    PostgreSQL :5432"
echo "    Redis      :6379"
echo "    Typesense  :8108  (dashboard: http://localhost:8108)"
echo ""
