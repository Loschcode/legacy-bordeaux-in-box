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
# It returns the stripe key depending the environment
##
_.mixin getStripeKey: ->

  environment = $('body').data('environment')

  if environment is 'production'
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

  return number.toFixed(2) + ' &euro;'

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
# If laravel returned a form error, it displays a sweet alert
##
_.mixin notificationFormErrors: ->

  hasErrors = _.trim($('#gotham').data('form-errors'))

  if _.isEmpty(hasErrors)
    return

  unless hasErrors == '1'
    return

  titleErrors = _.trim($('#gotham').data('form-errors-title'))
  textErrors = _.trim($('#gotham').data('form-errors-text'))

  # Guess tittle
  unless _.isEmpty(titleErrors)
    title = titleErrors
  else
    title = 'Attention'

  # Guess text
  unless _.isEmpty(textErrors)
    text = textErrors
  else
    text = 'Des erreurs sont présentes dans le formulaire'


  # Open the modal
  swal
    title: title
    text: text
    type: 'error'
    confirmButtonColor: '#D83F66'
    html: true
    timer: 1800

##
# If laravel returned a success message, it displays a sweet alert
##
_.mixin notificationSuccessMessage: ->

  successMessage = _.trim($('#gotham').data('success-message'))

  if _.isEmpty(successMessage)
    return

  swal
    title: 'Bravo !'
    text: successMessage
    type: 'success'
    confirmButtonColor: '#A5DC86'
    html: true
