module.exports =
    default :
        files : [
            "app/assets/scripts/**/*.{coffee,js}"
            "app/assets/stylesheets/**/*{sass,scss}"
        ]
        tasks : ["default"]
    dist :
        files : [
            "app/assets/scripts/**/*.{coffee,js}"
            "app/assets/stylesheets/**/*.{sass,scss}"
        ]
        tasks : ["dist"]