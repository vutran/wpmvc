module.exports =
    default : [
        "clean"
        "imagemin"
        "sass:default"
        "concat:default"
        "coffee:default"
        "concat:tmpToDist"
        "uglify"
        "clean:tmp"
    ]
    dist : [
        "clean"
        "imagemin"
        "sass:dist"
        "concat:default"
        "coffee:dist"
        "uglify"
        "clean:tmp"
    ]