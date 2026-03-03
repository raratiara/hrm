<style>
    .lms-stat {
        border-radius: 14px !important;
        padding: 18px 18px;
        border: 1px solid #e9ecf3;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 84px;
        margin-bottom: 20px;
    }

    .lms-stat .icon {
        width: 44px;
        height: 44px;
        border-radius: 12px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef3ff;
        color: #112D80;
        font-size: 18px;
    }

    .lms-stat .meta {
        line-height: 1.1;
    }

    .lms-stat .meta .label {
        color: #6c757d;
        font-size: 12px;
    }

    .lms-stat .meta .value {
        font-size: 22px;
        font-weight: 700;
        color: #111;
    }

    .lms-toolbar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .lms-search {
        flex: 1;
        min-width: 260px;
        position: relative;
    }

    .lms-search i {
        position: absolute;
        left: 12px;
        top: 11px;
        color: #9aa4b2;
    }

    .lms-search input {
        width: 100%;
        padding: 10px 12px 10px 34px;
        border-radius: 10px !important;
        border: 1px solid #e3e7ef;
        outline: none;
    }

    .lms-filters {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .lms-filters .btn {
        border-radius: 10px !important;
        padding: 7px 12px !important;
        border: 1px solid #e3e7ef !important;
        background: #fff;
    }

    .lms-filters .btn.active {
        background: #112D80 !important;
        border-color: #112D80 !important;
        color: #fff !important;
    }

    .lms-grid {
        margin-top: 14px;
    }

    .lms-card {
        border: 1px solid #e9ecf3;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .lms-card .cover {
        height: 140px;
        background: linear-gradient(135deg, #eef3ff, #ffffff);
        position: relative;
    }

    .lms-card .badge-status {
        position: absolute;
        right: 12px;
        top: 12px;
        border-radius: 999px !important;
        padding: 6px 10px;
        font-size: 12px;
        background: #f1f5f9;
        color: #111;
    }

    .lms-card .body {
        padding: 14px 14px 10px 14px;
        flex: 1;
    }

    .lms-card .title {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin: 0 0 8px 0;
        min-height: 40px;
    }

    .lms-card .meta {
        color: #6c757d;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .lms-card .meta i {
        width: 16px;
        text-align: center;
        color: #112D80;
    }

    .lms-card .footer {
        padding: 10px 14px 14px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .lms-card .actions .btn {
        border-radius: 10px !important;
        padding: 6px 9px !important;
    }

    .lms-card .select-box {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #6c757d;
    }

    .lms-pagination {
        margin-top: 18px;
        display: flex;
        justify-content: center;
    }

    /* ====== WRAPPER PENGGANTI PORTLET ====== */
    .lms-page-card {
        background: #fff;
        border: 1px solid #e9ecf3;
        border-radius: 16px !important;
        box-shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
        overflow: hidden;

    }

    .lms-page-header {
        padding: 14px 18px;
        border-bottom: 1px solid #eef1f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .lms-page-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #111;
    }

    .lms-page-title i {
        color: #112D80;
    }

    .lms-page-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .lms-page-body {
        padding: 18px;
        background: #fff;
    }

    /* rapihin tombol metronic default biar seragam */
    .lms-page-actions .btn {
        border-radius: 999px !important;
        padding: 7px 12px !important;
    }

    /* di mobile biar title & actions tetap enak */
    @media(max-width: 768px) {
        .lms-page-header {
            padding: 12px 14px;
        }

        .lms-page-body {
            padding: 14px;
        }
    }

    /* ====== LMS STAT COLOR VARIANTS ====== */
    .lms-stat.stat-total {
        background: linear-gradient(to bottom right, #eff6ff, #dbeafe) !important;
        border: 1px solid #bfdbfe !important;
    }

    .lms-stat.stat-active {
        background: linear-gradient(to bottom right, #faf5ff, #ede9fe) !important;
        border: 1px solid #ddd6fe !important;
    }

    .lms-stat.stat-inactive {
        background: linear-gradient(to bottom right, #fff7ed, #ffedd5) !important;
        border: 1px solid #fed7aa !important;
    }
</style>

<h3 class="page-title"></h3>
<!-- HEADER -->
<div class="lms-page-header">
    <div class="lms-page-title">
        <?php if (isset($icon) && $icon <> "") echo '<i class="fa ' . $icon . '"></i>'; ?>
        <span><?php if (isset($title) && $title <> "") echo $title; ?></span>
    </div>

    <div class="lms-page-actions">
        <?php if (defined('_REPORT') && _REPORT == "1") { ?>
            <a class="btn btn-default btn-sm" id="btnReportData"><i class="fa fa-file"></i> Report</a>
        <?php } ?>

        <?php if (_USER_ACCESS_LEVEL_EKSPORT == "1") { ?>
            <a class="btn btn-default btn-sm" id="btnEksportData"><i class="fa fa-download"></i> Eksport</a>
        <?php } ?>

        <?php if (_USER_ACCESS_LEVEL_IMPORT == "1") { ?>
            <a class="btn btn-default btn-sm" id="btnImportData"><i class="fa fa-upload"></i> Import</a>
        <?php } ?>

        <?php if (_USER_ACCESS_LEVEL_ADD == "1") { ?>
            <a class="btn btn-default btn-sm" id="btnAddData"><i class="fa fa-plus"></i> Add Course</a>
        <?php } ?>

        <?php if (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
            <a class="btn btn-default btn-sm" id="btnBulkData"><i class="fa fa-times"></i> Delete Bulk</a>
        <?php } ?>
    </div>
</div>

<div class="lms-page-card">



    <!-- BODY -->
    <div class="lms-page-body">

        <!-- STAT BOXES -->
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="lms-stat stat-total">
                    <div class="icon"><i class="fa fa-book"></i></div>
                    <div class="meta">
                        <div class="label">Total Courses</div>
                        <div class="value" id="stat_total">0</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12">
                <div class="lms-stat stat-active">
                    <div class="icon" style="background:#efe7ff;color:#7c3aed;"><i class="fa fa-line-chart"></i></div>
                    <div class="meta">
                        <div class="label">Active</div>
                        <div class="value" id="stat_active">0</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12">
                <div class="lms-stat stat-inactive">
                    <div class="icon" style="background:#ffe9d6;color:#f97316;"><i class="fa fa-graduation-cap"></i></div>
                    <div class="meta">
                        <div class="label">Not Active</div>
                        <div class="value" id="stat_inactive">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEARCH + SELECT -->
        <div class="lms-toolbar">
            <div class="lms-search">
                <i class="fa fa-search"></i>
                <input type="text" id="lmsSearch" placeholder="Search courses, departments..." />
            </div>

            <?php if (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
                <label class="btn btn-default btn-sm" style="border-radius:10px !important;margin:0;">
                    <input type="checkbox" id="check-all" style="vertical-align:middle;margin-right:6px;"> Select
                </label>
            <?php } ?>
        </div>

        <!-- FILTERS -->
        <div class="lms-filters">
            <span style="color:#6c757d;font-size:12px;margin-right:6px;">
                <i class="fa fa-filter"></i> Filter by category:
            </span>
            <button class="btn btn-default active" data-category="">All Courses</button>
            <button class="btn btn-default" data-category="Hardskill">Development</button>
            <button class="btn btn-default" data-category="Softskill">Design</button>
        </div>

        <!-- GRID -->
        <form id="frmListData" name="frmListData">
            <div class="row lms-grid" id="lmsGrid"></div>
        </form>

        <!-- PAGINATION -->
        <div class="lms-pagination">
            <ul class="pagination" id="lmsPagination"></ul>
        </div>

    </div>
</div>