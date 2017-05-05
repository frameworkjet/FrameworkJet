var path = require('path');

module.exports = function(grunt) {
    grunt.initConfig({
        handlebars: {
            compile: {
                files: {
                    // "./public/js/templates.js": "./public/templates/*.handlebars",
                    "./public/js/templates.js": [
                        "./public/templates/*.handlebars",
                        "./public/templates/*/*.handlebars"
                    ]
                },
                options: {
                    amd: false,
                    namespace: "Handlebars.templates",
                    processName: function (filePath) {
                        return path.basename(filePath, '.handlebars');
                    },
                    partialsUseNamespace: true,
                    processPartialName: function (filePath) {
                        return path.basename(filePath, '.handlebars');
                    },
                    partialRegex: /^par_/
                }
            }
        },
        uglify: {
            js: {
                files: {
                    './public/js/lib.min.js': [
                        './public/js/lib/*.js'
                    ],
                    './public/js/custom.min.js': [
                        './public/js/controllers/*.js',
                        './public/js/templates.js',
                        './public/js/config.js',
                        './public/js/routes.js',
                        './public/js/app.js',
                        './public/js/translations/*.js'
                    ]
                }
            }
        },
        less: {
            production: {
                options: {
                    paths: ['./public/css']
                },
                files: {
                    './public/css/style.css': './public/css/style.less'
                }
            }
        },
        sass: {
            dist: {
                files: {
                    './public/css/style.css': './public/css/style.scss'
                }
            }
        },
        clean: {
            cache: {
                src: ['./cache/*']
            },
            logs: {
                src: ['./logs/*']
            }
        },
        watch: {
            scripts: {
                files: [
                    './public/templates/*.handlebars',
                    "./public/templates/*/*.handlebars",

                    './public/js/lib/*.js',
                    './public/js/controllers/*.js',
                    './public/js/templates.js',
                    './public/js/config.js',
                    './public/js/routes.js',
                    './public/js/app.js',
                    './public/js/translations/*.js',

                    './public/css/style.less',

                    './Templates/*',
                    './Templates/*/*'
                ],
                tasks: ['default']
            }
        }
    });

    grunt.registerTask('default', ['handlebars', 'uglify', 'less', 'clean:cache']);

    grunt.loadNpmTasks('grunt-contrib-handlebars');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-clean');

    grunt.loadNpmTasks('grunt-contrib-watch');
}