module.exports =
    options :
        bare : true
        join : true
    dev :
        expand : true
        cwd : "app/assets/scripts/"
        src : "**/*.coffee"
        dest: "app/_tmp/scripts/"
        ext: ".js"
    dist :
        src : "app/assets/scripts/**/*.coffee"
        dest : "app/_tmp/scripts/app.js"