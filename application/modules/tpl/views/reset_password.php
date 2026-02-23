<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <title>Reset Password - <?php echo _TITLE; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="favicon.ico" />
    <style>
        /* ===== RESET & BASE ===== */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(160deg, #d6e6f5 0%, #e8f0f8 30%, #f0f6fb 60%, #f8fbfe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ===== MAIN CONTAINER ===== */
        .reset-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 620px;
            margin: 0 auto;
            position: relative;
            align-items: center;
        }

        /* ===== LEFT PANEL ===== */
        .left-panel {
            flex: 1;
            padding: 40px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .logo-wrapper {
            margin-bottom: 5px;
            
            
        }

        .logo-wrapper img {
            width: 110px;
            height: 110px;
            display: block;
        }


        .welcome-text {
            margin-bottom: 30px;
        }

        .welcome-text h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a2a3a;
            line-height: 1.3;
        }

        .welcome-text h1 span {
            color: #c8982e;
        }

        .welcome-text p {
            margin-top: 10px;
            font-size: 0.95rem;
            color: #5a7a9a;
            line-height: 1.6;
        }

        .illustration-wrapper {
            position: relative;
            width: 100%;
            max-width: 420px;
            margin-top: 10px;
        }

        .illustration-wrapper svg {
            width: 100%;
            height: auto;
        }

        /* ===== RIGHT PANEL (CARD) ===== */
        .right-panel {
            width: 440px;
            min-width: 400px;
            background: #ffffff;
            border-radius: 24px;
            padding: 50px 45px;
            box-shadow: 0 20px 60px rgba(61, 90, 128, 0.10), 0 4px 20px rgba(61, 90, 128, 0.06);
            position: relative;
            z-index: 2;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .card-header-section .icon-circle {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #e8f0f8, #d6e6f5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .card-header-section .icon-circle svg {
            width: 28px;
            height: 28px;
            color: #2c3e6b;
        }

        .card-header-section h2 {
            font-size: 1.65rem;
            font-weight: 700;
            color: #1a2a3a;
            margin-bottom: 6px;
        }

        .card-header-section p {
            font-size: 0.85rem;
            color: #8a9ab5;
        }

        /* ===== FORM STYLES ===== */
        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-group-custom label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #2c3e6b;
            margin-bottom: 7px;
            letter-spacing: 0.02em;
        }

        .form-group-custom .input-wrapper {
            position: relative;
        }

        .form-group-custom .input-wrapper .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8a9ab5;
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        .form-group-custom input {
            width: 100%;
            padding: 13px 16px 13px 46px;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
            border: 2px solid #e8edf5;
            border-radius: 14px;
            background: #f5f8fc;
            color: #1a2a3a;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group-custom input:focus {
            border-color: #3d5a80;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(61, 90, 128, 0.08);
        }

        .form-group-custom input[readonly] {
            background: #eef2f7;
            color: #6a7a8a;
            cursor: not-allowed;
        }

        .form-group-custom input::placeholder {
            color: #b0bec5;
        }

        .form-group-custom .help-block {
            display: block;
            font-size: 0.78rem;
            color: #e74c3c;
            margin-top: 5px;
            padding-left: 4px;
        }

        .form-group-custom.has-error input {
            border-color: #e74c3c;
            background: #fef7f7;
        }

        .form-group-custom.has-error input:focus {
            box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.08);
        }

        /* Toggle password visibility */
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #8a9ab5;
            transition: color 0.2s;
            background: none;
            border: none;
            outline: none;
            padding: 0;
        }

        .toggle-password:hover {
            color: #3d5a80;
        }

        /* ===== BUTTON ===== */
        .btn-reset {
            width: 100%;
            padding: 14px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            background: linear-gradient(135deg, #2c3e6b, #1a2744);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.03em;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-reset::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.5s ease;
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #354a7a, #223358);
            box-shadow: 0 8px 25px rgba(44, 62, 107, 0.35);
            transform: translateY(-1px);
        }

        .btn-reset:hover::after {
            left: 100%;
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        /* ===== BACK TO HOME ===== */
        .back-home {
            text-align: center;
            margin-top: 28px;
        }

        .back-home a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #5a7a9a;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-home a:hover {
            color: #2c3e6b;
        }

        .back-home a svg {
            width: 16px;
            height: 16px;
        }

        /* ===== MESSAGE BOX (when form=false) ===== */
        .message-box {
            text-align: center;
            padding: 20px 0;
        }

        .message-box p {
            color: #5a7a9a;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ===== PASSWORD STRENGTH ===== */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            background: #e8edf5;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength .strength-bar {
            height: 100%;
            border-radius: 2px;
            width: 0%;
            transition: width 0.3s ease, background 0.3s ease;
        }

        .strength-text {
            font-size: 0.72rem;
            margin-top: 4px;
            color: #8a9ab5;
        }

        /* ===== BOOTBOX CUSTOM ===== */
        .modal-content {
            border-radius: 16px !important;
            border: none !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        }

        .modal-body {
            padding: 30px !important;
            font-family: 'Poppins', sans-serif !important;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .reset-container {
                flex-direction: column;
                padding: 30px 20px;
            }

            .left-panel {
                padding: 20px;
                align-items: center;
                text-align: center;
            }

            .welcome-text h1 {
                font-size: 1.6rem;
            }

            .illustration-wrapper {
                max-width: 280px;
                margin: 0 auto;
            }

            .right-panel {
                width: 100%;
                min-width: unset;
                max-width: 440px;
                padding: 40px 30px;
            }
        }

        @media (max-width: 480px) {
            .right-panel {
                padding: 30px 24px;
                border-radius: 18px;
            }

            .card-header-section h2 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div class="logo-wrapper">
                <a href="/">
                    <img src="<?php echo _ASSET_LOGO_FRONT; ?>" alt="<?php echo _COMPANY_NAME; ?>" />
                </a>
            </div>
            <div class="welcome-text">
                <h1>Reset Your <span>Password</span></h1>
                <p>Enter your new password below to regain access to your account securely.</p>
            </div>
            <div class="illustration-wrapper">
                <!-- SVG Illustration matching the HR System theme -->
                <svg viewBox="0 0 500 380" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Ground shadow -->
                    <ellipse cx="250" cy="360" rx="200" ry="18" fill="#c8d8eb" opacity="0.35" />

                    <!-- Big shield / lock background -->
                    <rect x="150" y="60" width="200" height="230" rx="24" fill="#e8f0f8" stroke="#c8d8eb" stroke-width="2" />
                    <rect x="170" y="110" width="160" height="120" rx="14" fill="#fff" stroke="#d6e6f5" stroke-width="1.5" />

                    <!-- Lock icon -->
                    <rect x="225" y="140" width="50" height="45" rx="8" fill="#2c3e6b" />
                    <path d="M235 140V125a15 15 0 0130 0v15" stroke="#2c3e6b" stroke-width="5" fill="none" stroke-linecap="round" />
                    <circle cx="250" cy="158" r="6" fill="#fff" />
                    <rect x="247" y="162" width="6" height="10" rx="3" fill="#fff" />

                    <!-- Key icon decorative -->
                    <circle cx="200" cy="90" r="14" fill="#c8982e" opacity="0.2" />
                    <circle cx="200" cy="90" r="8" fill="#c8982e" opacity="0.4" />

                    <!-- Checkmark badge -->
                    <circle cx="330" cy="100" r="20" fill="#27ae60" opacity="0.15" />
                    <circle cx="330" cy="100" r="13" fill="#27ae60" opacity="0.3" />
                    <path d="M323 100l5 5 9-10" stroke="#27ae60" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />

                    <!-- Person left -->
                    <circle cx="110" cy="230" r="20" fill="#3d5a80" />
                    <rect x="90" y="255" width="40" height="60" rx="10" fill="#3d5a80" />
                    <rect x="82" y="262" width="22" height="8" rx="4" fill="#3d5a80" transform="rotate(-20 82 262)" />

                    <!-- Tablet in hand -->
                    <rect x="68" y="268" width="22" height="30" rx="4" fill="#e8f0f8" stroke="#3d5a80" stroke-width="1.5" />
                    <rect x="72" y="273" width="14" height="3" rx="1.5" fill="#c8d8eb" />
                    <rect x="72" y="279" width="10" height="3" rx="1.5" fill="#c8d8eb" />

                    <!-- Person right -->
                    <circle cx="390" cy="225" r="22" fill="#c8982e" />
                    <rect x="368" y="252" width="44" height="65" rx="10" fill="#e8b93c" />

                    <!-- Floating elements -->
                    <circle cx="80" cy="120" r="5" fill="#3d5a80" opacity="0.15" />
                    <circle cx="420" cy="160" r="7" fill="#c8982e" opacity="0.2" />
                    <circle cx="370" cy="70" r="4" fill="#3d5a80" opacity="0.1" />

                    <!-- Stars / sparkles -->
                    <path d="M430 200l3-8 3 8-8-3 8-3z" fill="#c8982e" opacity="0.4" />
                    <path d="M90 170l2-6 2 6-6-2 6-2z" fill="#3d5a80" opacity="0.3" />

                    <!-- Plant pot -->
                    <rect x="410" y="310" width="30" height="28" rx="4" fill="#e8dcc8" />
                    <ellipse cx="425" cy="310" rx="18" ry="5" fill="#d4c4a8" />
                    <path d="M420 310c-3-25-10-40-5-60" stroke="#27ae60" stroke-width="3" fill="none" stroke-linecap="round" />
                    <path d="M430 310c2-20 8-35 3-55" stroke="#2ecc71" stroke-width="3" fill="none" stroke-linecap="round" />
                    <ellipse cx="414" cy="252" rx="10" ry="7" fill="#27ae60" opacity="0.6" transform="rotate(-15 414 252)" />
                    <ellipse cx="434" cy="258" rx="9" ry="6" fill="#2ecc71" opacity="0.6" transform="rotate(10 434 258)" />
                </svg>
            </div>
        </div>

        <!-- RIGHT PANEL (FORM CARD) -->
        <div class="right-panel">
            <div class="card-header-section">
                <div class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h2>Reset Password</h2>
                <p>Create a strong new password for your account</p>
            </div>

            <form id="reset-form" class="reset-form" autocomplete="off">
                <?php if ($form): ?>

                    <!-- Username (readonly) -->
                    <div class="form-group-custom">
                        <label>Username</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <input type="text" name="username" value="<?= $username; ?>" readonly />
                        </div>
                        <input type="hidden" name="email" value="<?= $email; ?>" />
                        <input type="hidden" name="auth" value="<?= $auth; ?>" />
                    </div>

                    <!-- New Password -->
                    <div class="form-group-custom">
                        <label>New Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input type="password" id="reset_password" name="password" placeholder="Enter new password" />
                            <button type="button" class="toggle-password" onclick="togglePass('reset_password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="strength-text" id="strengthText"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group-custom">
                        <label>Confirm Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <input type="password" id="reset_rpassword" name="rpassword" placeholder="Re-type your password" />
                            <button type="button" class="toggle-password" onclick="togglePass('reset_rpassword', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-reset">Reset Password</button>

                <?php else: ?>
                    <div class="message-box">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <!-- Back to Home -->
                <div class="back-home">
                    <a href="/_hrm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function aPath() {
            return '<?= _ASSET_METRONIC_TEMPLATE ?>';
        }
    </script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>scripts/app.min.js" type="text/javascript"></script>

    <script>
        // Toggle password visibility
        function togglePass(inputId, btn) {
            var input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
            }
        }

        // Password strength indicator
        document.getElementById('reset_password') && document.getElementById('reset_password').addEventListener('input', function() {
            var val = this.value;
            var bar = document.getElementById('strengthBar');
            var text = document.getElementById('strengthText');
            var score = 0;

            if (val.length >= 5) score++;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            var colors = ['#e74c3c', '#e67e22', '#f1c40f', '#27ae60', '#2ecc71'];
            var labels = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            var widths = ['20%', '40%', '60%', '80%', '100%'];

            if (val.length === 0) {
                bar.style.width = '0%';
                text.textContent = '';
            } else {
                var idx = Math.min(score, 5) - 1;
                if (idx < 0) idx = 0;
                bar.style.width = widths[idx];
                bar.style.background = colors[idx];
                text.textContent = labels[idx];
                text.style.color = colors[idx];
            }
        });
    </script>

    <?php if ($form): ?>
        <script>
            var Reset = function() {
                var initValidation = function() {
                    $(".reset-form").validate({
                        errorElement: "span",
                        errorClass: "help-block",
                        focusInvalid: false,
                        ignore: "",
                        rules: {
                            password: {
                                required: true,
                                minlength: 5
                            },
                            rpassword: {
                                required: true,
                                equalTo: "#reset_password"
                            }
                        },
                        messages: {
                            password: {
                                required: "Please enter a new password",
                                minlength: "Password must be at least 5 characters"
                            },
                            rpassword: {
                                required: "Please confirm your password",
                                equalTo: "Passwords do not match"
                            }
                        },
                        invalidHandler: function(e, r) {},
                        highlight: function(e) {
                            $(e).closest(".form-group-custom").addClass("has-error");
                        },
                        unhighlight: function(e) {
                            $(e).closest(".form-group-custom").removeClass("has-error");
                        },
                        success: function(e) {
                            e.closest(".form-group-custom").removeClass("has-error");
                            e.remove();
                        },
                        errorPlacement: function(e, r) {
                            r.closest(".input-wrapper").after(e);
                        },
                        submitHandler: function(e) {
                            var btn = $(e).find('.btn-reset');
                            btn.prop('disabled', true).text('Processing...');

                            $.ajax({
                                url: '/login/reset_password',
                                type: 'POST',
                                data: $('#reset-form').serialize(),
                                dataType: 'json',
                                success: function(data) {
                                    if (data === true) {
                                        var dialog = bootbox.dialog({
                                            message: '<div style="text-align:center;padding:20px 10px;"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg><p style="margin-top:15px;font-size:1rem;color:#1a2a3a;font-weight:600;">Password Reset Successful!</p><p style="color:#8a9ab5;font-size:0.85rem;">Redirecting to login page...</p></div>'
                                        });
                                    } else {
                                        var dialog = bootbox.dialog({
                                            message: '<div style="text-align:center;padding:20px 10px;"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg><p style="margin-top:15px;font-size:1rem;color:#1a2a3a;font-weight:600;">Error Processing</p><p style="color:#8a9ab5;font-size:0.85rem;">Please contact us about this issue.</p></div>'
                                        });
                                        btn.prop('disabled', false).text('Reset Password');
                                    }

                                    setTimeout(function() {
                                        dialog.modal('hide');
                                        window.location.href = '<?= base_url('login') ?>';
                                    }, 3500);
                                },
                                error: function() {
                                    btn.prop('disabled', false).text('Reset Password');
                                    bootbox.alert('<div style="text-align:center;">Connection error. Please try again.</div>');
                                }
                            });

                            return false;
                        }
                    });

                    $(".reset-form input").keypress(function(e) {
                        if (13 == e.which) {
                            $(".reset-form").validate().form() && $(".reset-form").submit();
                            return false;
                        }
                    });
                };

                return {
                    init: function() {
                        initValidation();
                    }
                };
            }();

            jQuery(document).ready(function() {
                Reset.init();
            });
        </script>
    <?php endif; ?>
</body>

</html>