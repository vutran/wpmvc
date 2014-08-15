module.exports =
    options :
        separator : ";"
    dev :
        expand : true
        cwd : "app/assets/scripts/"
        src : "**/*.js"
        dest: "app/_tmp/scripts/"
        ext: ".js"
    vendor :
        expand : true
        cwd : "app/assets/vendor/"
        src : "**/*.js"
        dest : "app/_tmp/vendor/"
        ext : ".js"
    tmpToAssets :
        src : [
            "app/_tmp/vendor/**/*.js"
            "app/_tmp/scripts/**/*.js"
        ]
        dest : "assets/scripts/app.js"