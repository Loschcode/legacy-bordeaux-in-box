##
# Gotham
# 
# Coffeescript framework for lazy front-end developers
#
# @author Ges Jeremie <http://www.gesjeremie.fr>
# @copyright Copyright (c) 2014, Ges Jeremie
# @since Version 1.0
##

# Require the gotham router
router = require 'core/router'

##
# Application Class
#
# Run the framework
#
##
class Application

  ##
  # Constructor
  #
  # Constructor of the application
  #
  ##
  construct: ->

  ##
  # Start
  #
  # Run the application
  #
  ##
  start: () ->

    # Load underscore mixins
    require 'helpers'

    # Load handlebars helpers
    require 'views'

    # Load validators
    require 'validators'
    
    # Instance of the router
    router = new router()
      
    # We will include the routes and we will give the instance of the router
    require('routes')(router)

    # Start
    require 'start'

    # Run the router
    router.run()

    # Check if the router routed
    if router.passes()
      
      # Fetch the result of the router
      response = router.response()

      # Init controller
      @_controller(response)

  ##
  # Controller
  #
  # When the router match a route this method 
  # is called and run the controller wanted
  #
  ##
  _controller: (response) ->

    # Require the controller matched
    controller = require('controllers/' + response.controller)

    # Invoke
    controller = new controller()

    # Check if the before method exists in the controller
    if controller['before']?

      # We run the before method of the controller
      controller.before(response.params)

    # Check if the controller returns a "stop" action from before filter
    unless controller._gotham_stop

      # We run the controller
      controller.run(response.params)


module.exports = Application