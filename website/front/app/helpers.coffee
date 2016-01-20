#--------------------------------------------------------------------------
# Helpers
#--------------------------------------------------------------------------
#
# If you need to create some functions to use in your application, you are
# in the right place !
#
# Gotham uses lo-dash and the concept of mixins.
#
# @see http://gothamjs.iodocumentation/1.0.0/helpers
##

##
# If laravel returned a form error, it displays a sweet alert
##
_.mixin notificationFormErrors: ->

  hasErrors = _.trim($('#gotham').data('form-errors'))

  if _.isEmpty(hasErrors)
    return

  unless hasErrors == '1'
    return

  swal
    title: 'Erreur'
    text: 'Des erreurs sont présentes dans le formulaire'
    type: 'error'
    confirmButtonColor: '#D83F66'
    html: true

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
