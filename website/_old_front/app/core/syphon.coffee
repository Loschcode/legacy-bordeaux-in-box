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
# Syphon Class
#
# Fetch datas from a form
#
##
class Syphon

  # All inputs name to exclude from the result
  _exclude: []

  # All inputs name to keep from the result
  _keep: []

  ##
  # Constructor
  #
  # Nothing to read
  #
  ##
  constructor: ->

  ##
  # Exclude
  #
  # Add inputs name to exclude
  #
  ##
  exclude: () ->

    to_exclude = arguments

    if _.isArray(to_exclude[0])

      to_exclude = arguments[0]

    for value in to_exclude

      @_exclude.push(value)

    return @

  ##
  # Keep
  #
  # Add inputs name to keep
  #
  ##
  keep: () ->

    to_keep = arguments

    if _.isArray(to_keep[0])

      to_keep = arguments[0]

    for value in to_keep

      @_keep.push(value)

    return @

  ##
  # Get
  #
  # Fetch datas from a from (selector jquery)
  #
  ##
  get: (selector) ->

    # Serialize datas with array way
    datas_serialized = $(selector).serializeArray()

    # Init object
    datas = {}

    # We will loop datas and create a new datas object
    _.each datas_serialized, (data) =>

        unless data.name in @_exclude
          
          # Add value
          datas[data.name] = data.value

    unless _.isEmpty(@_keep)

      return _.pick(datas, @_keep)

    # Reset keep and exclude datas
    @_exclude = []
    @_keep = []

    return datas

module.exports = Syphon