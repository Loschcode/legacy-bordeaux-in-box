# Require the controller library of Gotham
Controller = require 'core/controller'


class ChooseFrequency extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @on 'click', 'label', @smoothScroll

  ##
  # Auto scroll to the submit button
  ##
  smoothScroll: (e) ->

    smoothScroll.animateScroll(null, '#commit')
    

# Export
module.exports = ChooseFrequency
