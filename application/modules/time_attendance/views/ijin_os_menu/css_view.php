<style type="text/css">
    [class*="col-"] .chosen-container {
        width: 98% !important;
    }

    [class*="col-"] .chosen-container .chosen-search input[type="text"] {
        padding: 2px 4% !important;
        width: 90% !important;
        margin: 5px 2%;
    }

    [class*="col-"] .chosen-container .chosen-drop {
        width: 100% !important;
    }

    .modal-dialog {
        width: 90% !important;
    }


    .btn-approvalLog {
        background-color: #D0DBF7;
        color: #14195a;
        border: none;
        padding: 10px 12px;
        font-size: 13px; padding: 5px 10px;       
        font-size: 12px;  
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease;
        /*font-weight: bold;*/
        /*margin-top: 10px;*/

        /* Tambahan agar tombol pindah ke kanan */
        float: right; /* tombol ke kanan */
        margin-right: 10px;

    }


    .btn-approvalLogView {
        background-color: #D0DBF7;
        color: #14195a;
        border: none;
        padding: 10px 12px;
        font-size: 13px; padding: 5px 10px;       
        font-size: 12px;  
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease;
        /*font-weight: bold;*/
        /*margin-top: 10px;*/

        /* Tambahan agar tombol pindah ke kanan */
        float: right; /* tombol ke kanan */
        margin-right: 10px;

    }

    #modalApprovalLog .modal-dialog {
          position: fixed;
          top: 100px;        /* jarak dari atas */
          right: 50px;       /* jarak dari kanan */
          margin: 0;         /* hapus margin default */
          transform: none;   /* hapus efek centering Bootstrap */
          width: 400px !important;      /* lebar modal */
          z-index: 9999;     /* pastikan di atas modal utama */
          /*border: 1px solid #7F0947;*/
          font-size: 8px;

    }


    /* Bentuk modal lebih lembut dan oval */
    #modalApprovalLog .modal-content {
      border-radius: 14px !important; /* 🔹 ubah jadi agak oval */
      overflow: hidden;    /* supaya isi tidak keluar dari radius */
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); /* bayangan lembut */
      border: none; /* hilangkan border keras */
    }


    #modalApprovalLog .modal-header {
      /*background-color: #F9D1E6 !important;*/ 
      background-color: #D0DBF7 !important;
      /*color: #7F0947 !important;*/               
      color: #14195a !important;
      padding: 8px 15px;         /* kecilkan tinggi header */
      border-bottom: none;       /* opsional: hilangkan border bawah */
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top-left-radius: 14px;
      border-top-right-radius: 14px;
      font-weight: bold;
      font-size: 10px;


      
    }


    /* judul modal */
    #modalApprovalLog .modal-title {
      font-size: 12px;
      font-weight: 600;
      margin: 0;
    }

    /* tombol close */
    #modalApprovalLog .close {
      color: #fff;
      opacity: 0.8;
      font-size: 12px;
      line-height: 1;
      margin-left: auto;     /* dorong tombol ke paling kanan */
      border: none;
      background: transparent;
    }

    #modalApprovalLog .close:hover {
      opacity: 1;
    }

    #modalApprovalLog .modal-body {
      padding: 15px 20px;
      background-color: #f9fafc;
      border-bottom-left-radius: 20px;
      border-bottom-right-radius: 20px;
    }

    #modalApprovalLog .modal-body table,
    #modalApprovalLog .modal-body table td,
    #modalApprovalLog .modal-body table th {
      font-size: 10px !important;
    }


</style>


