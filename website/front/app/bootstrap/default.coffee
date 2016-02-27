class Default

  constructor: ->

    @notificationFormErrors()
    @processNotifications()
    @chosenSelect()
    @labelautyForm()
    @tooltipster()
    @inputMaskDate()
    @stickyFooter()
    @textareaAutosize()

  ##
  # Notify errors from forms validated by Laravel
  ##
  notificationFormErrors: ->

    hasErrors = _.trim($('#gotham').data('form-errors'))

    if _.isEmpty(hasErrors)
      return

    unless hasErrors == '1'
      return

    titleErrors = _.trim($('#gotham').data('form-errors-title'))
    textErrors = _.trim($('#gotham').data('form-errors-text'))

    # Guess tittle
    unless _.isEmpty(titleErrors)
      title = titleErrors
    else
      title = 'Attention'

    # Guess text
    unless _.isEmpty(textErrors)
      text = textErrors
    else
      text = 'Des erreurs sont présentes dans le formulaire'


    # Open the modal
    swal
      title: title
      text: text
      type: 'error'
      confirmButtonColor: '#D83F66'
      html: true
      timer: 1750

  ##
  # Sweet alert don't stack the alerts shown
  # This process will handle two modals stacked
  # if laravel returns an error message and a success message
  # we display the error and AFTER the success.
  # Else we just let as is.
  ##
  processNotifications: =>

    if @hasNotificationErrorMessage() and @hasNotificationSuccessMessage()

      notificationSuccess = @notificationSuccessMessage

      # Run notification error and attach callback success
      @notificationErrorMessage(notificationSuccess)

    else

      # Process default
      @notificationErrorMessage()
      @notificationSuccessMessage()

  ##
  # If laravel returned a success message, it displays a sweet alert
  ##
  notificationSuccessMessage: (callback) =>

    successMessage = @getNotificationSuccessMessage()

    if _.isEmpty(successMessage)
      return

    if _.isFunction(callback)
      swal
        title: 'Succès'
        text: successMessage
        type: 'success'
        confirmButtonColor: '#A5DC86'
        html: true
      , ->
        callback()

    else
      swal
        title: 'Succès'
        text: successMessage
        type: 'success'
        confirmButtonColor: '#A5DC86'
        html: true
  

  ##
  # Fetch the success message
  ##
  getNotificationSuccessMessage: =>

    return _.trim($('#gotham').data('success-message'))

  ##
  # Check if laravel returned a success message.
  ## 
  hasNotificationSuccessMessage: =>
    
    successMessage = @getNotificationSuccessMessage()

    if _.isEmpty(successMessage)
      return false

    return true


  ##
  # If laravel returned an error message, it displays a sweet alert
  ##
  notificationErrorMessage: (callback) =>

    if @hasNotificationErrorMessage()

      if _.isFunction(callback)
        swal
          title: 'Erreur'
          text: @getNotificationErrorMessage()
          type: 'error'
          confirmButtonColor: '#D83F66'
          html: true
          closeOnConfirm: false
        , -> 
          callback()

      else

        swal
          title: 'Erreur'
          text: @getNotificationErrorMessage()
          type: 'error'
          confirmButtonColor: '#D83F66'
          html: true
          timer: 4000
  
  
  ##
  # Check if laravel returned an error message.
  ## 
  hasNotificationErrorMessage: =>
    
    errorMessage = @getNotificationErrorMessage()

    if _.isEmpty(errorMessage)
      return false

    return true

  ##
  # Fetch the error message
  ##
  getNotificationErrorMessage: =>

    return _.trim($('#gotham').data('error-message'))


  ##
  # Prettify selects
  ##
  chosenSelect: ->

    defaults = 
      disable_search_threshold: 30

    $('.js-chosen').each ->

      if this.hasAttribute('data-width')
        config = _.merge({}, defaults, {width: $(this).data('width')})
      else
        config = defaults
        
      $(this).chosen(config)

  ##
  # Prettify radios / checkboxes
  ##
  labelautyForm: ->

    $(':checkbox').labelauty()
    $(':radio').labelauty()

  ##
  # Tooltips
  ##
  tooltipster: ->

    $('.js-tooltip').tooltipster()

  ##
  # Mask date for inputs
  ##
  inputMaskDate: ->

    $('.js-input-mask-date').inputmask("99/99/9999")

  ##
  # Always stick that fucking footer 
  # on the bottom of the page
  ##
  stickyFooter: =>

    if $('.js-footer-stick').length > 0

      @processStickyFooter()

      $(window).resize =>
        @processStickyFooter()

  processStickyFooter: =>

    docHeight = $(window).height()
    footerHeight = $('.js-footer-stick').height()
    footerTop = $('.js-footer-stick').position().top + footerHeight
    
    if footerTop < docHeight
      $('.js-footer-stick').css('margin-top', 10 + (docHeight - footerTop) + 'px')
  
  ##
  # Init textarea auto size on textarea
  ##
  textareaAutosize: =>

    $('textarea').textareaAutoSize()


module.exports = Default