##
# Start
# 
# Gotham execute that file just before to run the router and the controller. 
# It's the right place to execute some "global" code, activate some jquery Plugins
# etc ...
##


##
# Tooltipster
# 
# We activate the plugin tooltipster.
##
if $('[data-gotham=tooltipster]').length > 0

  $('[data-gotham=tooltipster').tooltipster()