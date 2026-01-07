<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="<?php echo _ASSET_LOGO_TSP; ?>" type="image/PNG" />
    <title><?php echo _TITLE; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
        type="text/css" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/datatables.min.css" rel="stylesheet"
        type="text/css" />
    <link
        href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/colreorder/colReorder.dataTables.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/fixedheader/fixedHeader.dataTables.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/responsive/responsive.dataTables.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo _ASSET_PLUGINS; ?>chosen/chosen.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo _ASSET_PLUGINS; ?>bootstrap-multiselect/css/bootstrap-multiselect.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo _ASSET_PLUGINS; ?>eonasdan-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo _ASSET_PLUGINS; ?>tablesaw-stackonly/tablesaw.stackonly.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>/plugins/select2/css/select2.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/css/select2-bootstrap.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>css/components.min.css" rel="stylesheet"
        id="style_components" type="text/css" />
    <link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo _ASSET_LAYOUTS_METRONIC_TEMPLATE; ?>layout/css/layout.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo _ASSET_LAYOUTS_METRONIC_TEMPLATE; ?>layout/css/themes/darkblue.min.css" rel="stylesheet"
        type="text/css" id="style_color" />
    <link href="<?php echo _ASSET_LAYOUTS_METRONIC_TEMPLATE; ?>layout/css/custom.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?= base_url('public/css/custom.css') ?>" rel="stylesheet" type="text/css" />
    <?php
    // inline css related to page. can multiple
    if (isset($css)) {
        if (is_array($css)) {
            foreach ($css as $script) {
                $this->load->view($script);
            }
        } else {
            $this->load->view($css);
        }
    }
    ?>

    <link rel="shortcut icon" href="favicon.ico" />


    <style type="text/css">
        /* Ubah warna background sidebar */
        /* .page-sidebar-wrapper {
            background-color: #343851 !important;
            
        } */

        * {
            font-family: 'Poppins', sans-serif;
        }

        /* Warna teks menu sidebar */
        .page-sidebar .page-sidebar-menu>li>a {
            color: #ffffff !important;
            /* Warna teks putih */
            font-weight: 500;
            border: none !important;
            margin-top: 13px;
            border-radius: 10px !important;
            margin-left: 6px !important;
            margin-right: 6px !important;
            font-size: 12px;
            margin-bottom: 15px !important;
        }

        .page-sidebar-closed .page-sidebar .page-sidebar-menu>li>a {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }



        /* Warna teks menu saat dihover */
        .page-sidebar .page-sidebar-menu>li>a:hover {
            background-color: #FED24B !important;
            /* Hover warna lebih terang */
            color: #343851 !important;
        }

        .page-sidebar .page-sidebar-menu>li>a:hover>i {
            /*color: #343851 !important;*/
            color: #343851 !important;
        }

        .page-sidebar-closed .page-sidebar .page-sidebar-menu>li>a>i {
            margin-left: 3px !important;
        }


        /* Warna menu aktif */
        .page-sidebar .page-sidebar-menu>li.active>a,
        .page-sidebar .page-sidebar-menu>li.start>a {

            background-color: #FED24B !important;
            /* Warna menu aktif (biru tua) */
            color: #343851 !important;
            font-size: 12px !important;
        }

        /* Icon menu aktif jadi hitam */
        .page-sidebar .page-sidebar-menu>li.active>a>i,
        .page-sidebar .page-sidebar-menu>li.start>a>i {
            color: #343851 !important;
            font-size: 12px !important;
        }

        /* Warna ikon menu sidebar */
        .page-sidebar .page-sidebar-menu>li>a>i {
            color: #ffffff !important;
            border-bottom: none !important;
            border: none !important;
            font-size: 12px !important;

        }



        /* Warna background menu di dalam sidebar */
        .page-sidebar .page-sidebar-menu {
            background-color: #343851 !important;
            width: 235px !important;
            /*height: 93vh !important;*/
            height: 94vh !important;
            /* Tinggi full layar */
            min-height: 93vh !important;
            position: fixed !important;
            /* Tetap di tempatnya, tidak ikut scroll */
            overflow-y: auto !important;
            z-index: 9999 !important;
            scrollbar-width: none !important;
            transition: width 0.1s ease !important;
        }

        .clearfix {
            background-color: #ffffff !important;
            color: #ffffff !important;
        }

        .dropdown-toggle {
            color: #ffffff !important;
            background-color: #343851 !important;
            border-bottom-left-radius: 5px !important;
            border-bottom-right-radius: 5px !important;
            height: auto !important;
            top: -1px !important;
        }



        @media (max-width: 768px) {

            .page-sidebar .page-sidebar-menu {
                left: auto !important;
                right: 0 !important;
                top: 45px !important;
                border-bottom-left-radius: 20px !important;
                border-bottom-right-radius: 20px !important;
            }

        }

        .page-header.navbar {
            background-color: #343851 !important;
            border: none !important;
            box-shadow: none !important;
            /* Hilangkan bayangan jika ada */
        }

        /* Warna teks dan icon di page header */
        .page-header.navbar .page-top>.navbar-nav>li>a {
            color: #ffffff !important;
            /* Warna teks navbar */
        }

        .page-header.navbar .page-top>.navbar-nav>li>a>i {
            color: #ffffff !important;
            /* Warna icon navbar */
        }

        /* Submenu (dropdown) background normal */
        .page-sidebar .page-sidebar-menu .sub-menu>li>a {
            background-color: transparent !important;
            color: #ffffff !important;
            font-size: 11px !important;
            /* Warna teks submenu normal */
        }

        /* Saat hover di submenu → background kuning, teks hitam */
        .page-sidebar .page-sidebar-menu .sub-menu>li>a:hover {
            background-color: #D9D9D9 !important;
            /* Background kuning */
            color: #000000 !important;
            font-size: 11px !important;
            /* Teks submenu saat hover */
        }

        /* Submenu aktif → background abu-abu #D9D9D9, teks hitam */
        .page-sidebar .page-sidebar-menu .sub-menu>li.active>a {
            background-color: #D9D9D9 !important;
            color: #000000 !important;
            font-size: 11px !important;
        }

        .page-sidebar .page-sidebar-menu li.open>a {
            font-size: 12px !important;

        }

        .page-sidebar .page-sidebar-menu li.open .sub-menu>li>a {
            font-size: 11px !important;

        }

        /*.page-sidebar-wrapper h2 {
          text-align: center;
          margin-bottom: 30px;
          font-size: 24px;
          font-weight: bold;
        }*/

        /*.nav-toggle {
          list-style: none;
        }

        .nav-toggle li {
          margin: 15px 0;
        }

        .nav-toggle a {
          text-decoration: none;
          color: #ecf0f1;
          padding: 10px;
          display: block;
          border-radius: 5px;
          transition: 0.3s;
        }

        .nav-toggle a:hover {
          background-color: #34495e;
        }*/
    </style>


