# Require the controller library of Gotham
Controller = require 'core/controller'
Config = require 'config'


class Index extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    ##
    # Init datatable
    ##
    @table = $('table').DataTable
      length: false
      language: Config.datatable.language.fr
      ajax: $('table').data('request')
      processing: true
      serverSide: true
      order:
        [[1, 'asc']]
      columns: [
        {
          orderable: false
          className: 'more-details'
          data: null
          defaultContent: '<a href="#" class="button button__table"><i class="fa fa-plus-square-o"></i></a>'
        }
        { data: "id" }
        { data: "full_name" }
        { data: "email" }
        { data: "phone_format" }
        {
          render: (date, type, full, meta) =>

            datas = 
              focus_profile: _.slash($('table').data('focus-profile'))

            return @view('masterbox.admin.customers.render_subscriptions', _.extend(datas, full))
        }
        { 
          render: (data, type, full, meta) ->
            _.euro(full.turnover)
        }
        { data: "city"}
        { data: "zip"}
        { 
          render: (data, type, full, meta) => 
            address = full.address 

            unless _.isEmpty(full.address_detail)
              address += ' (' + full.address_detail + ')'

            return address
        }
        {
          sortable: false,
          render: (data, type, full, meta) =>

            datas = 
              link_edit: _.slash($('table').data('edit-customer')) + full.id
              link_focus: _.slash($('table').data('focus-customer')) + full.id
            return @view('masterbox.admin.customers.actions', datas)
        }
      ]

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @delayed 'click', '.more-details', @displayMore

  displayMore: (e) =>

    e.preventDefault()

    tr = $(e.currentTarget).closest('tr')
    row = @table.row(tr)

    if row.child.isShown()
      row.child.hide()
      tr.find('.more-details i').removeClass('fa-minus-square-o').addClass('fa-plus-square-o')
    else
      datas = row.data()
      datas['focus_profile'] = $('table').data('focus-profile')

      html = @view 'masterbox.admin.customers.more', datas

      row.child(html).show()
      tr.find('.more-details i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o')




# Export
module.exports = Index
