# Require the controller library of Gotham
Controller = require 'core/controller'
CustomBox = require 'libraries/custom-box'

class BoxForm extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->
    
    # Load custom box module
    new CustomBox()

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->


# Export
module.exports = BoxForm
