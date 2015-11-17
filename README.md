# WordPress MVC Theme Skeleton

WordPress MVC framework -- Docker, webpack, Babel/ES6, SCSS, PostCSS

# Features
 - WordPress/MySQL contained within Docker
 - Create and organize application specific Models, and Views
 - Manage PHP packages with Composer
 - Managed JS packages with NPM
 - Modules bundling and hot reloading with webpack on a separate node.js server
 - Develop with ES6 in the front-end
 - MVC architecture for easily seperating your business logic and markup

# General Documentation

## Running with Docker

Make sure Docker Toolbox is currently installed on your computer. Once installed, just run `docker-compose up` when switched to this directory. You don't even need to have WordPress installed in your project folder since everything is contained in Docker!

### Booting up the first time

When you boot up the container for the very first time, you will have to go through the WordPress setup flow and set a WordPress admin username and password.

## Your Application Resources

Your app codebase should be stored in the `app` folder located in your theme's folder.

***You shouldn't touch anything outside of the `app` folder unless you absolutely have to.***

### Models

Models are stored in the `app/models/` directory. Models holds your data for a specific resource like a post or taxonomy term.

### Views

Views should be stored in the `app/views/` directory. These are regular HTML-based template with a mixture of some PHP.

| View Name | Vilew File |
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

### Note: Please remember to disable hot reloading on production!
