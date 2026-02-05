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

  @media screen and (max-width: 768px) {
    .row {
      font-size: 12px !important;
    }
  }


  .btn-approvalLog {
    background-color: #D0DBF7;
    color: #14195a;
    border: none;
    padding: 10px 12px;
    font-size: 13px;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 6px !important;
    cursor: pointer;
    transition: background 0.3s ease;
    /*font-weight: bold;*/
    /*margin-top: 10px;*/

    /* Tambahan agar tombol pindah ke kanan */
    float: right;
    /* tombol ke kanan */
    margin-right: 10px;

  }


  .btn-approvalLogView {
    background-color: #D0DBF7;
    color: #14195a;
    border: none;
    padding: 10px 12px;
    font-size: 13px;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 6px !important;
    cursor: pointer;
    transition: background 0.3s ease;
    /*font-weight: bold;*/
    /*margin-top: 10px;*/

    /* Tambahan agar tombol pindah ke kanan */
    float: right;
    /* tombol ke kanan */
    margin-right: 10px;

  }

  #modalApprovalLog .modal-dialog {
    position: fixed;
    top: 100px;
    /* jarak dari atas */
    right: 50px;
    /* jarak dari kanan */
    margin: 0;
    /* hapus margin default */
    transform: none;
    /* hapus efek centering Bootstrap */
    width: 400px !important;
    /* lebar modal */
    z-index: 9999;
    /* pastikan di atas modal utama */
    /*border: 1px solid #7F0947;*/
    font-size: 8px;

  }


  /* Bentuk modal lebih lembut dan oval */
  #modalApprovalLog .modal-content {
    border-radius: 14px !important;
    /* 🔹 ubah jadi agak oval */
    overflow: hidden;
    /* supaya isi tidak keluar dari radius */
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    /* bayangan lembut */
    border: none;
    /* hilangkan border keras */
  }


  #modalApprovalLog .modal-header {
    /*background-color: #F9D1E6 !important;*/
    background-color: #D0DBF7 !important;
    /*color: #7F0947 !important;*/
    color: #14195a !important;
    padding: 8px 15px;
    /* kecilkan tinggi header */
    border-bottom: none;
    /* opsional: hilangkan border bawah */
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
    margin-left: auto;
    /* dorong tombol ke paling kanan */
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

  :root {
    --bg: #f6f8fc;
    --card: #ffffff;
    --text: #0f172a;
    --muted: #64748b;
    --border: rgba(15, 23, 42, .08);

    --primary: #112D80;
    /* biru fresh */
    --primary2: #112D80;
    --dark: #0b1220;

    --blue-soft: #eaf2ff;
    --orange-soft: #fff1e6;
    --green-soft: #e9fff3;

    --shadow: 0 12px 40px rgba(15, 23, 42, .08);
    --radius: 18px;
  }

  .lms-wrap {
    background: var(--bg);
    padding: 28px;
    border-radius: 18px !important;
  }

  .lms-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 18px;
  }

  .lms-title {
    margin: 0;
    font-size: 32px;
    letter-spacing: -0.6px;
    color: var(--text);
    font-weight: 800;
  }

  .lms-subtitle {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 14px;
  }

  .lms-actions {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .lms-btn {
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text);
    padding: 10px 14px;
    border-radius: 12px !important;
    cursor: pointer;
    font-weight: 700;
    font-size: 13px;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
  }

  .lms-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
  }

  .lms-btn-ghost {
    background: var(--card);
  }

  .lms-btn-primary {
    border-color: rgba(37, 99, 235, .25);
    background: linear-gradient(135deg, var(--primary), var(--primary2));
    color: #fff;
  }

  .lms-btn-dark {
    border-color: rgba(2, 6, 23, .25);
    background: linear-gradient(135deg, #0b1220, #111827);
    color: #fff;
  }

  .lms-btn.full {
    width: 100%;
  }

  .lms-icon {
    margin-right: 6px;
  }

  .lms-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin: 18px 0;
   
  }

  .sum-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius) !important;
    padding: 16px;
    display: flex;
    gap: 12px;
    align-items: center;
    box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
  }

  .sum-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }

  .sum-blue  {
  background: linear-gradient(to bottom right, #eff6ff, #dbeafe) !important;
  border: 1px solid #bfdbfe !important;
}
  
 .sum-orange {
  background: linear-gradient(to bottom right, #faf5ff, #ede9fe) !important;
  border: 1px solid #ddd6fe !important;
}
 .sum-green {
  background: linear-gradient(to bottom right, #fff7ed, #ffedd5) !important;
  border: 1px solid #fed7aa !important;
}

.sum-blue .sum-icon {
  background: #e0edff !important;
}

.sum-orange .sum-icon {
  background: #f3e8ff !important;
}

.sum-green .sum-icon {
  background: #ffefe0 !important;
}



  .sum-label {
    font-size: 12px;
    color: var(--muted);
    font-weight: 700;
  }

  .sum-value {
    font-size: 26px;
    font-weight: 900;
    color: var(--text);
    margin-top: 2px;
  }

  .lms-search {
    position: relative;
    margin: 12px 0 18px;
  }

  .lms-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    opacity: .65;
  }

  .lms-search-input {
    width: 100%;
    padding: 14px 14px 14px 44px;
    border-radius: 16px !important;
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, .85);
    outline: none;
    font-weight: 600;
  }

  .lms-search-input:focus {
    border-color: rgba(37, 99, 235, .35);
    box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
  }

  .lms-filter {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 14px;
  }

  .lms-filter-label {
    color: var(--muted);
    font-weight: 800;
    font-size: 13px;
  }

  .lms-pills {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .pill {
    border: 1px solid var(--border);
    background: #fff;
    padding: 8px 12px;
    border-radius: 999px !important;
    font-weight: 800;
    font-size: 12px;
    cursor: pointer;
    color: var(--text);
    transition: background .15s ease, transform .15s ease;
  }

  .pill:hover {
    transform: translateY(-1px);
  }

  .pill.active {
    background: rgba(37, 99, 235, .12);
    border-color: rgba(37, 99, 235, .25);
    color: var(--primary);
  }

  .lms-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-top: 12px;
  }

  .course-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius) !important;
    overflow: hidden;
    box-shadow: 0 14px 40px rgba(15, 23, 42, .07);
    transition: transform .16s ease, box-shadow .16s ease;
  }

  .course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 55px rgba(15, 23, 42, .12);
  }

  .course-cover {
    position: relative;
    aspect-ratio: 16/9;
    background: #eef2ff;
  }

  .course-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .badge-lms {
    position: absolute;
    right: 12px;
    top: 12px;
    padding: 6px 10px;
    border-radius: 999px !important;
    font-size: 11px;
    font-weight: 900;
    border: 1px solid rgba(15, 23, 42, .08);
    backdrop-filter: blur(6px);
    background: rgba(255, 255, 255, .85);
  }

  .badge.in\ progress {
    background: rgba(37, 99, 235, .12);
    color: var(--primary);
    border-color: rgba(37, 99, 235, .20);
  }

  .badge.completed {
    background: rgba(16, 185, 129, .14);
    color: #047857;
    border-color: rgba(16, 185, 129, .22);
  }

  .badge.not\ started {
    background: rgba(2, 6, 23, .06);
    color: #0b1220;
  }

  .course-body {
    padding: 14px 14px 16px;
  }

  .course-title {
    margin: 4px 0 10px;
    font-size: 16px;
    font-weight: 900;
    color: var(--text);
    line-height: 1.25;
  }

  .course-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    color: var(--muted);
    font-weight: 700;
    font-size: 12px;
  }

  .meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .course-progress {
    margin-top: 12px;
  }

  .progress-label {
    display: flex;
    justify-content: space-between;
    color: var(--muted);
    font-weight: 800;
    font-size: 12px;
    margin-bottom: 8px;
  }

  .progress-track {
    height: 10px;
    border-radius: 999px !important;
    background: rgba(15, 23, 42, .08);
    overflow: hidden;
  }

  .progress-bar {
    height: 100%;
    border-radius: 999px !important;
    background: linear-gradient(90deg, var(--primary), #60a5fa);
  }

  .course-footer {
    margin-top: 14px;
  }

  .lms-empty {
    grid-column: 1/-1;
    background: #fff;
    border: 1px dashed rgba(15, 23, 42, .18);
    border-radius: var(--radius) !important;
    padding: 26px;
    text-align: center;
  }

  .lms-empty-title {
    font-weight: 900;
    color: var(--text);
    font-size: 16px;
  }

  .lms-empty-sub {
    color: var(--muted);
    margin-top: 6px;
  }

  /* Responsive */
  @media (max-width: 992px) {
    .lms-summary {
      grid-template-columns: 1fr;
    }

    .lms-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 600px) {
    .lms-wrap {
      padding: 16px;
    }

    .lms-header {
      flex-direction: column;
      align-items: stretch;
    }

    .lms-actions {
      justify-content: flex-start;
    }

    .lms-grid {
      grid-template-columns: 1fr;
    }
  }
</style>