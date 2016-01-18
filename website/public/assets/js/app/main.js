(function() {
  $(document).ready(function() {
    var environment, stripe_key, stripe_key_live, stripe_key_test;
    stripe_key_live = 'pk_live_EhCVbntIqph3ppfNCiN6wq3x';
    stripe_key_test = 'pk_test_HNPpbWh3FV4Lw4RmIQqirqsj';
    environment = $('body').data('environment');
    if (environment === 'production') {
      stripe_key = stripe_key_live;
    } else {
      stripe_key = stripe_key_test;
    }
    console.log(stripe_key);
    new Global();
    new Box();
    new Billing();
    new Payment(stripe_key);
    new Login();
    new Spot();
    return new Card(stripe_key);
  });

}).call(this);
