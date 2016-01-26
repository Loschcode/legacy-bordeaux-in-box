#--------------------------------------------------------------------------
# Errors
#--------------------------------------------------------------------------
#
# If you want to change / add errors for the Validator library, you can
# do it here.
#
# @see http://gothamjs.io/documentation/1.0.0/validator#custom-error
##
Validator.errors
  required: 'Le champ :attribute est requis'
  valid_card_number: 'Numéro de carte invalide'
  valid_card_expiry: 'Date d\'expiration invalide'
  valid_card_cvc: 'Code de vérification invalide'

#--------------------------------------------------------------------------
# Attributes
#--------------------------------------------------------------------------
#
# If you want to change / add attributes for the Validator library, you can
# do it here.
#
# @see http://gothamjs.io/documentation/1.0.0/validator#change-attributes
##
Validator.attributes
  card: 'numéro de carte'
  expiration: 'date d\expiration'
  ccv: 'cvv'

#--------------------------------------------------------------------------
# Custom validation rules
#--------------------------------------------------------------------------
#
# @see http://gothamjs.io/documentation/1.0.0/validator#custom-rules
##
Validator.rule 'valid_card_number', (attribute, value, params) ->

  if $.payment.validateCardNumber(value)
    return true

  return false

Validator.rule 'valid_card_expiry', (attribute, value, params) ->

  expiry = value.split('/')

  if $.payment.validateCardExpiry(expiry[0], expiry[1])
    return true

  return false

Validator.rule 'valid_card_cvc', (attribute, value, params) ->

  if $.payment.validateCardCVC(value)
    return true

  return false
