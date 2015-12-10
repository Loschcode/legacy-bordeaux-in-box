exports.config =
  # See http://brunch.io/#documentation for docs.
  paths:
    public: '../public'
  files:
    javascripts:
      joinTo:
        'javascripts/app.js': /^app/
        'javascripts/vendor.js': /^(?!app)/
    stylesheets:
      joinTo:
        'stylesheets/vendor.css': /^bower_components/
        'stylesheets/front.css': /^app\/styles\/front/
        'stylesheets/easygo.css': /^app\/styles\/easygo/


    templates:
      joinTo: 'javascripts/app.js'

  plugins:
    sass:
      mode: 'ruby'
