module.exports =
    dev : [
        "newer:copy:fonts"
        "newer:imagemin"
        "newer:svgmin"
        "sass:dev"
        "compileJS"
        "clean:tmp"
        "watch"
    ]
    dist : [
        "clean"
        "copy:fonts"
        "imagemin"
        "svgmin"
        "sass:dist"
        "concat:dev"
        "coffee:dist"
        "uglify"
        "clean:tmp"
    ]
    compileJS : [
        "concat:dev"
        "coffee:dev"
        "coffeelint:dev"
        "concat:tmpToAssets"
    ]
    default : ["dev"]
    build : ["dist"]