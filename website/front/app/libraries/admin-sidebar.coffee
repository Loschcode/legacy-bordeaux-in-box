class AdminSidebar

  constructor: ->

    $('#sidebar').on 'mouseenter', @sidebarEnter
    $('#sidebar').on 'mouseleave', @sidebarLeave

  sidebarEnter: ->

    $(this).css('width', '270px')
    $('#sidebar-brand').css('display', 'block')

  sidebarLeave: ->

    $(this).css('width', '60px')
    $('#sidebar-brand').css('display', 'none')


module.exports = AdminSidebar