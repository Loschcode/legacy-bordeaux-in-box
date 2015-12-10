##
# Validators
# 
# Below you will find all validators attribute, rules and errors
##


Validator = require 'core/validator'


##
# Attributes
##
Validator::attributes


##
# Rules
##

##
# Accepted
#
# The field under validation must be yes, on, or 1. This is useful for validating "Terms of Service" acceptance.
##
Validator::rule 'accepted', (attribute, value, params) ->

  if value is 'yes' or value is 'on' or value is '1'

    return true

  return false

##
# Alpha
#
# The field under validation must be entirely alphabetic characters.
##
Validator::rule 'alpha', (attribute, value, params) ->

  if value is undefined or value is ''
    return true
  
  if value.match(/^[a-zA-Z]+$/)
    return true

  return false


##
# Alpha Dash
#
# The field under validation may have alpha-numeric characters, as well as dashes and underscores.
##
Validator::rule 'alpha_dash', (attribute, value, params) ->

  if value is undefined or value is ''
    return true
  
  if value.match(/^[a-zA-Z0-9_-]+$/)
    return true

  return false


##
# Alpha Num
#
# The field under validation must be entirely alpha-numeric characters.
##
Validator::rule 'alpha_num', (attribute, value, params) ->

  if value is undefined or value is ''
    return true
  
  if value.match(/^[a-zA-Z0-9]+$/)
    return true

  return false

##
# Array
#
# The field under validation must be of type array.
##
Validator::rule 'array', (attribute, value, params) ->

  if _.isArray(value)

    return true

  return false


##
# Between
#
# The field under validation must have a size between the given min and max
##
Validator::rule 'between', (attribute, value, params) ->

  if value is undefined or value is ''
    return true

  length = value.toString().length

  if length >= params[0] and length <= params[1]
    return true

  return false

##
# Boolean
#
# The field under validation must be a boolean
##
Validator::rule 'boolean', (attribute, value, params) ->

  if value is true or value is false

    return true

  return false



##
# Required
#
# Check if a field is filled
##
Validator::rule 'required', (attribute, value, params) ->

  unless value
    return false

  if value.length is 0
    return false

  return true

##
# Email
#
# Check if a field is a valid email
##
Validator::rule 'email', (attribute, value, params) ->

  if value is undefined or value is ''
    return true

  valid_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  return valid_email.test value

##
# In
#
# Check if the value of a field is in the values given
##
Validator::rule 'in', (attribute, value, params) ->

  if value is undefined or value is ''
    return true

  value = value.toString()

  success = false

  for param in params
    
    if value is param

      success = true
      break

  return success

##
# Max
#
# Check if the value is inferior than an other
##
Validator::rule 'max', (attribute, value, params) ->

  if value is undefined or value is ''
    return true

  value = parseInt(value)
  constraint = parseInt(params[0])

  if value > constraint

    return false

  return true

##
# Min
#
# Check if the value is superior than an other
##
Validator::rule 'min', (attribute, value, params) ->

  if value is undefined or value is ''
    return true

  value = parseInt(value)
  constraint = parseInt(params[0])

  if value < constraint

    return false

  return true


Validator::rule 'size', (attribute, value, params) ->
  
  if value.length isnt params[0]

    return false

  return true
  
##
# Match
#
# The field under validation must match the field given
##
Validator::rule 'match', (attribute, value, params, datas) ->
  
  # Field given
  field = params[0]

  # Check if the field exists in datas
  if _.has(datas, field)

    value = value.toString()
    value_of_field = datas[field].toString()

    if value is value_of_field
      return true

  return false

##
# Different
#
# The field under validation must be different than the field given
##
Validator::rule 'different', (attribute, value, params, datas) ->
  
  # Field giver  
  field = params[0]

  # Check if the field exists in datas
  if _.has(datas, field)

    value = value.toString()
    value_of_field = datas[field].toString()

    unless value is value_of_field
      return true

  return false

##
# Errors
##
Validator::error 'accepted', 'The :attribute must be accepted'
Validator::error 'alpha', 'The :attribute may only contain letters.'
Validator::error 'alpha_dash', 'The :attribute may only contain letters, numbers, and dashes.'
Validator::error 'alpha_num', 'The :attribute may only contain letters and numbers.'
Validator::error 'array', 'The :attribute must be an array.'
Validator::error 'between', 'The :attribute must be between :option0 and :option1 characters.'
Validator::error 'boolean', 'The :attribute must be a boolean.'
Validator::error 'required', 'The :attribute is required.'
Validator::error 'email', 'The :attribute must be a valid email.'
Validator::error 'in', 'The :attribute must be in :options.'
Validator::error 'max', 'The :attribute can\'t be superior to :option0.'
Validator::error 'min', 'The :attribute can\'t be inferior to :option0.'
Validator::error 'size', 'The :attribute must contain :option0 chars.'
Validator::error 'match', 'The :attribute does not match.'
Validator::error 'different', 'The :attribute isn\'t different.'
