##
# Gotham
# 
# Coffeescript framework for lazy front-end developers
#
# @author  Ges Jeremie <http://www.gesjeremie.fr>
# @copyright Copyright (c) 2014, Ges Jeremie
# @since Version 1.0
##


##
# Router Class
#
# Parse URIs and determines routing
#
##
class Router
  
  # Pull every routes to match
  _routes: []

  # Current request (Ex. zombie/27/edit)
  _request: ''

  # Check if the router matched
  _success: false

  ##
  # Pull the response of the matched route
  # @example
  #   _response:
  #    controller: 'zombie/edit'
  #    params:
  #      id: 27
  ##
  _response: {}

  ##
  # Constructor
  #
  # Set the current request
  #
  ##
  constructor: ->

    @_request = @_slashes(window.location.pathname)

  ##
  # Match
  #
  # @param [String] The pattern to match against the current request
  # @param [String] The controller and the file to execute if it's match
  # 
  # @example Router.match('zombie/:id/edit', 'zombie#edit')
  ##
  match: (pattern, controller, constraint) ->

    pattern = @_slashes(pattern)

    @_routes.push
      pattern: pattern
      controller: controller
      parsed: @_parse_pattern(pattern)
      variables: @_fetch_variables(pattern)
      constraint: constraint

  ##
  # Run
  #
  # Routes pushed via match() are waiting to be tested. This method
  # run the system to test every patterns against the current
  # request url
  # 
  ##
  run: ->

    for route in @_routes

      # Check if the pattern match the request
      if route.parsed.test(@_request)

        # At this point we match the route
        # but we don't put right now the flag
        # because maybe the route have a constraint
        # and will fail
        success = true

        params = {}

        if route.variables?

          # Fetch every params of the request
          params_request = route.parsed.exec(@_request)

          for variable, index in route.variables

            # Create param with the value from current request
            params[variable] = params_request[index + 1]

        # Success match, now we will check if the route pass the constraint function
        if route.constraint?

          # Check if it's a function 
          if _.isFunction(route.constraint)

            success_constraint = route.constraint(params)

            # With this condition we prevent an "undefined" return
            if success_constraint is false

              success = false

        if success is true

          # Set flag success
          @_success = true

          # Return the response
          @_response = 
            controller: @_decode(route.controller)
            params: params

          # We already found, stop execution
          break

  ##
  # Passes
  #
  # Tell you if the router matched one pattern
  # 
  # @return [Bool]
  ##
  passes: ->

    return @_success

  ##
  # Fails
  #
  # Tell you if the router failed to match one pattern
  # 
  # @return [Bool]
  ##
  fails: ->

    unless @_success

      return true

    return false

  ##
  # Response
  # 
  # Pull the response of the matched route
  ##
  response: ->

    return @_response

  ##
  # Decode
  #
  # Just will decode the sugared string controller of match()
  # to be ready to require()
  #
  # @param  [String] The string to decode
  #
  # @example
  #   _decode('zombies#edit') -> zombies/edit
  # 
  # @return [String] The string decoded
  ##
  _decode: (controller) ->

    return controller.split('#').join('/')

  ##
  # Slashes
  #
  # Eat the first and last slash of a string
  # 
  # @param  [String]  The string to treat
  # @return [String]  The string without slash  
  ##
  _slashes: (str) ->

    if str 

      # Eat last slash
      if str[str.length-1] is '/'

        str = str.substr(0, str.length - 1)

      # Eat all first slashes
      while str.charAt(0) is '/'
        str = str.substr(1)


    return str

  ##
  # Parse pattern
  #
  # Will parse the variables of the pattern to match normal url stuff
  # 
  # @return [Regex] Pattern regexified
  ##
  _parse_pattern: (pattern) ->

    # Regex to find every variables of the pattern (Ex. /zombies/:id/edit})
    variables = /(:[a-zA-Z_]*)/g

    # We will replace variables found by an another regex
    # to match normal url stuff.
    #
    # Example: 
    # "/zombies/:id/edit" becomes "/users/([a-zA-Z0-9-_]*)/edit"
    # 
    # So now the pattern can match something like :
    # "/users/27/edit"
    # "/users/zombie-forever-99/edit"
    # "/users/zombie_forever-22/edit"
    #
    pattern = pattern.replace(variables, '([a-zA-Z0-9-_]*)')

    # We will regexify the pattern
    return new RegExp('^' + pattern + '$')

  ##
  # Fetch variables
  #
  # Will fetch every variables of the pattern given
  # 
  # @params   [String] The pattern
  # @return   [Object] Variables found
  ##
  _fetch_variables: (pattern) ->

    # Regex to find every variables of the pattern (Ex. /users/:id)
    variables = /(:[a-zA-Z_]*)/g

    # Match regex against the pattern
    variables = pattern.match(variables)

    # Variables found ?
    if variables?
      for variable, index in variables

        variables[index] = variable.replace(':', '')

    return variables

      
module.exports = Router