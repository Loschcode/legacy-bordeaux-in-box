#--------------------------------------------------------------------------
# Start
#--------------------------------------------------------------------------
#
# Gotham will run after that file the "router" system. It's the right place
# to put some code to execute globally like the init of jQuery plugins, etc.
##

##
# Polify placeholders for old browsers
##
$('input, textarea').placeholder()

##
# Notify errors from froms validated by Laravel
##
_.notificationFormErrors()

##
# Notify success message returned by Laravel
##
_.notificationSuccessMessage()

##
# Chosen select form
##
$('.js-chosen').chosen
  disable_search_threshold: 30

##
# Labelauty (Prettify checkboxes and radios html tags)
##
$(':checkbox').labelauty()
$(':radio').labelauty()