# Badminton League Tables

## ToDo
#### Required
- Remove the `retired_on` key from teams
  - Removed in favour of the `season_teams` table
- Implement API calls from admin pages
  - New scorecard has no API call
  - Edit scorecard has no user feedback from API call
  - Edit teams has no backend function or API call
- Write the help page  
  
#### Optional
- Store user information and token in the client browser
  - Localstorage api looks promising
- Dark mode 
  - Need to figure out the colors
- Workers / Scheduling
  - Auto create seasons at the end of each day
  - Send emails when scorecards added
- Users
  - Admins can log into this web management arena
  - Non-admins log in to a different portal 
    - Can delete account
    - Can subscribe / unsubscribe from emails
  
---

Provides a way for badminton leagues to manage the league scoreboard
with constant updates and data integrity.

## Technologies
What technologies are bing used in this stack

### Frontend
The frontend is written in JavaScript using VueJS, compiled and served by nginx

### Backend
The backend is written in PHP using Lumen

### Docker
