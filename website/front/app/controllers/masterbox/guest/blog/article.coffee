# Require the controller library of Gotham
Controller = require 'core/controller'


class Article extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    $("#share").jsSocials
      shares: ["facebook", "pinterest", "twitter", "email"]
      showCount: true
      showLabel: true
      text: $('#gotham').data('text')
      facebook: 
        label: "Partager"
      pinterest:
        media: $('#gotham').data('pinterest-media')
  


  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->


# Export
module.exports = Article
