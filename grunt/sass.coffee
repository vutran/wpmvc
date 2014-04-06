module.exports =
    options :
        style : "nested"
        compass: true

    default :
        expand : true
        cwd : "app/assets/stylesheets/"
        src : "**/*.{sass,scss}"
        dest : "assets/stylesheets/"
        ext : ".css"

    dist :
        options :
            style : "compressed"
        expand : true
        cwd : "app/assets/stylesheets/"
        src : "**/*.{sass,scss}"
        dest : "assets/stylesheets/"
        ext : ".css"