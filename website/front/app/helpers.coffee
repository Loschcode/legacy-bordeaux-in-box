##
# Helpers
#
# In this file you can add your function helpers
#
# We use underscore and the concept of mixins for this
# @see http://underscorejs.org/#mixin
##
_.mixin
  capitalize: (string) ->
    return string.charAt(0).toUpperCase() + string.substring(1).toLowerCase()



