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

switch _.getApp()
  when 'masterbox'
    new BootstrapMasterboxDefault()
  when 'masterbox-admin'
    new BootstrapMasterboxDefault()
    new BootstrapMasterboxAdmin()
