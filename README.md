# WordPress MVC Theme Skeleton

WordPress based MVC theme skeleton.

# Features
 - Create and organize application specific Models and Views
 - Manage PHP packages with Composer
 - Managed JS packages with NPM
 - Bundle assets with webpack
 - Develop with ES6 in the front-end

# General Documentation

## Install Node Packages

Open the Terminal switch the current directory to your theme folder and run `npm install`.

Once npm has installed all the necessary node packages, you can begin compiling Grunt (see below for more information).

## Install Composer Packages

Open the Terminal and switch the current directory to your theme folder and run `php composer.phar install`.

Once composer has installed all the necessary vendor packages and create an autoload file in the `vendor/` directory inside of your theme folder, you can continue to view your website.

## Compiling with webpack

You are required to install node in your environment to be able to webpack. Please refer to the official documentation for more information.

`webpack`

## Running with Docker

You can choose to use Docker with this theme file easily. Make sure Docker Toolbox is currently installed on your computer. Once installed, just run `docker-compose up` when switched to this directory. You don't even need to have WordPress installed in your project folder since everything is contained in Docker!

### Booting up the first time

When you boot up the container for the very first time, you will have to go through the WordPress setup flow and set a wp-admin username and password.

## Your Application Resources

Your app codebase should be stored in the `app` folder located in your theme's folder.

***You shouldn't touch anything outside of the theme folder unless you absolutely have to.***

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

### Assets

Please store all static assets files (fonts, images, scripts, and stylesheets) in the `app/assets` folder respectively. The framework will automatically load bundle everything into the `dist/bundle.js` file.
