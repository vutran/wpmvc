module.exports =
    default :
        expand : true
        cwd : "app/assets/scripts/"
        src : "**/*.coffee"
        dest: "app/_tmp/scripts/"
        ext: ".js"
    dist :
        options :
            join : true
        src : "app/assets/scripts/**/*.coffee"
        dest : "app/_tmp/scripts/app.js"