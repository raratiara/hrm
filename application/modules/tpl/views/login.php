<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
  <meta charset="utf-8" />
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

    body {
      margin: 0;
      display: flex;
      height: 100vh;
    }

    .left-side {
      background-color: #343851;
      color: white;
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
      border-radius: 50%;
    }

    .left-side h2 {
      font-size: 24px;
      font-weight: 600;
      text-align: center;
      color: white;
    }

    .left-side .illustration {
      margin-top: 2rem;
      max-width: 430px;
      width: 100%;
    }

    .right-side {
      background-color: #F5C020;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .right-side h2 {
      font-size: 28px;
      font-weight: 400;
      color: #343851;
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1rem;
      min-width: 320px;
    }

    .checkbox {
      margin-top: 1.5rem;
      font-size: 12px;
      color: #343851;
      margin-left: -20px;
    }

    .form-group label {
      font-size: 12px;
      margin-bottom: 0.5rem;
      display: block;
      color: #343851;
    }

    .form-group input[type="text"],
    .form-group input[type="password"] {
      width: 100%;
      padding: 0.7rem;
      border-radius: 30px;
      border: none;
      outline: none;
    }



    .form-group .checkbox-label {
      font-size: 12px;
      font-weight: normal;
      color: #343851;
    }

    .login-button {
      width: 100%;
      padding: 0.8rem;
      background-color: #343851;
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


    /*.font-sign {
                color: #32c5d2 !important; 
            }*/

    /*.login-box {
              background: #fff;
              padding: 40px;
              width: 300px;
              border-radius: 15px;
              box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
              text-align: center;
            }

            .login-box h2 {
              margin-bottom: 30px;
              color: #333;
            }

            .input-box {
              position: relative;
              margin-bottom: 30px;
            }

            .input-box input {
              width: 100%;
              padding: 10px 10px;
              font-size: 16px;
              color: #333;
              border: none;
              border-bottom: 2px solid #aaa;
              background: transparent;
              outline: none;
            }

            .input-box label {
              position: absolute;
              left: 10px;
              top: 10px;
              color: #aaa;
              pointer-events: none;
              transition: 0.3s ease;
            }

            .input-box input:focus ~ label,
            .input-box input:valid ~ label {
              top: -10px;
              font-size: 12px;
              color: #6e8efb;
            }

            .login-btn {
              width: 100%;
              padding: 10px;
              background: #6e8efb;
              border: none;
              color: #fff;
              border-radius: 25px;
              font-size: 16px;
              cursor: pointer;
              transition: 0.3s;
            }

            .login-btn:hover {
              background: #5b78e0;
            }*/
  </style>

</head>
<!-- END HEAD -->

<body>
  <!-- BEGIN LOGO -->
  <div class="left-side">
    <a href="/">
      <img width="100" src="<?php echo _ASSET_LOGO_FRONT; ?>" alt="<?php echo _COMPANY_NAME; ?>" /> </a>
    <h2>Hello! Welcome to</h2>
    <h2 style="margin-top: 0rem;">HR System</h2>
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
        <button type="submit" class="login-button" id="btnLogin">Login</button>
        <div class="checkbox">
          <label class="checkbox-label">
            <input type="checkbox" name="remember" value="1" />Keep me logged in
          </label>
        </div>

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
                  window.location.href = '<?= base_url('dashboard/dashboard_menu') ?>';
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