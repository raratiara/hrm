


<style type="text/css">

	.modal-content {
	    width: 1200px;
	    max-width: 1200px;
	}

	

/* ============================= */
/* CONTAINER SCROLL */
/* ============================= */
.table-scroll-x {
    padding-bottom: 25px;
}

.table-scrollable {
    margin-bottom: 15px;
    overflow-x: auto;
    overflow-y: auto;
    position: relative;
}

/* ============================= */
/* TABLE BASE */
/* ============================= */
#tblDetailHistBpjs {
    min-width: 2000px;
    table-layout: fixed;
    border-collapse: separate;
}

/* ============================= */
/* SET WIDTH KOLOM WAJIB */
/* ============================= */

/* Kolom 1 (NIK) */
#tblDetailHistBpjs th:nth-child(1),
#tblDetailHistBpjs td:nth-child(1) {
    width: 150px;
    min-width: 150px;
    max-width: 150px;
}

/* Kolom 2 (Karyawan) */
#tblDetailHistBpjs th:nth-child(2),
#tblDetailHistBpjs td:nth-child(2) {
    width: 150px;
    min-width: 150px;
    max-width: 150px;
}



/* Kolom lainnya */
#tblDetailHistBpjs th:not(:nth-child(-n+2)),
#tblDetailHistBpjs td:not(:nth-child(-n+2)) {
    width: 150px;
    min-width: 150px;
    max-width: 150px;
}


/* WAJIB untuk sticky + tablesaw */
#tblDetailHistBpjs {
    border-collapse: separate !important;
}

#tblDetailHistBpjs thead,
#tblDetailHistBpjs tbody,
#tblDetailHistBpjs tr,
#tblDetailHistBpjs th,
#tblDetailHistBpjs td {
    transform: none !important;
}


/* ============================= */
/* FREEZE BODY */
/* ============================= */

#tblDetailHistBpjs td:nth-child(1) {
    position: sticky !important;
    left: 0 !important;
    background: #fff !important;
    z-index: 5 !important;
}

#tblDetailHistBpjs td:nth-child(2) {
    position: sticky !important;
    left: 150px !important;
    background: #fff !important;
    z-index: 4 !important;
}


/* ============================= */
/* FREEZE HEADER */
/* ============================= */

#tblDetailHistBpjs thead th {
    position: sticky !important;
    top: 0 !important;
    
    z-index: 10 !important;
}

#tblDetailHistBpjs thead th:nth-child(1) {
    left: 0 !important;
    z-index: 30 !important;
}

#tblDetailHistBpjs thead th:nth-child(2) {
    left: 150px !important;
    z-index: 29 !important;
}


/* ============================= */
/* ROW & TEXT STYLE */
/* ============================= */

#tblDetailHistBpjs th,
#tblDetailHistBpjs td {
    height: 40px;
    padding: 6px 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

#tblDetailHistBpjs tbody tr {
    height: 42px;
}

</style>







<div class="row">
	
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Periode Penggajian</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="periode_penggajian"></span>
			</div>
		</div>
		

	</div>

</div>





<div class="row histbpjsview" id="inpHistBpjsView">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Details </div>
				<div class="tools">
					<!-- <input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addabsenosrow" value="Add Row" /> -->
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scroll-x">
					<div class="table-scrollable tablesaw-cont" style="overflow: auto; max-height: 300px;">
					<table class="table table-striped table-bordered table-hover histbpjs-list-view tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailHistBpjs">
						<thead>
							<tr>
								<th scope="col" style="width: 150px !important;">NIK</th>
								<th scope="col">Karyawan</th>
								<th scope="col">No BPJS Kesehatan</th>
								<th scope="col">Nominal BPJS Kesehatan</th>
								<th scope="col">No BPJS TK</th>
								<th scope="col">Nominal BPJS TK</th>
								<th scope="col">Tanggal Potong</th>
								<th scope="col">Tanggal Setor</th>
								<th scope="col">Tanggal Dikembalikan</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
						<tfoot>
						</tfoot>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>