# Require the controller library of Gotham
Controller = require 'core/controller'
Config = require 'config'


class Payments extends Controller


  before: ->

    # Init datatable
    @table = $('table').DataTable
      length: false
      language: Config.datatable.language.fr
      order:
        [[1, 'asc']]

     
  run: ->

    @delayed 'click', '.js-more', @displayMore

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
      
      datas =
        stripe_customer: tr.data('stripe-customer')
        stripe_event: tr.data('stripe-event')
        stripe_charge: tr.data('stripe-charge')
        stripe_card: tr.data('stripe-card')

      html = @view('masterbox.admin.profiles.payments.more', datas)

      row.child(html).show()
      tr.find('.more-details i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o')

# Export
module.exports = Payments
