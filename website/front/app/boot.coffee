#--------------------------------------------------------------------------
# Boot
#--------------------------------------------------------------------------
#
# Gotham runs after that file the built-in router system. It's the common 
# place to init some jQuery plugins or some defaults for the project. 
# In short, bootstrap your app here.
##
Default = require 'bootstrap/default'
Admin = require 'bootstrap/admin'
Masterbox = require 'bootstrap/masterbox'

# Polify placeholders
$('input, textarea').placeholder()

# Avoid console log if production
if _.getEnvironment() is 'production'

  console.log = ->
    return

switch _.getApp()
  when 'masterbox'
    new Default()
    new Masterbox()
  when 'masterbox-admin'
    new Default()
    new Admin()
  when 'company-admin'
    new Default()
    new Admin()
