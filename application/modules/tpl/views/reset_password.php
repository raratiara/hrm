<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>Reset Password Page - <?php echo _TITLE; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo _ASSET_PAGES_METRONIC_TEMPLATE; ?>css/login.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="/">
                <img width="109" src="<?php echo _ASSET_LOGO_FRONT; ?>" alt="<?php echo _COMPANY_NAME; ?>" /> </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN RESET PASSWORD FORM -->
            <form id="reset-form" class="reset-form">
                <h3 class="form-title font-green">Reset Password</h3>
				<?php
				if($form){
				?>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" name="username" value="<?=$username;?>" readonly />
                    <input type="hidden" name="email" value="<?=$email;?>" readonly />
                    <input type="hidden" name="auth" value="<?=$auth;?>" readonly />
					</div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="reset_password" placeholder="Password" name="password" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>
                <div class="form-actions">
                    <button type="submit" class="btn green uppercase">Reset Now</button>
                </div>
				<?php
				} else {
					echo $message;
				}
				?>
                <div class="create-account">
                    <p>
                        <a href="/" class="btn btn-info btn-lg uppercase"><i class="icon-home"></i></a>
                    </p>
                </div>
            </form>
            <!-- END RESET PASSWORD FORM -->
        </div>
        <div class="copyright"><?php echo _COPYRIGHT; ?></div>
        <!--[if lt IE 9]>
<script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/respond.min.js"></script>
<script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/excanvas.min.js"></script> 
<![endif]-->
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
		<script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			function aPath() {
				return '<?= _ASSET_METRONIC_TEMPLATE ?>';
			}
		</script>
        <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>scripts/app.min.js" type="text/javascript"></script>
<?php
if($form){
?>
		<script>
		var Reset = function() {
			var e = function() {
				$(".reset-form").validate({
					errorElement: "span",
					errorClass: "help-block",
					focusInvalid: !1,
					ignore: "",
					rules: {
						password: { required: true, minlength: 5 },
						rpassword: { equalTo: "#reset_password" }
					},
					invalidHandler: function(e, r) {},
					highlight: function(e) {
						$(e).closest(".form-group").addClass("has-error")
					},
					success: function(e) {
						e.closest(".form-group").removeClass("has-error"), e.remove()
					},
					errorPlacement: function(e, r) {
						r.closest(".input-icon").size() ? e.insertAfter(r.closest(".input-icon")) : e.insertAfter(r)
					},
					submitHandler: function(e) {
						//e.submit()
                        $.ajax({
                            url: '/login/reset_password',
                            data: $('#reset-form').serialize(),
                            type: "POST",
                            success: function(data) {
                                if (data == 'true') {
									var dialog = bootbox.dialog({
										message: '<p class="text-center mb-0">Reset Password Success. You may now log in and begin using it.</p>'
									});
                                } else {
									var dialog = bootbox.dialog({
										message: '<p class="text-center mb-0"><i class="fa fa-2x fa-spin fa-spinner"></i><br/><br/>Error Processing<br/>Please contact us about this issue.</p>'
									});
                                }
								setTimeout(function(){
									dialog.modal('hide');
									window.location.href = '<?= base_url('login') ?>';
								}, 3500);
                            }
                        })

                        return false;
					}
				}), 
				$(".reset-form input").keypress(function(e) {
					return 13 == e.which ? ($(".reset-form").validate().form() && $(".reset-form").submit(), !1) : void 0
				})
			};
			return {
				init: function() {
				  e()
				}
			}
		}();
		jQuery(document).ready(function() {
			Reset.init()
		});
		</script>
<?php
}
?>
    </body>
</html>