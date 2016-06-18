# WordPress MVC Theme Skeleton (WPMVC)

WordPress MVC framework -- Docker, webpack, hot reloading, Babel/ES6, SCSS, PostCSS

# Features
 - WordPress/MySQL contained within Docker
 - Create and organize application specific Models, and Views
 - Manage PHP packages with Composer
 - Managed JS packages with NPM
 - Modules bundling and hot reloading with webpack on a separate node.js server
 - Develop with ES6 in the front-end
 - View architecture for easily separating presentation and business logic

## Quickstart

To get started, make sure you have [Docker](https://www.docker.com/) installed.

#### Install Composer packages

````bash
$ docker-compose up install
````

#### Update Composer packages

````bash
$ docker-compose up update
````

#### Boot up the database and web services

````bash
$ docker-compose up db web
````

#### Boot up the a PHPMyAdmin service

````bash
$ docker-compose up phpma
````

#### Boot up the hot reload server

````bash
$ docker-compose up hotReload
````

#### Boot up a development workflow (db, web, PHPMyAdmin, and hotReload)

````bash
$ docker-compose up db web phpma hotReload
````

## Booting up the first time

When you boot up the container for the very first time, you will have to go through the WordPress setup flow and set a WordPress admin username and password.

## Your Application Resources

Your app codebase should be stored in the `app` folder located in your theme's folder.

***You shouldn't touch anything outside of the `app` folder unless you absolutely have to.***

### Models

Models are stored in the `app/models/` directory. Models holds your data for a specific resource like a post or taxonomy term.

### Views

Views should be stored in the `app/views/` directory. These are regular HTML-based template with a mixture of some PHP.

| View Name | View File |
|:---|:---|
|Home Page|views/home.php|
|404 Error Page|views/404.php|
|Search Results|views/search/index.php|
|Tag Archive|views/tag/index.php|
|Taxonomy Archive|views/taxonomy/`{taxonomy-slug}`/index.php|
|Post Type Archive|views/`{post-type}`/index.php|
|Post Type Permalink|views/`{post-type}`/single.php|
|Page Permalink|views/`{page-slug}`.php|

# webpack and Hot Reloading

Please store all static assets files (fonts, images, scripts, and stylesheets) in the `app/assets` folder respectively. webpack will automatically bundle everything into the `dist/bundle.js` file.

Hot reloading is served in a separate node.js container and linked via Docker Compose.

**Note: Please remember to disable hot reloading on production!**
