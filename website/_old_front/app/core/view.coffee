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
# View class
#
# Class who manage "views"
#
##
class View 

  ##
  # Constructor
  #
  # The constructor
  #
  ##
  constructor: ->

  ##
  # Render
  #
  # Render a template
  #
  # @param [String] Template to compile
  # @param [Object] Datas to compile with the template
  # 
  ##
  render: (template, datas) ->

    template = require 'views/' + template

    return template(datas)

module.exports = View