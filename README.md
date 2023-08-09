<div align="center">

# Harvey UI

The UI for the the lightweight Docker Compose deployment platform - Harvey.

[![Build](https://github.com/Justintime50/harvey-ui/workflows/build/badge.svg)](https://github.com/Justintime50/harvey-ui/actions)
[![Licence](https://img.shields.io/github/license/justintime50/harvey-ui)](LICENSE)

<img src="https://raw.githubusercontent.com/justintime50/assets/main/src/harvey/showcase.png" alt="Showcase">

</div>

Harvey is the lightweight Docker Compose deployment platform. The API and backend can be found here: <https://github.com/Justintime50/harvey>.

## Install

```bash
# Copy the env files, and edit as needed
cp src/.env-example src/.env && cp .env-example .env

# Run the setup script which will bootstrap all the requirements, spin up the service, and migrate the database
just setup
```

### Environment Variables

#### Required

- `HARVEY_DOMAIN`
- `HARVEY_SECRET`

#### Optional

- `HARVEY_DOMAIN_PROTOCOL` (`http` vs `https` - defaults to `http`)
- `HARVEY_TIMEOUT` (defaults to `10` seconds)
- `HARVEY_PAGE_SIZE` (defaults to `20` records)

## Usage

Visit `harvey-ui.localhost` in a browser to get started.

### Default Login

The default login is `admin@harvey.com` and `password`. **Make sure to update the email/password after first login!**

## Deploy

```bash
# Deploy the project locally
just run

# Deploy the project in production
just prod
```

## Development

```bash
# Get a comprehensive list of development tools
just --list
```
