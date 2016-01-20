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
# Example
#
# Check if the user is batman
#
# @param [string] Name of the user
##
_.mixin isBatman: (name) ->

  if name.toLowerCase() is "batman"
    return true

  return false

_.mixin hasFormErrors: ->

  hasErrors = _.trim($('#gotham').data('form-errors'))

  if _.isEmpty(hasErrors)
    return false

  if hasErrors == '1'
    return true

  return false

_.mixin notificationFormErrors: ->

  if _.hasFormErrors()
    swal
      title: 'Erreur'
      text: 'Des erreurs sont présentes dans le formulaire'
      type: 'error'
      confirmButtonColor: '#D83F66'
      html: true