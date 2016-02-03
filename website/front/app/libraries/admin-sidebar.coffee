#--------------------------------------------------------------------------
# Admin Sidebar
#--------------------------------------------------------------------------
#
# Manage the admin sidebar
#
##
class AdminSidebar

  ##
  # Construct a new admin sidebar
  ##
  constructor: ->

    $('#sidebar').on 'mouseenter', @sidebarEnter
    $('#sidebar').on 'mouseleave', @sidebarLeave

  ##
  # When the mouse enters in the sidebar
  ##
  sidebarEnter: ->

    $(this).css('width', '270px')
    $('#sidebar-brand').css('display', 'block')

  ##
  # When the mouse leaves the sidebar
  ##
  sidebarLeave: ->

    $(this).css('width', '60px')
    $('#sidebar-brand').css('display', 'none')


# Export
module.exports = AdminSidebar