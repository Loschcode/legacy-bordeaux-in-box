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
# Export each modules of Gotham
##
module.exports = 
  Application:  require 'core/application'
  Controller:   require 'core/controller'
  Router:       require 'core/router'
  Syphon:       require 'core/syphon'
  Validator:    require 'core/validator'
  View:         require 'core/view'