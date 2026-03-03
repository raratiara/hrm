<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
  <meta charset="utf-8" />
  <link rel="icon" href="<?php echo _ASSET_LOGO_TSP; ?>" type="image/PNG" />
  <title>Login Page - <?php echo _TITLE; ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css"
    rel="stylesheet" type="text/css" />
  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/simple-line-icons/simple-line-icons.min.css"
    rel="stylesheet" type="text/css" />
  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
    type="text/css" />
  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/uniform/css/uniform.default.css" rel="stylesheet"
    type="text/css" />
  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-switch/css/bootstrap-switch.min.css"
    rel="stylesheet" type="text/css" />
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/css/select2.min.css" rel="stylesheet"
    type="text/css" />
  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/css/select2-bootstrap.min.css"
    rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL PLUGINS -->
  <!-- BEGIN THEME GLOBAL STYLES -->
  <!-- <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>css/components.min.css" rel="stylesheet" id="style_components" type="text/css" /> -->
  <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>css/plugins.min.css" rel="stylesheet" type="text/css" />
  <!-- END THEME GLOBAL STYLES -->
  <!-- BEGIN PAGE LEVEL STYLES -->
  <link href="<?php echo _ASSET_PAGES_METRONIC_TEMPLATE; ?>css/login.min.css" rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL STYLES -->
  <!-- BEGIN THEME LAYOUT STYLES -->
  <!-- END THEME LAYOUT STYLES -->
  <link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    /* body {
      margin: 0;
      display: flex;
      height: 100vh;
    }


    @media screen and (max-width: 767px) {
      body {
        flex-direction: column;
      }

      .left-side {
        padding: 1rem !important;
        
      }

    }
    

    .left-side {
  background: linear-gradient(to bottom, #b7dbfb 0%, #FFFFFF 100%);
  color: #343851;
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}

    .left-side img {
  width: 80px;
  margin-bottom: 1rem;
  border-radius: 0;
}

   .left-side h2 {
  font-size: 24px;
  font-weight: 600;
  color: #1E2A5A;
  padding-right: 150px;
}

    .left-side .illustration {
  max-width: 500px;
  width: 100%;
}

    .right-side {
      background-color: #FFFFFF;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .right-side h2 {
      font-size: 35px;
      font-weight: 600;
      color: #343851;
      margin-bottom: 3rem;
    }

    .form-group {
      margin-bottom: 1rem;
      min-width: 350px;
    }

    .checkbox {
      margin-top: 1.5rem;
      font-size: 12px;
      color: #343851;
      margin-left: -20px;
    } */

      /* =========================
   DESKTOP layout mirip gambar (tanpa ubah HTML)
========================= */
body{
  height: 100vh;
  display: flex;
  align-items: center;            /* vertikal center */
  justify-content: center;        /* biar ada ruang kiri-kanan */
  background: linear-gradient(to bottom, #b7dbfb 0%, #FFFFFF 100%);
  padding: 35px;                  /* ruang tepi seperti gambar */
}

/* kiri: rata kiri, hero area */
.left-side{
  flex: 1;
  height: 100%;
  border-radius: 18px 0 0 18px;   /* kalau mau halus (opsional) */
  justify-content: center;
  align-items: center;      /* rata kiri */
  padding: 30px 100px;
  position: relative;
}

/* judul kiri atas */
.left-side h2{
  padding-right: 0 !important;
  font-size: 30px;
  line-height: 1.2;
  margin: 0;
  font-weight: 550;
  color: #1E2A5A;
}





/* logo kiri atas (kecil) */
.left-side .logo-default{
  width: 70px !important;
  margin-bottom: 18px;
}

/* ilustrasi kiri agak ke bawah */
.left-side img.illustration{
  max-width: 520px;
  width: 100%;
  
}

/* kanan: jadi card putih mengambang */
.right-side{
  flex: 0 0 600px;                /* lebar card (sesuaikan) */
  height: 90vh;                   /* jangan full tinggi */
  background: #fff;
  border-radius: 38px;
  box-shadow: 0 18px 40px rgba(0,0,0,0.12);
  padding: 120px 100px;
  margin-left: 40px;              /* jarak dari hero kiri */
  align-items: center;
  justify-content: center;
}

/* judul Sign in */
.right-side h2{
  font-size: 34px;
  margin-bottom: 28px;
  text-align: center;
  font-weight: 600;
   color: #343851;
}


/* form lebar pas seperti gambar */
.form-group{
  min-width: 100%;
}

/* rapihin checkbox biar nggak ketarik ke kiri */
.checkbox{
  margin-top: 1.8rem;
      font-size: 12px;
      color: #343851;
      margin-left: -20px;
}


    .form-group label {
      font-size: 13px;
      margin-bottom: 0.5rem;
      display: block;
      color: #343851;
    }

   .form-group input[type="text"],
.form-group input[type="password"] {
  width: 100%;
  padding: 1.2rem 1.8rem;
  border-radius: 30px;
  border: 1px solid #e0e0e0;
  outline: none;
  background: #F1F9FF;
  color: #343851;        
}

.form-group input::placeholder {
  color: #858585;
  font-size: 13px;
}

.form-group input[type="text"]:focus,
.form-group input[type="password"]:focus {
  box-shadow: 0 0 0 2px rgba(52, 56, 81, 0.15);
}



    .form-group .checkbox-label {
      font-size: 12px;
      font-weight: normal;
      color: #343851;
    }

    .login-button {
      width: 100%;
      padding: 1.1rem;
      background-color: #38406F;
      color: white;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 1rem;
      transition: background-color 0.3s ease;
    }

    .login-button:hover {
      background-color: #1a1c3d;
    }

    input::placeholder {
      font-size: 12px;

    }


    

   /* =========================
   MOBILE (mirip gambar)
========================= */
@media (max-width: 768px) {

  body{
    display:block;            /* matikan flex 2 kolom */
    height: 100vh;
    background: linear-gradient(to bottom, #D7EBFC 0%, #FFFFFF 100%);
  }

  /* HERO (atas) */
  .left-side{
    width:100%;
    min-height: 56vh;
    padding: 0px 0px 0px 0 !important;
    justify-content: flex-start;
    align-items: flex-start;
    position: relative;
    border-radius: 0;
  }

  /* judul kiri atas */
  .left-side h2{
    padding-right: 0 !important;
    margin: 0;
    font-size: 20px;
    line-height: 1.15;
  }

  /* logo (kalau mau pojok kanan atas seperti gambar) */
  .left-side .logo-default{
    position: absolute;
    right: 16px;
    top: -8px;
    width: 60px !important;
  }

  /* ilustrasi di tengah */
  .left-side img.illustration{
    width: 100%;
    max-width: 360px;
    margin: 18px auto 0;
    display:block;
  }

  /* AREA CARD FORM (bawah) */
  .right-side{
    width: 100%;
    margin: 0;
    padding: 20px 25px 20px 25px !important;
    height: auto;
    margin-top: -270px; 
    border-radius: 30px;
  }



  .right-side h2{
    margin: 0;
    font-size: 20px;
    margin-bottom: 22px;
  }



  .form-group{
    width: 100%;
    margin-bottom: 15px;
  }

  .form-group label{
    display:none; /* biar mirip gambar: cukup placeholder */
  }

  .form-group input[type="text"],
  .form-group input[type="password"]{
    padding: 8px 15px;
    border-radius: 30px;
  }

  .checkbox{
    font-size: 10px;
  }

  .login-button{
    margin-top: 6px;
    padding: 10px;
    border-radius: 30px;
    font-size: 12px;
  }
}

/* =========================
   FIX: Mobile input tidak bisa diketik
   (layer ketiban / ke-block)
========================= */
@media (max-width: 768px){

  /* pastikan parent punya konteks stacking */
  .left-side,
  .right-side{
    position: relative !important;
  }

  /* card form harus paling atas */
  .right-side{
    z-index: 50 !important;
    pointer-events: auto !important;
  }

  /* hero/left-side di bawahnya */
  .left-side{
    z-index: 1 !important;
  }

  /* seringnya ilustrasi/asset yang “nahan klik” */
  .left-side img,
  .left-side .illustration,
  .left-side .logo-default{
    pointer-events: none !important;
  }

  /* optional: kalau masih ketiban, kasih ruang pakai transform */
  .right-side{
    margin-top: 0 !important;              /* reset negatif */
    transform: translateY(-270px);          /* ganti negative margin */
  }
}

  </style>




</head>
<!-- END HEAD -->

<body>
  <!-- BEGIN LOGO -->
  <div class="left-side">
    <div>
 <a href="/">
      <img width="100" class="logo-default" src="<?php echo _ASSET_LOGO_FRONT; ?>" alt="<?php echo _COMPANY_NAME; ?>" /> </a>
    <h2>Hello! Welcome to</h2>
    <h2 style="margin-top: 0rem;">HR System</h2>
    </div>
   
    <img width="500" src="<?php echo _ASSET_ILUSTRASI_LOGIN; ?>" alt="Illustration" class="illustration">
  </div>
  <!-- END LOGO -->

  <!-- BEGIN LOGIN -->

  <div class="right-side">
    <h2>Sign in</h2>
    <form id="login-form" class="login-form">
      <!-- <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button>
        <span> Enter any username and password. </span>
      </div> -->

      <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <input type="text" autocomplete="off" placeholder="Username" name="username" required>
      </div>

      <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input type="password" autocomplete="off" placeholder="Password" name="userpasswd" required>
      </div>

      <div class="form-actions">
        <div class="checkbox">
          <label class="checkbox-label">
            <input type="checkbox" name="remember" value="1" />Keep me logged in
          </label>
        </div>
        <button type="submit" class="login-button" id="btnLogin">Login</button>

      </div>

    </form>
  </div>


  <!-- <div class="content">

    <form id="login-form" class="login-form">
      <h3 class="form-title font-green">Login</h3>
      <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button>
        <span> Enter any username and password. </span>
      </div>
      <div class="form-group">

        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"
          placeholder="Username" name="username" />
      </div>
      <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
          placeholder="Password" name="userpasswd" />
      </div>
      <div class="form-actions">
        <button type="submit" class="btn green uppercase" id="btnLogin">Login</button>
        <label class="rememberme check">
          <input type="checkbox" name="remember" value="1" />Remember </label>
      </div>

    </form>

  </div> -->





  <!-- <div class="copyright"><?php echo _COPYRIGHT; ?></div> -->
  <!--[if lt IE 9]>
<script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/respond.min.js"></script>
<script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/excanvas.min.js"></script> 
<![endif]-->
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap/js/bootstrap.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/js.cookie.min.js" type="text/javascript"></script>
  <script
    src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-slimscroll/jquery.slimscroll.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery.blockui.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/uniform/jquery.uniform.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-switch/js/bootstrap-switch.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootbox/bootbox.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/jquery.validate.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/additional-methods.min.js"
    type="text/javascript"></script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/js/select2.full.min.js"
    type="text/javascript"></script>
  <script type="text/javascript">
    function aPath() {
      return '<?= _ASSET_METRONIC_TEMPLATE ?>';
    }
  </script>
  <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>scripts/app.min.js" type="text/javascript"></script>
  <script>
    var Login = function () {
      var e = function () {
        $(".login-form").validate({
          errorElement: "span",
          errorClass: "help-block",
          focusInvalid: false,
          rules: {
            username: { required: true },
            userpasswd: { required: true },
            remember: { required: false }
          },
          messages: {
            username: { required: "Username is required." },
            userpasswd: { required: "Password is required." }
          },
          invalidHandler: function (e, r) {
            $('.alert-danger', $('.login-form')).html('<button class="close" data-close="alert"></button><span>Enter your username and password.</span>');
            $(".alert-danger", $(".login-form")).show()
          },
          highlight: function (e) {
            $(e).closest(".form-group").addClass("has-error")
          },
          success: function (e) {
            e.closest(".form-group").removeClass("has-error"), e.remove()
          },
          errorPlacement: function (e, r) {
            e.insertAfter(r.closest(".input-icon"))
          },
          submitHandler: function (e) {
            //e.submit()
            $('.alert-danger', $('.login-form')).hide();
            $.ajax({
              url: 'login/auth',
              data: $('#login-form').serialize(),
              type: "POST",
              success: function (data) {
                if (data == 'Welcome') {
                  /*window.location.href = '<?= base_url('#') ?>';*/
                  window.location.href = '<?= base_url('profile/profile_menu') ?>';
                } else {
                  $('.alert-danger', $('.login-form')).html(data);
                  $('.alert-danger', $('.login-form')).show();
                  $('.login-form [name="username"]').val('');
                  $('.login-form [name="userpasswd"]').val('');
                }
              }
            })

            return false;
          }
        }),
          $(".login-form input").keypress(function (e) {
            return 13 == e.which ? ($(".login-form").validate().form() && $(".login-form").submit(), !1) : void 0
          })
      };
      return {
        init: function () {
          e()
        }
      }
    }();
    jQuery(document).ready(function () {
      Login.init()
    });
  </script>
</body>

</html>