module.exports =
    options :
        separator : ";"
    dev :
        expand : true
        cwd : "app/assets/scripts/"
        src : "**/*.js"
        dest: "app/_tmp/scripts/"
        ext: ".js"
    tmpToAssets :
        src : "app/_tmp/scripts/**/*.js"
        dest : "assets/scripts/app.js"