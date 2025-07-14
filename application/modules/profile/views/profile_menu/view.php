<!-- 

<div class="row">

    <div class="col-md-4 col-sm-12 col-xs-12">
        <span class="emp_photo"></span>
    </div>
    
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">NIK</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="nik"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Name</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="name"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Gender</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="gender"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Date of Birth</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="date_of_birth"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Phone</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="phone"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Email</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="email"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Direct</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="direct"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Address</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="address"></span>
            </div>
        </div>
    </div>



    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Division</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="division"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Department</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="department"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Job Title</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="job_title"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Job Level</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="job_level"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Status</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="status"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Date of Hired</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="date_of_hired"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Shift Type</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="shift_type"></span>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-3 col-sm-12 col-xs-12">
        <span class="ttl_leave"></span>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <span class="ttl_tasklist_open"></span>
        <span class="ttl_tasklist_inprogress"></span>
        <span class="ttl_tasklist_close"></span>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-12">
        <span class="ttl_workhours"></span>
    </div>
</div>


<div class="box box-14">
    <div class="box-title">Monthly Attendance Summary <b><span class="clyear"><span><b></div>
    <div class="box-value">
        <canvas id="monthly_attendance_summ"></canvas>
    </div>
</div> -->



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<div class="dashboard-container">

    <!-- Profile Box -->


    <div class="profile-section">
        <div class="profile-card">
            <img src="<?php echo _ASSET_PROFILE_PICTURE; ?>" alt="Profile Picture" class="profile-image">
            <div class="profile-name">
                <span class="name" style="font-weight: bold;">
            </div>


            <div class="profile-details">

                <div style="color: #888888;">
                    <span class="job_title_department">
                        <?php
                        $job = !empty($job_title) ? $job_title : 'Web Developer';
                        $dept = !empty($department) ? $department : 'App Development';
                        echo $job . ' – ' . $dept;
                        ?>
                    </span>
                </div>



                <div style="margin-top: 5px;">
                    IT Division<span class="division">
                </div>


                <div style="margin-top: 5px;">
                    <i class="fa fa-envelope" style="margin-right: 5px;"></i>
                    <span class="email">
                </div>

                <div style="margin-top: 5px;">
                    <i class="fa fa-phone" style="margin-right: 5px;"></i>
                    <span class="phone">
                </div>
            </div>

        </div>

        <div class="profile-info">
            <div class="info-grid">
                <!-- Kolom 1 -->
                <div class="column">

                    <div><strong>NIK</strong><br><span class="nik"></div>
                    <div><strong>Date of Birth</strong><br><span class="date_of_birth"></div>
                    <div><strong>Gender</strong><br><span class="gender"></div>
                    <div><strong>Job Level</strong><br><span class="job_level"></div>
                </div>

                <!-- Kolom 2 -->
                <div class="column">
                    <div><strong>Direct</strong><br><span class="direct"></div>
                    <div><strong>Status</strong><br><span class="status"></div>
                    <div><strong>Date of Hired</strong><br><span class="date_of_hired"></div>
                    <div><strong>Shift Type</strong><br><span class="shift_type"></div>
                </div>

                <!-- Kolom 3 -->
                <div class="column">
                    <div><strong>Address</strong><br><span class="address"></div>
                </div>
            </div>
        </div>
    </div>











    <!-- <div class="profile-box">
        <span class="emp_photo">
        </span>
        <div class="profile-details">
            <div><strong>NIK:</strong> <span class="nik"></div>
            <div><strong>Division:</strong> <span class="division"></div>
            <div><strong>Name:</strong> <span class="name"></div>
            <div><strong>Department:</strong> <span class="department"></div>
            <div><strong>Gender:</strong> <span class="gender"></div>
            <div><strong>Job Title:</strong> <span class="job_title"></div>
            <div><strong>Date of Birth:</strong> <span class="date_of_birth"></div>
            <div><strong>Job Level:</strong> <span class="job_level"></div>
            <div><strong>Phone:</strong> <span class="phone"></div>
            <div><strong>Status:</strong> <span class="status"></div>
     
            <div><strong>Email:</strong> <span class="email"></div>
            <div><strong>Date of Hired:</strong> <span class="date_of_hired"></div>
            <div><strong>Direct:</strong> <span class="direct"></div>
            <div><strong>Employee Type:</strong> <span class="shift_type"></div>
            <div><strong>Address:</strong> <span class="address"></div>
        </div>
        <div class="app-download">
            <span>Apk Mobile Attendance:</span>

            <a href="javascript:void(0);" onclick="downloadFile('hrm.apk')" class="download-icon android">
                <img src="https://cdn-icons-png.flaticon.com/512/888/888857.png" alt="Android" />
                for Android
            </a>
            <a href="#" class="download-icon ios">
                <img src="https://cdn-icons-png.flaticon.com/512/888/888841.png" alt="iOS" />
                for iOS
            </a>
        </div>
    </div> -->


    <!-- Summary Box -->

    <div class="summary-section">
        <div class="summary-box">
            <div class="summary-item highlight">
                <div>
                    <h4>Total Leave</h4>
                    <div class="total">4</div>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i><i class="fas fa-arrow-down" style="font-size: 15px;"></i>
                </div>

            </div>

            <div class="summary-item yellow">

                <div class="title">Tasklist</div>
                <div class="tasklist-line">
                    <div><strong>2</strong> <br>Open</div>
                    <div><strong>1</strong> <br>In Progress</div>
                    <div><strong>5</strong> <br>Done</div>
                </div>

            </div>

            <div class="summary-item time">
                <div>
                    <h4>Total Work Hours</h4>
                    <div class="total">165</div>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="birthday-box">
            <h4 class="birthday-title">Today’s Birthdays</h4>
            <div class="birthday-content">
                <img id="birthday-image" src="<?php echo _ASSET_PROFILE_PICTURE; ?>" alt="Profile Picture"
                    class="birthday-image">
                <div class="birthday-info">
                    <div id="birthday-name" class="birthday-name"></div>
                    <div id="birthday-job" class="birthday-job"></div>
                </div>
                <div class="birthday-arrow">
                    <i class="fa fa-caret-up" onclick="showPrevious()"></i>
                    <i class="fa fa-caret-down" onclick="showNext()"></i>
                </div>
            </div>
        </div>

    </div>



    <!-- Chart Placeholder -->
    <div class="chart-section">

        <div class="chart-box">
            <strong>Monthly Attendance Summary</strong>
            <!-- Placeholder Chart -->
            <canvas id="monthly_attendance_summ" style="margin-top: 20px; width: 100%; height: 220px;"></canvas>
        </div>


        <div class="events-box">
            <div class="scroll-area" id="event-list"></div>
        </div>


    </div>

</div>




