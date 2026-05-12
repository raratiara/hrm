<style type="text/css">
#submitFilter {
	background-color: #3832d2;
	color: white;
	padding: 8px 14px;
	font-size: 12px;
	line-height: 1.2;
	border: none;
	border-radius: 6px;
	box-shadow: 0 3px 4px rgba(0, 0, 0, 0.2);
	display: inline-flex;
	align-items: center;
	justify-content: center;
}

.bonus-thr-form .control-label {
	text-align: left !important;
}

.bonus-thr-form .form-group {
	margin-bottom: 16px;
}

.bonus-thr-form {
	margin: 20px 0 18px;
}

.bonus-thr-form .control-label {
	font-size: 16px;
	font-weight: 400;
	line-height: 34px;
}

.bonus-thr-form .bonus-thr-control {
	padding-left: 15px;
	padding-right: 15px;
}

.bonus-thr-form .select2-container,
.bonus-thr-form input.form-control,
.bonus-thr-form textarea.form-control {
	width: 100% !important;
}

.bonus-thr-form textarea.form-control {
	min-height: 84px;
}

.bonus-thr-table-wrap {
	display: block;
	overflow: auto !important;
	max-height: 340px;
	max-width: 100%;
	position: relative;
	width: 100%;
}

#tblDetailBonusThr,
#tblDetailBonusThrView {
	border-collapse: separate;
	border-spacing: 0;
	min-width: 720px;
	table-layout: fixed;
	width: 720px !important;
}

#tblDetailBonusThr th,
#tblDetailBonusThr td,
#tblDetailBonusThrView th,
#tblDetailBonusThrView td {
	white-space: nowrap;
}

#tblDetailBonusThr th,
#tblDetailBonusThr td,
#tblDetailBonusThrView th,
#tblDetailBonusThrView td {
	background-clip: padding-box;
	position: relative;
}

#tblDetailBonusThr thead th,
#tblDetailBonusThrView thead th {
	background: #fff !important;
	position: sticky;
	top: 0;
	z-index: 6;
}

#tblDetailBonusThr th:nth-child(1),
#tblDetailBonusThr td:nth-child(1),
#tblDetailBonusThrView th:nth-child(1),
#tblDetailBonusThrView td:nth-child(1) {
	background: #fff !important;
	left: 0;
	min-width: 145px;
	position: sticky;
	width: 145px;
	z-index: 7;
}

#tblDetailBonusThr th:nth-child(2),
#tblDetailBonusThr td:nth-child(2),
#tblDetailBonusThrView th:nth-child(2),
#tblDetailBonusThrView td:nth-child(2) {
	background: #fff !important;
	box-shadow: 2px 0 0 #e5e9f2;
	left: 145px;
	min-width: 230px;
	position: sticky;
	width: 230px;
	z-index: 7;
}

#tblDetailBonusThr th:nth-child(3),
#tblDetailBonusThr td:nth-child(3),
#tblDetailBonusThrView th:nth-child(3),
#tblDetailBonusThrView td:nth-child(3) {
	min-width: 150px;
	width: 150px;
}

#tblDetailBonusThr th:nth-child(4),
#tblDetailBonusThr td:nth-child(4),
#tblDetailBonusThrView th:nth-child(4),
#tblDetailBonusThrView td:nth-child(4) {
	min-width: 195px;
	width: 195px;
}

#tblDetailBonusThr input[name^="nominal_amount"],
#tblDetailBonusThrView input[name^="nominal_amount"] {
	max-width: 130px;
	width: 130px;
}

#tblDetailBonusThr thead th:nth-child(1),
#tblDetailBonusThr thead th:nth-child(2),
#tblDetailBonusThrView thead th:nth-child(1),
#tblDetailBonusThrView thead th:nth-child(2) {
	z-index: 8;
}

/* Override table/tablesaw defaults so this behaves like hitung gaji. */
#tblDetailBonusThr,
#tblDetailBonusThrView {
	table-layout: fixed !important;
	width: 100% !important;
	min-width: 1000px !important;
}

#tblDetailBonusThr th,
#tblDetailBonusThr td,
#tblDetailBonusThrView th,
#tblDetailBonusThrView td {
	height: 42px !important;
	line-height: 1.4 !important;
	padding: 6px 10px !important;
	overflow: hidden !important;
	text-overflow: ellipsis !important;
	white-space: nowrap !important;
}

#tblDetailBonusThr th:nth-child(1),
#tblDetailBonusThr td:nth-child(1),
#tblDetailBonusThrView th:nth-child(1),
#tblDetailBonusThrView td:nth-child(1) {
	left: 0 !important;
	min-width: 150px !important;
	max-width: 150px !important;
	width: 150px !important;
}

#tblDetailBonusThr th:nth-child(2),
#tblDetailBonusThr td:nth-child(2),
#tblDetailBonusThrView th:nth-child(2),
#tblDetailBonusThrView td:nth-child(2) {
	left: 150px !important;
	min-width: 210px !important;
	max-width: 210px !important;
	width: 210px !important;
}

#tblDetailBonusThr th:nth-child(3),
#tblDetailBonusThr td:nth-child(3),
#tblDetailBonusThrView th:nth-child(3),
#tblDetailBonusThrView td:nth-child(3) {
	min-width: 260px !important;
	max-width: 260px !important;
	width: 260px !important;
}

#tblDetailBonusThr th:nth-child(4),
#tblDetailBonusThr td:nth-child(4),
#tblDetailBonusThrView th:nth-child(4),
#tblDetailBonusThrView td:nth-child(4) {
	min-width: 380px !important;
	max-width: 380px !important;
	width: 380px !important;
}

#tblDetailBonusThr thead th,
#tblDetailBonusThrView thead th {
	position: sticky !important;
	top: 0 !important;
	z-index: 50 !important;
}

#tblDetailBonusThr thead th:nth-child(1),
#tblDetailBonusThrView thead th:nth-child(1) {
	left: 0 !important;
	position: sticky !important;
	z-index: 60 !important;
}

#tblDetailBonusThr thead th:nth-child(2),
#tblDetailBonusThrView thead th:nth-child(2) {
	left: 150px !important;
	position: sticky !important;
	z-index: 59 !important;
}

#tblDetailBonusThr input[name^="nominal_amount"],
#tblDetailBonusThrView input[name^="nominal_amount"] {
	max-width: 190px !important;
	width: 190px !important;
}

#tblDetailBonusThr input[name^="detail_note"],
#tblDetailBonusThrView input[name^="detail_note"] {
	max-width: 320px !important;
	width: 320px !important;
}
</style>
