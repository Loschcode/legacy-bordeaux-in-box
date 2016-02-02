# Require the controller library of Gotham
Controller = require 'core/controller'


class ChooseSpot extends Controller

  currentGoogleMap: false

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    if $('input:checked').length > 0

      id = $('input:checked').first().attr('id')
      @showGoogleMap(id)

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @on 'click', 'label', (e) =>

      id = $(e.currentTarget).attr('for')

      @showGoogleMap(id)


  showGoogleMap: (id) => 

    if id != @currentGoogleMap

      if @currentGoogleMapMap != false 
        @hideGoogleMap(@currentGoogleMap)

      # Show
      $('#gmap-' + id).stop().hide().removeClass('+hidden').fadeIn()

      # Flag
      @currentGoogleMap = id

  hideGoogleMap: (id) =>

    $('#gmap-' + id).addClass('+hidden')


# Export
module.exports = ChooseSpot
