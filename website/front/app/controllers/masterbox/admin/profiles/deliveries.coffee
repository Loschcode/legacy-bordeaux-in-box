# Require the controller library of Gotham
Controller = require 'core/controller'

class Deliveries extends Controller

  before: ->

  run: ->

    @delayed 'change', '[name=order_status]', @updateStatus
    @delayed 'change', '[name=order_payment_way]', @updatePaymentWay

  ##
  # Update status of the order via Ajax
  ##
  updateStatus: (e) =>

    $th = $(e.currentTarget).parent()
    $loader = $th.find('.js-loader')
    
    $loader.html '<i class="fa fa-spin fa-refresh"></i> Mise à jour'

    # Prepare datas to send     
    datas = 
      _token: $th.data('token')
      order_id: $th.data('order-id')
      order_status: $(e.currentTarget).val()

    # Execute request
    request = $.post '/service/api/update-status-order', datas

    request.done (response) =>
      
      $loader.html ''

      if response.success

        @showSuccess(response.message)

  showSuccess: (message) ->

    swal
      title: 'Succès'
      text: message
      type: 'success'
      confirmButtonColor: '#A5DC86'
      html: true

  updatePaymentWay: (e) =>


    $th = $(e.currentTarget).parent()
    $loader = $th.find('.js-loader')
    
    $loader.html '<i class="fa fa-spin fa-refresh"></i> Mise à jour'

    # Prepare datas to send     
    datas = 
      _token: $th.data('token')
      order_id: $th.data('order-id')
      order_payment_way: $(e.currentTarget).val()

    # Execute request
    request = $.post '/service/api/update-payment-way-order', datas

    request.done (response) =>
      
      $loader.html ''

      if response.success

        @showSuccess(response.message)



# Export
module.exports = Deliveries