$(document).ready(function() {
  
  /*print message*/
  $.fn.printMessage = function(opsi) {
      var opsi = $.extend({
          type: 'success',
          message: 'Success',
          timeout: 500000
      }, opsi);

      $(this).hide();
      $(this).html('<div class="alert alert-' + opsi.type + ' alert-dismissible fade show" role="alert"><strong>' + opsi.message + '</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
      $(this).slideDown('slow');
      // Run the effect
      setTimeout(function() {
          $('.message-alert').slideUp('slow');
      }, opsi.timeout);

      var parentElem  = $(this);

      $(this).find('.message-alert .close').click(function(event) {
        event.preventDefault();
        parentElem.html('');
      });
  };

});