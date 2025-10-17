                
<style type="text/css">
    .dropdown-notification {
        margin-right: 10px;
    }
    .dropdown-notification .fa-bell {
        font-size: 18px;
    }
    .dropdown-notification .badge {
        position: absolute;
        top: 5px;
        right: 10px;
    }

</style>


                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- END NOTIFICATION DROPDOWN -->
                        <!-- BEGIN INBOX DROPDOWN -->
                        <!-- END INBOX DROPDOWN -->
                        <!-- BEGIN TODO DROPDOWN -->
                        <!-- END TODO DROPDOWN -->
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- <li class="dropdown dropdown-notification">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-bell"></i>
                                <span class="badge badge-danger" id="ttl_pendingan_approval"></span> 
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <span class="bold">3 Pending</span> Approval
                                    <a href="#">view all</a>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                        <li>
                                            <a href="#">
                                                <span class="time">Just now</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-success">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    New user registered.
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="time">3 mins</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span>
                                                    Server #12 overloaded.
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li> -->




                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <!--img alt="" class="img-circle" src="<?php echo _ASSET_LAYOUTS_METRONIC_TEMPLATE;?>layout/img/avatar3_small.jpg" /-->
                                <span class="username username-hide-on-mobile">Selamat Datang, <?php echo $_SESSION["name"];?>..</span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="<?php echo _URL.'reset_password/';?>">
                                        <i class="icon-pencil"></i> Reset Password </a>
                                </li>
                                <li>
                                    <a href="<?php echo _URL.'login/logout';?>">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                </div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        $(function() {
            getPendinganApproval();
        });
    });


    function getPendinganApproval(){
        path = 'http://localhost/_hrm/profile/profile_menu';

        $.ajax({
            type: "POST",
            url: path + '/getPendinganApproval',
            data: {},
            cache: false,
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                // tampilkan hasil ke tabel
                //$('#approvalLogContent tbody').html(response.html);
                //$('span#ttl_pendingan_approval').html('10');
                $("li.dropdown.dropdown-user").before(response.html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var dialog = bootbox.dialog({
                    title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
                    message: jqXHR.responseText,
                    buttons: {
                        confirm: {
                            label: 'Ok',
                            className: 'btn blue'
                        }
                    }
                });
            }
        });
    
    }

</script>