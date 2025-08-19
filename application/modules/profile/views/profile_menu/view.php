

<style>
    table tbody td:nth-child(2) {
        text-align: center;
    }

    table tbody td {
        font-size: 12px;
        /*border-bottom: 1px solid #e2e3e4ff;*/
    }

    /* thead font lebih kecil */
    .table-container thead th {
        font-size: 13px !important;
        white-space: nowrap;

    }


    .table-container tbody td {
        white-space: nowrap;
    }


    .table-container {
        overflow-x: auto;
    }

    .table-container table {
        border-collapse: collapse;
        min-width: 100%;
    }

</style>


<div class="dashboard-container">

    <!-- Profile Box -->


    <div class="profile-section">
        <div class="profile-card">
            <!-- <img src="<?php echo _ASSET_PROFILE_PICTURE; ?>" alt="Profile Picture" class="profile-image"> -->
            <span class="emp_photo"></span>
            <div class="profile-name">
                <span class="name" style="font-weight: bold;"></span>
            </div>


            <div class="profile-details">

                <div style="color: #888888;">
                    <span class="job_title_department">
                        <?php
                        $job = !empty($job_title) ? $job_title : '[Job Title]';
                        $dept = !empty($department) ? $department : '[Department]';
                        echo $job . ' – ' . $dept;
                        ?>
                    </span>
                </div>



                <div style="margin-top: 5px;">
                    <span class="division"></span>
                </div>


                <div style="margin-top: 5px;">
                    <i class="fa fa-envelope" style="margin-right: 5px;"></i>
                    <span class="email"></span>
                </div>

                <div style="margin-top: 5px;">
                    <i class="fa fa-phone" style="margin-right: 5px;"></i>
                    <span class="phone"></span>
                </div>
            </div>

        </div>

        <div class="profile-info">
            <div class="info-grid">
                <!-- Kolom 1 -->
                <div class="column">

                    <div><strong>NIK</strong><span class="nik"></span></div>
                    <div><strong>Date of Birth</strong><span class="date_of_birth"><span></div>
                    <div><strong>Gender</strong><span class="gender"></span></div>
                    <div><strong>Address</strong><span class="address"></span></div>

                </div>

                <!-- Kolom 2 -->
                <div class="column">
                    <div><strong>Date of Hired</strong><span class="date_of_hired"></span></div>
                    <div><strong>Status</strong><span class="status"></span></div>
                    <div><strong>Shift Type</strong><span class="shift_type"></span></div>
                    <div><strong>Direct</strong><span class="direct"></span></div>
                </div>

                <!-- Kolom 3 -->
                <div class="columnlevel">
                    <div><strong>Job Level</strong><span class="job_level"></span></div>
                </div>
            </div>
        </div>
    </div>



    <!-- Summary Box -->

    <div class="summary-section">
        <div class="summary-box">
             <div class="summary-item highlight">
                <div>
                    <h4>Remaining Leave</h4>
                    <div class="total"><span class="ttl_sisa_cuti"></span></div>
                </div>
                <div class="icon">
                    <i class="fa fa-user"></i><i class="fa fa-arrow-down" style="font-size: 15px;"></i>
                </div>

            </div>

            <div class="summary-item yellow">

                <div class="title" style="margin-top: 20px;">Tasklist</div>
                <div class="tasklist-line">
                    <div class="task-item">
                        <p class="ttl_tasklist_open"></p>
                        <p>Open</p>
                    </div>

                    <div class="task-item">
                        <p class="ttl_tasklist_inprogress"></p>
                        <p>In Progress</p>
                    </div>

                    <div class="task-item">
                        <p class="ttl_tasklist_closed"></p>
                        <p>Done</p>
                    </div>
                </div>

            </div>

            <div class="summary-item reimbursement">
                <h4>Remaining Reimbursement Limit</h4>
                <div class="reimbursement-grid">
                    <div class="item">
                        <span class="title_reim">Rawat jalan</span>
                        <span class="amount" id="amount_rawatjalan"></span>
                    </div>
                    <div class="item">
                        <span class="title_reim">Kacamata</span>
                        <span class="amount" id="amount_kacamata"></span>
                    </div>
                    <div class="item">
                        <span class="title_reim">Rawat Inap</span>
                        <span class="amount" id="amount_rawatinap"></span>
                    </div>
                    <div class="item">
                        <span class="title_reim">Persalinan</span>
                        <span class="amount" id="amount_persalinan"></span>
                    </div>
                </div>

              
                
            </div> 
        </div>

        <!--  <span class="dataBday"> -->

        <!-- </span> -->

    </div>



    <!-- Chart Placeholder -->
    <div class="chart-section">

        <div class="chart-box">
            <strong>Monthly Attendance Summary</strong>
            <!-- Placeholder Chart -->
            <canvas id="monthly_attendance_summ" style="margin-top: 20px;"></canvas>
        </div>

        <div class="right-section">
            <div class="birthday-box">
                <h4 class="birthday-title">Today’s Birthdays</h4>
                <div class="birthday-content">
                    <!-- <img id="birthday-image" src="<?php echo _ASSET_PROFILE_PICTURE; ?>" alt="Profile Picture"
                    class="birthday-image"> -->
                    <img id="birthday-image" alt="Profile Picture" class="birthday-image">
                    <div class="birthday-info">
                        <div id="birthday-name" class="birthday-name"></div>
                        <div id="birthday-job" class="birthday-job"></div>
                    </div>
                    <div class="birthday-arrow" id="birthday-arrow">
                        <i class="fa fa-caret-up" onclick="showPrevious()"></i>
                        <i class="fa fa-caret-down" onclick="showNext()"></i>
                    </div>
                </div>
            </div>

            <div class="events-box">
                <h4 class="birthday-title">This Week's Highlights</h4>
                <span class="clnoevents" style="font-size: 14px; color: #555;"></span>
                <div class="scroll-area" id="event-list"></div>
            </div>


        </div>

    </div>


    <div class="chart-section2">

        <!-- <div class="chart-box2">
            <strong>Daily Tasklist Statistics</strong>

            <div class="top-bar">
                <div class="date-picker-wrapper">
                    <span class="date-icon"><i class="fas fa-calendar-alt"></i></span>
                    <input type="text" id="fltasklistperiod" name="fltasklistperiod" placeholder="Select Date"
                        class="date-input" />
                </div>


                <div class="employee-select-wrapper">
                    <span class="employee-icon"><i class="fas fa-user"></i></span>
                   
                    <?=$selstatus?>
                </div>
            </div>


            <canvas id="daily_tasklist" style="margin-top: 20px; margin-bottom: 20px;"></canvas>


        </div> -->


        <!-- <div class="right-section">
             <div class="box-1 table-box">
                <div class="title_tasklist">Tasklist Progress</div>
                <div class="table-container" style="margin-top: 10px;">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 40%; font-size:12px">Task</th>
                                <th style="width: 25%; font-size:12px">Progress (%)</th>
                                <th style="width: 35%; font-size:12px">Due Date</th>
                            </tr>
                        </thead>
                        <tbody id="tasklistBody">
                           
                        </tbody>
                    </table>

                </div>
            </div>
        </div> -->

        <div class="chart-box2">
            <div class="title-box-chart">
                <strong>Daily Tasklist Statistics</strong>
                <div class="top-bar">
                    <div class="date-picker-wrapper">
                        <span class="date-icon"><i class="fa fa-calendar"></i></span>
                        <input type="text" id="fltasklistperiod" name="fltasklistperiod" placeholder="Select Date"
                            class="date-input" />
                    </div>
                    <div class="employee-select-wrapper">
                        <span class="employee-icon"><i class="fa fa-user"></i></span>
                        <?= $selstatus ?>
                    </div>
                </div>
            </div>
            <canvas id="daily_tasklist" style="margin-top: 20px; margin-bottom: 20px;"></canvas>
        </div>

        <div class="right-section">
            <div class="box-1 table-box">
                <div class="title_tasklist">Tasklist Progress</div>
                <div class="table-container" style="margin-top: 10px;">
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left; font-size:12px">Task</th>
                                <!-- <th style="width: 100px; text-align: center; font-size:12px">Progress (%)</th> -->
                                <th style="text-align: center;">Progress (%)</th>
                                <th style="font-size:12px">Due Date</th>
                            </tr>
                        </thead>
                        <tbody id="tasklistBody">
                            <!-- Data will be injected here by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>






</div>
