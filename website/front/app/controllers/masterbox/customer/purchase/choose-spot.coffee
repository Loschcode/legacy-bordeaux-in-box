# Require the controller library of Gotham
Controller = require 'core/controller'


class ChooseSpot extends Controller

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

    @on 'click', 'label', @displayGoogleMap

  displayGoogleMap: ->

    # Hide each google map buttons
    $('[id^=gmap]').addClass('+hidden')

    id = $(this).attr('for')

    # If it's not already displayed
    if $('#gmap-' + id).hasClass('+hidden')

      # Display it
      $('#gmap-' + id).stop().hide().removeClass('+hidden').fadeIn()


# Export
module.exports = ChooseSpot
