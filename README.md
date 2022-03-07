<div align="center">

# Harvey UI

The UI for the the lightweight Docker Compose deployment platform - Harvey.

[![Build](https://github.com/Justintime50/harvey-ui/workflows/build/badge.svg)](https://github.com/Justintime50/harvey-ui/actions)
[![Licence](https://img.shields.io/github/license/justintime50/harvey-ui)](LICENSE)

<img src="https://raw.githubusercontent.com/justintime50/assets/main/src/harvey/showcase.png" alt="Showcase">

</div>

Harvey is the lightweight Docker Compose deployment platform. The API and backend can be found here: https://github.com/Justintime50/harvey.

## Install

```bash
# Copy the env files, and edit as needed
cp src/.env-example src/.env && cp database.env-example database.env

# Run the setup script which will bootstrap all the requirements, spin up the service, and migrate the database
./setup.sh
```

### Environment Variables

**Required**

- `HARVEY_DOMAIN_PROTOCOL`
- `HARVEY_DOMAIN`
- `HARVEY_SECRET`

**Optional**

- `HARVEY_TIMEOUT`
- `HARVEY_PAGE_SIZE`

## Usage

Visit `harvey-ui.localhost` in a browser to get started.

## Deploy

```bash
# Deploy the project locally
docker compose up -d

# Deploy the project in production
docker compose -f docker-compose.yml -f docker-compose-prod.yml up -d
```

## Development

```bash
# Install dependencies
composer install

# Migrate the database
composer migrate
composer migrate-fresh

# Clean the database
composer db-clean

# Seed the database
composer seed

# Lint the PHP files
composer lint

# Compile SASS and Javascript during development
npm run dev

# Compile for production
npm run prod

# Watch for CSS and Javascript changes
npm run watch
```
