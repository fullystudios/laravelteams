# Laravel Teams Package
### What is this?
This is a laravel package that adds team functionality for users in laravel.

### Functionality - in progress
 - A logged in user can create a new team
 - A logged in user can modify its' own teams
 - A logged in user gets a notification when invited to a team
 - A logged in user can accept a team invitation
 - A logged in user can decline a team invitation
 - A team owner can delete a team

### Assumptions of application logic
This package assumes that you have not heavily modified laravels basic Auth scaffolding. This package also creates several tables in your database. These are: teams, invitations … 

### Installation
Package should be available on packagist shortly

1. Include the UserTeams trait in your User model.

### Testing
Run `../../../vendor/bin/phpunit` from project root.

### Gotchas
None reported yet.

### Worklog
I will abandon http functionality and focus on a nice api for adding teams instead. There are at this time too many things with testing a package that goes wrong to secure proper functionality.