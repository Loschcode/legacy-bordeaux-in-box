##
# Gotham
# 
# Coffeescript framework for lazy front-end developers
#
# @author  Ges Jeremie <http://www.gesjeremie.fr>
# @copyright Copyright (c) 2014, Ges Jeremie
# @since Version 1.0
##

# Require the gotham view
view = require 'core/view'

##
# Controller Class
#
# Provide basic structure of a controller
#
##
class Controller 

  # Flag to know if we must run or not the controller
  _gotham_stop: false

  ##
  # Constructor
  #
  # The constructor
  #
  ##
  constructor: ->

  ##
  # Stop
  #
  # If we call this method in the before method,
  # it will not execute the run() method
  #
  ##
  stop: ->

    @_gotham_stop = true

  ##
  # Log
  #
  # Shortcut to display a console.log
  #
  # @param [Mixed] Value to display
  # 
  ##
  log: (value) ->

    if _.isObject(value) or _.isArray(value)

      return console.table(value)

    console.log(value)

  ##
  # On
  #
  # Shortcut to create a jquery "on" event
  #
  # @param [String] Trigger to listen (Ex. click)
  # @param [String] The selector to attach
  # @param [Function] The callback
  # 
  ##
  on: (trigger, selector, handler) ->

    $(selector).on trigger, handler

  ##
  # Off
  #
  # Shortcut to create a jquery "off" event
  #
  # @param [String] Trigger to shutdown (Ex. click)
  # @param [String] The selector attached
  # @param [Function] The handler
  # 
  ##
  off: (trigger, selector, handler) ->

    $(selector).off trigger, handler

  ##
  # Delayed
  #
  # Like "on" jquery event but listen new elements
  # added in the page
  #
  # @param [String] Trigger to listen (Ex. click)
  # @param [String] The selector to attach
  # @param [Function] The callback
  # 
  ##
  delayed: (trigger, selector, handler) ->

    $(document).on trigger, selector, handler

  ##
  # View
  #
  # Shortcut to render a template
  #
  # @param [String] Template to compile
  # @param [Object] Datas to compile with the template
  # 
  ##
  view: (template, datas) ->

    view = new view()
  
    view.render(template, datas)

module.exports = Controller