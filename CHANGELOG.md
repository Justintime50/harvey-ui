# CHANGELOG

## Next Release

- Adds a spinner on project and deployment screens showing when a deployment is in progress. The screen will refresh every 5 seconds to update the status
- Upgrades mariadb from 11.3.2 to 11.4.3

## v2.0.0 (2024-06-10)

- Upgrades Laravel from 10 to 11
- Drops support for PHP 8.1
- Upgrades MariaDB from `10.11` to `11.1.3`
- Swaps colored text statuses to easily-understood emoji and ensures statuses come as the first element in tables for easier readability
- Fixes a bug that returned `null` instead of an empty list when there were no deployments for a project leading to the inability to do counts on null
- Bumps all dependencies

## v1.0.0 (2023-09-01)

- Adds the ability to redeploy a project with the click of a button
- Adds `runtime` to deployments
- Adds deployment runtime graphs to each project
- Various tweaks to the UI for a more usable and appealing experience
- Fixes all routes to follow RESTful conventions
- Adds test suite

## v0.4.0 (2023-03-31)

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
