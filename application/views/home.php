<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Short N>
 valid URL="author" content="Mohsin Shaikh">
    <link rel="icon" href="">

    <title>SHORTEN URL Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= BASE_URL('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= BASE_URL('assets/css/style.css'); ?>" rel="stylesheet">
  </head>

  <body>
    <script type="text/javascript">
      var BASE_URL  = '<?= BASE_URL() ?>';
      var csrf      = '<?= $this->security->get_csrf_token_name(); ?>';
      var token     = '<?= $this->security->get_csrf_hash(); ?>';
    </script>

    <!-- As a heading -->
    <nav class="navbar navbar-light bg-light">
      <a href="<?= base_url(); ?>">
        <span class="navbar-brand mb-0 h1">SHORTEN</span>
      </a>
    </nav>

    <div class="container-fluid">
     
      <div class="row">
        <div class="col-lg-12" style="background-image: url('<?=BASE_URL('assets/images/filter.jpg') ?>'); background-repeat: no-repeat; background-size: 100% 270%;">
          
            <?= form_open('', [
              'name'    => 'form_url', 
              'class'   => 'form-horizontal', 
              'id'      => 'form_url', 
              'enctype' => 'multipart/form-data', 
              'method'  => 'POST'
              ]); ?>
                  

              <div class="input-group mb-3" id="url-and-button">
                <input type="text" name="url" id="url" class="form-control" placeholder="Paste link to shorten it">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" id="url-button" type="button" data-toggle="tooltip" data-placement="top" title="Click to SHORTEN">SHORTEN</button>
                  <button class="btn btn-outline-secondary" id="copy-button" type="button" hidden="" data-toggle="tooltip" data-placement="top" title="Click to Copy">COPY</button>
                  <button class="btn btn-outline-secondary" id="reset-button" type="reset" hidden="" data-toggle="tooltip" data-placement="top" title="Click to Reset">RESET</button>
                </div>
              </div>
              
              <span class="loading loading-hide">
              <img src="<?= BASE_URL('assets/images/loading-spin-primary.svg'); ?>"> 
              <i>Loading, Saving data</i>
              </span>
              <div class="message"></div>

            <?= form_close(); ?>
          
        </div>
          
        <hr>
        
        <div class="col-lg-12">

          <h1 class="text-center mt-3">TOP URLs</h1>

          <table class="table">
            <thead>
              <tr>
                <th>Sr No.</th>
                <th>Short URL</th>
                <th>Orignal URL</th>
                <th>Hits</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 0; foreach ($urls as $row): $i++; ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= base_url($row->short_link) ?></td>
                  <td><?= substr($row->link, 0, 80) ?></td>
                  <td><?= $row->counter ?></td>
                </tr>
              <?php endforeach ?>
             
            </tbody>
          </table>

        </div>


      </div>
      

      <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
          <div class="col-lg-12">
            <small class="d-block mb-3 text-muted">Designed & Developed By <strong>Mohsin Shaikh</strong> &copy; 2017-2018</small>
          </div>
        </div>
      </footer>
    </div> <!-- container end -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= BASE_URL('assets/jquery/jquery-2.2.3.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= BASE_URL('assets/js/app.js'); ?>"></script>
    <script src="<?= BASE_URL('assets/popper/popper.min.js'); ?>"></script>
    <script src="<?= BASE_URL('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>

<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

  $(document).ready(function(){

    $("form").submit(function(e){
        e.preventDefault();
    });

    $("#url-button").on("click", function(event){
        url_form_submit();
    });

    $("#reset-button").on("click", function(event){
        $('#url-button').show();
        $('.message').hide();
        $('#copy-button').attr("hidden","");
        $('#reset-button').attr("hidden","");
    });

    $(document).on("keypress", function(event){
      if(event.which == 13) {
        url_form_submit();
      }
    });
  
    function url_form_submit() {
      var form_url = $('#form_url');
      var data_post = form_url.serializeArray();

      data_post.push();

      $('.message').hide();
      $('.loading').show();
    
      $.ajax({
        url: BASE_URL + 'shorturl/createCode',
        type: 'POST',
        dataType: 'json',
        data: data_post,
        cache:false,
      })
      .done(function(res) {
        if(res.success) {
          
          var shorturl = res.short_link; 
          $('#url').val(BASE_URL + shorturl);

          $('#url-button').hide();
          $('#copy-button').removeAttr("hidden");
          $('#reset-button').removeAttr("hidden");
          
          
          //Copy to Clipboard
          copyToClipboard( $('#url') );

          $('.message').printMessage({message : res.message});
          $('.message').fadeIn();

        } else {
          $('.message').printMessage({message : res.message, type : 'warning'});
        }
      })
      .fail(function() {
        $('.message').printMessage({message : 'Error save data', type : 'danger'});
      })
      .always(function() {
        $('.loading').hide();
      });
      return false;
    }

    $("#copy-button").on("click", function(event){
      copyToClipboard( $('#url') );
    });
    $(".copy-button").on("click", function(event){
      copyToClipboard( $('#url') );
    });

    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).val()).select();
      document.execCommand("copy");
      $temp.remove();
    }

  });
</script>

  </body>
</html>
