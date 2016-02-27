# Require the controller library of Gotham
Controller = require 'core/controller'


class Index extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    @freewall()

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

  freewall: =>

    wall = new freewall("#freewall")

    wall.reset
      selector: '.js-brick',
      animate: false,
      cellW: 220,
      cellH: 'auto',

      onResize: ->
        wall.fitWidth()
          
    wall.container.find('.js-brick img').load ->
      wall.fitWidth()
    
    $(window).trigger('resize')

# Export
module.exports = Index
