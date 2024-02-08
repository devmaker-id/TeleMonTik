<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('favicon.ico'); ?>" type="image/png">
    <title><?= base_url(); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="<?= base_url('/js/jQuery.v3.7.1.js'); ?>"></script>
  </head>
  <body>
    
    <div class="container p-3">
      <h3>Daftarkan Telegram Dan Mikrotik</h3>
      <form action="<?=base_url('/simpan_data'); ?>" method="post">
        <div class="mb-3">
          <label for="telegramid" class="form-label">Telegram ID</label>
          <input <?= ($telegram_id ? "readonly" : "type='number'"); ?> class="form-control" id="telegramid" placeholder="id telegram" name="teleid" value="<?= ($telegram_id ? $telegram_id : 000000); ?>">
        </div>
        <div class="mb-3">
          <label for="hostmikrotik" class="form-label">Host/IP Mikrotik</label>
          <input type="text" class="form-control" id="hostmikrotik" placeholder="ip:port" name="hostmikrotik">
        </div>
        <div class="mb-3">
          <label for="username" class="form-label">Username Mikrotik</label>
          <input type="text" class="form-control" id="username" placeholder="username" name="username">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password Mikrotik</label>
          <input type="password" class="form-control" id="password" placeholder="password" name="password">
        </div>
        
        <i id="infomk"></i>
        <div id="kirimForm"></div>
      </form>
      <hr>
      <button class="btn btn-info btn-sm" id="teskoneksi">Teskoneksi</button>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
      $("#teskoneksi").click(function(e){
        $("#teskoneksi").attr("disable", true);
        $("#infomk").attr("class", "text-info");
        $("#infomk").text("Mohon menunggu bos, sampai text ini berganti status.");
        var postForm = {
          'teleid': $('input[name=teleid]').val(),
          'host': $('input[name=hostmikrotik]').val(),
          'username': $('input[name=username]').val(),
          'password': $('input[name=password]').val()
        };
        $.ajax({
          url: "<?= base_url('/requestdata'); ?>",
          type: "post",
          data: postForm ,
          success: function (response) {
            var data = JSON.parse(response);
            if(data.success){
              $("#teskoneksi").attr("disable",data.btn);
              $("#infomk").attr("class", data.color);
              $("#infomk").text(data.info);
              $("#kirimForm").html(data.btnKirim);
              $("input").attr("readonly",true);
              console.log(response);
            } else {
              $("#infomk").attr("class", data.color);
              $("#infomk").text(data.info);
              console.log(response);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
             console.log(textStatus, errorThrown);
          }
        });
      });
    </script>
  </body>
</html>