</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
    <div class="page-header navbar navbar-fixed-top">
        <div class="page-header-inner ">
            <div class="page-logo" style="background-color: #FED24B;">
                <a href="<?= base_url() ?>">
                    <img src="<?php echo _ASSET_LOGO_INSIDE; ?>" alt="logo"
                        style="height:45px; margin-top: 0px; margin-left: -8px;" class="logo-default" />
                </a>
                <div class="menu-toggler sidebar-toggler"> </div>
            </div>
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                data-target=".navbar-collapse"> </a>
            <?php $this->load->view(_TEMPLATE_PATH . "navbar"); ?>
        </div>
    </div>
    <div class="clearfix"> </div>
    <div class="page-container">
        <?php $this->load->view(_TEMPLATE_PATH . "sidebar"); ?>
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <?php if (isset($breadcrumb) && $breadcrumb): ?>
                            <?php if (is_array($breadcrumb)): ?>
                                <li>
                                    <i class="fa fa-home"></i>
                                    <a href="<?= base_url() ?>">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <?php foreach ($breadcrumb as $i => $b): ?>
                                    <li>
                                        <span><?= $b ?></span>
                                        <?php if ($i < count($breadcrumb) - 1): ?> <i class="fa fa-circle"></i>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach ?>
                            <?php else: ?>
                                <?= $breadcrumb ?>
                            <?php endif ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if (isset($sview))
                    $this->load->view($sview); ?>
            </div>
        </div>
    </div>

    <!-- <?php if ($this->session->flashdata('msg')): ?>

    <div id="_info_" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-blue bg-font-blue no-padding">
                    <div class="table-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <span class="white">&times;</span>
                        </button>
                        Notifikasi
                    </div>
                </div>

                <div class="modal-body" style="height:100px;">
                    <div class="alert alert-<?= $this->session->flashdata('stats') == '0' ? 'error' : 'info' ?>">
                        <p class="err-form" style="letter-spacing: 1px;"><?php echo strtoupper($this->session->flashdata('msg')); ?></p>
                    </div>
                </div>

                <div class="modal-footer no-margin-top">
                    <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?> -->


    <!--[if lt IE 9]>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/respond.min.js"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/excanvas.min.js"></script> 
    <![endif]-->
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap/js/bootstrap.min.js"
        type="text/javascript"></script>
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
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/ckeditor/ckeditor.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootbox/bootbox.min.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>scripts/datatable.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/datatables.min.js"
        type="text/javascript"></script>
    <script
        src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
        type="text/javascript"></script>
    <script
        src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/colreorder/dataTables.colReorder.min.js"
        type="text/javascript"></script>
    <script
        src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/fixedheader/dataTables.fixedHeader.min.js"
        type="text/javascript"></script>
    <script
        src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/datatables/plugins/responsive/dataTables.responsive.min.js"
        type="text/javascript"></script>
    <script
        src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
    <script
        src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery.scrollTo.min.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_PLUGINS; ?>chosen/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_PLUGINS; ?>bootstrap-multiselect/js/bootstrap-multiselect.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_PLUGINS; ?>eonasdan-bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_PLUGINS; ?>jquery-number/jquery.number.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_PLUGINS; ?>tablesaw-stackonly/tablesaw.stackonly.jquery.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/select2/js/select2.full.min.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/jquery.validate.min.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/jquery-validation/js/additional-methods.min.js"
        type="text/javascript"></script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/bootstrap-toastr/toastr.min.js"
        type="text/javascript"></script>
    <script type="text/javascript">
        function aPath() {
            var path = '<?php echo _ASSET_METRONIC_TEMPLATE; ?>';

            return path;
        }
    </script>
    <script src="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>scripts/app.min.js" type="text/javascript"></script>
    <script src="<?php echo _ASSET_PAGES_METRONIC_TEMPLATE; ?>scripts/table-datatables-fixedheader.min.js"
        type="text/javascript"></script>

    <?php
    // inline javascript related to page. can multiple
    if (isset($js)) {
        if (is_array($js)) {
            foreach ($js as $script) {
                $this->load->view($script);
            }
        } else {
            $this->load->view($js);
        }
    }
    ?>

    <script src="<?php echo _ASSET_LAYOUTS_METRONIC_TEMPLATE; ?>layout/scripts/layout.min.js"
        type="text/javascript"></script>

    <?php if ($this->session->flashdata('error') == true): ?>
        <script type="text/javascript">
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 2000);
        </script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('msg') == true): ?>
        <script type="text/javascript">
            $(window).load(function () {
                $('#_info_').modal('show');
            });
            setTimeout(function () {
                $('#_info_').modal('hide');
            }, 2000);
        </script>
    <?php endif; ?>
</body>






</html>