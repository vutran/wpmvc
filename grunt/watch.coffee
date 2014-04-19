module.exports =
    options :
        livereload : true
    coffee :
        files : ["app/assets/scripts/**/*.coffee"]
        tasks : ["compileJS"]
    js :
        files : ["app/assets/scripts/**/*.js"]
    sass :
        files : ["app/assets/stylesheets/**.*{sass,scss}"]
        tasks : ["sass:dev"]
    images :
        files : ["app/assets/images/*"]
    fonts :
        files : ["app/assets/fonts/*"]
    tasks : ["dev"]