# CHANGELOG

## v0.2.0 (2022-05-01)

- Renames all `pipeline` references to `deployment` to match the Harvey backend
- Adds authentication to Harvey API with basic auth when present
- Adds `lock` and `unlock` functionality for projects
- Wraps all HTTP requests in try/catch blocks to gracefully handle exceptions
- Adds reusable flash messaging
- Adds `sentry` integration
- Bumps default timeout from 5 to 10 seconds

## v0.1.0 (2022-02-24)

- Initial release
- Can view a list of projects from Harvey
- Can view a list of deployments per project and their status and output
- Scaffolding for future features
