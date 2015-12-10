class @Billing

  #--------------------------------------------------------------------------
  # Constructor
  #--------------------------------------------------------------------------
  #
  # Always called when the class is runned
  #
  constructor: ->

    # Stock "this" into a safe var
    self = @

    if $('#js-page-billing-address').length is 1

      if $('#js-flag-billing-address').length is 1
        @initValidationForm()

      if $('#gift').data('value') == false
        # Copy at init
        @copyBilling()

      # Event to catch copy billing click
      $('#copy-billing').click (event) ->

        event.preventDefault()

        self.copyBilling()

      # Event to catch focusout input or textarea
      $('input, textarea').focusout ->

        self.initValidationForm()

  #--------------------------------------------------------------------------
  # Copy Billing
  #--------------------------------------------------------------------------
  #
  # System to copy informations from billing form to delivery form
  #
  copyBilling: ->

    datas = 
      first_name: $('#billing_first_name').val()
      last_name: $('#billing_last_name').val()
      city: $('#billing_city').val()
      zip: $('#billing_zip').val()
      address: $('#billing_address').val()

    $('#destination_first_name').val(datas.first_name)
    $('#destination_last_name').val(datas.last_name)
    $('#destination_city').val(datas.city)
    $('#destination_zip').val(datas.zip)
    $('#destination_address').val(datas.address)

    # Re run validation
    @initValidationForm()

  #--------------------------------------------------------------------------
  # Init validation form
  #--------------------------------------------------------------------------
  #
  # Check the form like a ninja
  #
  initValidationForm: ->

    self = @

    $('input[type=text], textarea').each ->

      console.log $(this).attr('id')
      value = $.trim($(this).val())

      if value is ''

        self.displayError(@)

      else

        self.displaySuccess(@)

  #--------------------------------------------------------------------------
  # Display error
  #--------------------------------------------------------------------------
  #
  # Display error for a wanted selector
  #
  displayError: (object) ->

    type = $(object).prop('tagName')

    unless $(object).parent().find('i').hasClass('billing-error')

      $(object).parent().find('i').remove()

      if type is 'TEXTAREA'
        $(object).after('<i class="fa fa-times billing-error hidden type-textarea"></i>')
      else
        $(object).after('<i class="fa fa-times billing-error hidden"></i>')

      $(object).parent().find('i').hide().removeClass('hidden').fadeIn()

  #--------------------------------------------------------------------------
  # Display success
  #--------------------------------------------------------------------------
  #
  # Display success for the selector wanted
  #
  displaySuccess: (object) ->

    type = $(object).prop('tagName')

    unless $(object).parent().find('i').hasClass('billing-success')

      $(object).parent().find('i').remove()

      if type is 'TEXTAREA'
        $(object).after('<i class="fa fa-check billing-success hidden type-textarea"></i>')
      else
        $(object).after('<i class="fa fa-check billing-success hidden"></i>')

      $(object).parent().find('i').hide().removeClass('hidden').fadeIn()



