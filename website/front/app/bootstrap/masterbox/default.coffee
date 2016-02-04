class Default

  constructor: ->

    @polyfillPlaceholders()
    @notificationFormErrors()
    @notificationSuccessMessage()
    @notificationErrorMessage()
    @chosenSelect()
    @labelautyForm()
    @tooltipster()
    @inputMaskDate()
    @responsiveMenu()
    @stickyFooter()

  ##
  # Polyfill placeholders 
  ##
  polyfillPlaceholders: ->

    $('input, textarea').placeholder()

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
  # If laravel returned a success message, it displays a sweet alert
  ##
  notificationSuccessMessage: ->

    successMessage = _.trim($('#gotham').data('success-message'))

    if _.isEmpty(successMessage)
      return

    swal
      title: 'Succès'
      text: successMessage
      type: 'success'
      confirmButtonColor: '#A5DC86'
      html: true

  ##
  # If laravel returned an error message, it displays a sweet alert
  ##
  notificationErrorMessage: ->

    errorMessage = _.trim($('#gotham').data('error-message'))

    if _.isEmpty(errorMessage)
      return

    swal
      title: 'Erreur'
      text: errorMessage
      type: 'error'
      confirmButtonColor: '#D83F66'
      html: true
      timer: 4000

  ##
  # Prettify selects
  ##
  chosenSelect: ->

    $('.js-chosen').chosen
      disable_search_threshold: 30

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
  # Set responsive to the menu
  ##
  responsiveMenu: ->

    if $('.js-menu-sidebar').length > 0
      $('.js-menu-sidebar').slicknav
        label: "SECTIONS"
    else
      $('.js-menu').slicknav()

  ##
  # Always stick that fucking footer 
  # on the bottom of the page
  ##
  stickyFooter: ->
    docHeight = $(window).height()
    footerHeight = $('.js-footer-stick').height()
    footerTop = $('.js-footer-stick').position().top + footerHeight
    
    if footerTop < docHeight
      $('.js-footer-stick').css('margin-top', 10 + (docHeight - footerTop) + 'px')
    




module.exports = Default