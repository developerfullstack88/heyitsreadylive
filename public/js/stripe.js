$(function() {
  var $form         = $("#form-signin-task");
  // Create a Stripe client.
  stripe = Stripe($form.data('stripe-publishable-key'));
    /* ------- Set up Stripe Elements to use in checkout form ------- */
    var elements = stripe.elements();
    // Create an instance of the card Element.
    var style = {
      base: {
        // Add your base input styles here. For example:
        color: '#fff',
        background:'#fff',
        '::placeholder': {
          color: '#fff',
        },
        iconColor: '#3C85CE',
      },
    };
    var card = elements.create('card',{hidePostalCode: true,style:style});
    card.mount("#card-element");

    card.on('change', ({error}) => {
      var $form         = $("#form-signin-task"),
      inputSelector = ['input[type=email]', 'input[type=password]','input[type=text]', 'input[type=file]',
      'textarea'].join(', '),
      $inputs       = $form.find('.required').find(inputSelector),
      $errorMessage = $form.find('div.error'),
      valid         = true;

      $errorMessage.addClass('hide');
      $('.has-error').removeClass('has-error');
      $inputs.each(function(i, el) {
        var $input = $(el);
        if ($input.val() === '') {
          $input.parent().addClass('has-error');
          $errorMessage.removeClass('hide');
          e.preventDefault();
        }
      });
      const displayError = document.getElementById('card-errors');
      if (error) {
        $('.error').removeClass('hide').find('.alert').text(error.message);
      }
});

    $("#form-signin-task").bind('submit', function(e) {
      e.preventDefault();
      createToken();
    });

    function createToken() {
      stripe.createToken(card).then(function(result) {
        if (result.error) {
            $('.error').removeClass('hide').find('.alert').text(result.error.message);
        } else {
          var token = result.token.id;
          $form.find('input[type=text]').empty();
          $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
          $form.get(0).submit();
        }
      });
    };
});
  /*$("#form-signin-task").bind('submit', function(e) {
    var $form         = $("#form-signin-task"),
    inputSelector = ['input[type=email]', 'input[type=password]','input[type=text]', 'input[type=file]',
    'textarea'].join(', '),
    $inputs       = $form.find('.required').find(inputSelector),
    $errorMessage = $form.find('div.error'),
    valid         = true;
    $errorMessage.addClass('hide');
    $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('hide');
        e.preventDefault();
      }
    });

    if (!$form.data('cc-on-file')) {
      e.preventDefault();
      Stripe($form.data('stripe-publishable-key'));
      Stripe.card.createToken({
        number: $("input[name=cardnumber]").val(),
        cvc: $("input[name=cvc]").val(),
        exp_month: $("input[name=cvc]").val(),
        exp_year: $('#card_year').val()
      }, stripeResponseHandler);
    }
  });

  function stripeResponseHandler(status, response) {
    if (response.error) {
      $('.error').removeClass('hide').find('.alert').text(response.error.message);
    } else {
      /* token contains id, last4, and card type
      var token = response['id'];
      $form.find('input[type=text]').empty();
      $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
      $form.get(0).submit();
    }

  }
});*/
