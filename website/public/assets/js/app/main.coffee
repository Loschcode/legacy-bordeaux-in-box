$(document).ready ->

  # Key used by stripe to give a token
  stripe_key_live = 'pk_live_EhCVbntIqph3ppfNCiN6wq3x'
  stripe_key_test = 'pk_test_HNPpbWh3FV4Lw4RmIQqirqsj'

  # Fetch global environment
  environment = $('body').data('environment')

  if environment is 'production'
    stripe_key = stripe_key_live
  else
    stripe_key = stripe_key_test

  console.log stripe_key
  
  new Global()
  new Box()
  new Billing()
  new Payment(stripe_key)
  new Login()
  new Spot()
  new Card(stripe_key)