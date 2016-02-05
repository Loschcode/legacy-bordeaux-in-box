# Require the controller library of Gotham
Controller = require 'core/controller'
Config = require 'config'


class Index extends Controller


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
        { data: "contract_id" }
        { data: @dataCustomer }
        { data: @dataCountOrdersNotSent }
        { data: @dataCountPaymentsDone }
        { data: "readable_status" }
        { data: "readable_priority"}
        { data: "created_at" }
        {
          sortable: false
          render: (data, type, full, meta) =>
            
            console.log full

            datas =
              link_focus: _.slash($('table').data('focus-profile')) + full.id
              link_delete: _.slash($('table').data('delete-profile')) + full.id

            return @view('masterbox.admin.profiles.actions', datas)


        }
      ]

  run: ->
    @delayed 'click', '.more-details', @displayMore

  ##
  # Display more datas in the table
  ##
  displayMore: (e) =>

    e.preventDefault()

    tr = $(e.currentTarget).closest('tr')
    row = @table.row(tr)

    if row.child.isShown()
      row.child.hide()
      tr.find('.more-details i').removeClass('fa-minus-square-o').addClass('fa-plus-square-o')
    else
      datas = row.data()

      html = @view('masterbox.admin.profiles.more', datas)

      row.child(html).show()
      tr.find('.more-details i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o')

  ##
  # Fetch the data customer
  ##
  dataCustomer: (row, type, val, meta) ->

    link = _.slash($('table').data('focus-customer')) + row.customer.id

    return '<a class="button button__link" href="' + link + '">' + row.customer.full_name + '</a>'


  ##
  # Fetch the number of orders not sent
  # @return integer
  ##
  dataCountOrdersNotSent: (row, type, val, meta) ->

    orders = row.orders
    orders_not_sent = 0

    _.forEach orders, (value, key) ->

      if value.date_sent is null
        orders_not_sent++

    return orders_not_sent

  ##
  # Fetch the number of payments done
  # @return integer
  ##
  dataCountPaymentsDone: (row, type, val, meta) ->

    return row.payments.length

# Export
module.exports = Index
