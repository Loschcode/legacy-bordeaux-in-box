##
# Bootstrap
#
# Skeleton of gotham
##
class Bootstrap

  ##
  # Run
  #
  # Load the helpers, start, and invoke the controller
  ##
  run: ->

    # Load lo-dash helpers
    require 'helpers'

    # Load custom validators
    require 'validators'

    # Load start
    require 'start'

    # Fetch the controller to execute
    controller = $('#gotham').data('controller')

    # No controller defined
    unless controller?
      return

    # Controller defined is empty
    if _.isEmpty(controller)
      return

    # Format to the right path
    pathController = @_formatPath(controller)

    # Invoke controller
    controller = require 'controllers/' + pathController
    controller  = new controller()

    # Check if we have a method before
    if controller['before']?

      # Run the before method
      controller.before()

    unless controller._gothamStop

      # Run the controller
      controller.run()

  ##
  # Format path
  #
  # Will replace all dots by a slash
  #
  # @param [String] The string to format
  #
  ##
  _formatPath: (str) ->

    str.split('.').join('/')


module.exports = Bootstrap
