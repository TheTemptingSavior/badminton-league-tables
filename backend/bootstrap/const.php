<?php
/*
|--------------------------------------------------------------------------
| Application constants
|--------------------------------------------------------------------------
|
| The constants defined in this file will be used to bootstrap the initial
| application. The goal is to be able to spin up new leagues and configure
| them using these values.
|
*/

// League specific constants
defined('LEAGUE_NAME')      or define('LEAGUE_NAME',        getenv('LEAGUE_NAME') ? getenv('LEAGUE_NAME') : 'Rutland/Stamford');
defined('LEAGUE_CODE')      or define('LEAGUE_CODE',        getenv('LEAGUE_CODE') ? getenv('LEAGUE_CODE') : 'rs');
defined('API_HOST')         or define('API_HOST',           getenv('API_HOST') ? getenv('API_HOST') : 'localhost');

// Admin user to be created on startup
defined('ADMIN_USERNAME')   or define('ADMIN_USERNAME',     getenv('ADMIN_USERNAME') ? getenv('ADMIN_USERNAME') : 'admin');
defined('ADMIN_PASSWORD')   or define('ADMIN_PASSWORD',     getenv('ADMIN_PASSWORD') ? getenv('ADMIN_PASSWORD)') : 'helloworld');

// Application constants
defined('TOKEN_EXPIRATION') or define('TOKEN_EXPIRATION',   3600);
