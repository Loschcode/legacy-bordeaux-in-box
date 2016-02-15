#--------------------------------------------------------------------------
# Helpers
#--------------------------------------------------------------------------
#
# If you need to create some functions to use in your application, you are
# in the right place !
#
# Gotham uses lo-dash and the concept of mixins.
#
# @see http://gothamjs.io/documentation/1.0.0/helpers
##
Config = require 'config'

##
# It returns the current app (masterbox, masterbox-admin, etc ..)
##
_.mixin getApp: ->

  return $('body').data('app')


##
# It returns the current environment
##
_.mixin getEnvironment: ->

  return $('body').data('environment')

##
# It returns the stripe key depending the environment
##
_.mixin getStripeKey: ->

  if _.getEnvironment() is 'production'
    return Config.stripe.production

  return Config.stripe.testing

##
# Add slash at the end of a string if needed
##
_.mixin slash: (string) ->

  # Get Last char
  last = string.slice(-1)

  if last is '/'
    return string

  return string + '/'

##
# Format a number and suffix with euro symbol
##
_.mixin euro: (number) ->

  return parseFloat(number).toFixed(2) + ' &euro;'


##
# Translate the profile status
##
_.mixin profileStatus: (status) ->

  return switch status
    when 'in-progress' then 'En cours de création'
    when 'expired' then 'Expiré'
    when 'not-subscribed' then 'Non abonné'
    when 'subscribed' then 'Abonné'
    else status

##
# Returns the right css class
##
_.mixin colorProfileStatusButton: (status) ->

  return switch status
    when 'in-progress' then '--blue'
    when 'expired' then '--red'
    when 'not-subscribed' then '--red'
    when 'subscribed' then '--green'
    else '--blue'


