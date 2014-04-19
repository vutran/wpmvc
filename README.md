# WordPress MVC Theme Skeleton

WordPress based MVC theme skeleton.

# Features
 - Create and organize application specific Models and Views
 - Manage third-party PHP libraries/packages with Composer
 - Manage and compile static assets with Grunt
   - Install third-party Frontend libraries with Bower
   - Frontend development with CoffeeScript, SASS and Bourbon
   - Image and SVG minifications

# General Documentation

## Install Node Packages

Open the Terminal switch the current directory to your theme folder and run `npm install`.

Once npm has installed all the necessary node packages, you can begin compiling Grunt (see below for more information).

## Install Composer Packages

Open the Terminal and switch the current directory to your theme folder and run `php composer.phar install`.

Once composer has installed all the necessary vendor packages and create an autoload file in the `vendor/` directory inside of your theme folder, you can continue to view your website.

## Compiling with Grunt

You are required to install node in your server or local environment to be able to Grunt. Please refer to the official documentation for more information.

`grunt dev`

Compile all static assets (development)

`grunt dist`

Compile the static assets (distribution)

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

Please store all static assets files (fonts, images, scripts, and stylesheets) in the `app/assets` folder respectively. Grunt will then compile these assets into the public `assets` folder for your theme.