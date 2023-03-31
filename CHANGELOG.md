# CHANGELOG

## Next Release

- Adds authentication system
  - Users must now be logged in to view or perform actions with Harvey
  - Harvey does not have any concept of permissions, anyone given access to Harvey has access to it all
  - Added profile page where you can change your password
- Overhauls UI to account for new Harvey deployment attempt schema in responses
- Adds project webhook to project page
- Fixes a bug where a default deployment and project count wasn't set if an error occured when hitting the Harvey API
- Migrate from Webpack to Vite
- Bumps all dependencies

## v0.3.0 (2022-11-21)

- Removes `harvey` prefix from all routes
- Adds a count of deployments and projects to the UI

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
