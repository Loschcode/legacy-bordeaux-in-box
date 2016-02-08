#--------------------------------------------------------------------------
# Boot
#--------------------------------------------------------------------------
#
# Gotham runs after that file the built-in router system. It's the common 
# place to init some jQuery plugins or some defaults for the project. 
# In short, bootstrap your app here.
##
BootstrapMasterboxDefault = require 'bootstrap/masterbox/default'
BootstrapMasterboxAdmin = require 'bootstrap/masterbox/admin'
BootstrapMasterboxFront = require 'bootstrap/masterbox/front'

# Polify placeholders
$('input, textarea').placeholder()

# Avoid console log if production
if _.getEnvironment() is 'production'

  console.log = ->
    return

switch _.getApp()
  when 'masterbox'
    new BootstrapMasterboxDefault()
    new BootstrapMasterboxFront()
  when 'masterbox-admin'
    new BootstrapMasterboxDefault()
    new BootstrapMasterboxAdmin()
