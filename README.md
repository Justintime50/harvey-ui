<div align="center">

# Harvey UI

The UI for Harvey, the lightweight Docker Compose deployment runner.

[![Build](https://github.com/Justintime50/harvey-ui/workflows/build/badge.svg)](https://github.com/Justintime50/harvey-ui/actions)
[![Coverage Status](https://coveralls.io/repos/github/Justintime50/harvey-ui/badge.svg?branch=main)](https://coveralls.io/github/Justintime50/harvey-ui?branch=main)
[![Version](https://img.shields.io/github/v/tag/justintime50/harvey-ui)](https://github.com/justintime50/harvey-ui/releases)
[![Licence](https://img.shields.io/github/license/justintime50/harvey-ui)](LICENSE)

<img src="https://raw.githubusercontent.com/justintime50/assets/main/src/harvey-ui/showcase.png" alt="Showcase">

</div>

[Harvey](https://github.com/Justintime50/harvey) is the lightweight Docker Compose deployment runner. This project serves as a UI on top of the underlying API and service. View your deployment statuses, logs, and runtime history. Lock and unlock deployments as well as redeploying a project with the click of a button.

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

Visit `harvey.localhost` in a browser to get started.

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
