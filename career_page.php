<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Career - Gerbang Data Indonesia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /*.hero {
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                  url('https://hrm.nathabuana.com/public/assets/images/logo/bg_careerpage.jpg') center/cover no-repeat;*/
      .hero {
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                  url('http://localhost/_hrm/public/assets/images/logo/bg_careerpage.jpg') center/cover no-repeat;
      color: white;
      padding: 80px 20px;
      text-align: center;
      border-radius: 12px;
      margin-bottom: 40px;
    }
    .hero h1 {
      font-weight: bold;
    }
  </style>
</head>
<body class="p-4">

  <!-- Hero Section -->
  <div class="hero">
    <h1>Join Our Team</h1>
    <p class="lead">Grow your career with Gerbang Data Indonesia</p>
  </div>

  <!-- Job Vacancy List -->
  <div class="container">
    <h3 class="mb-3">Job Vacancy List</h3>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Position</th>
          <th>Job Description</th>
          <th>Level</th>
          <th>Division</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="jobTable">
        <!-- Data via JS -->
      </tbody>
    </table>
  </div>

  <!-- Modal Apply -->
  <div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog">
      <form id="applyForm" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Form Apply</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="job_id" id="job_id">
            <div class="mb-3">
              <label>Position</label>
              <input type="text" class="form-control" id="job_position" name="job_position" readonly>
            </div>
            <div class="mb-3">
              <label>Full Name</label>
              <input type="text" class="form-control" name="full_name" required>
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label>Phone</label>
              <input type="phone" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
              <label>CV</label> <span style="color:red; font-size: 10px;"> *pdf | doc | docx (max 5MB)</span>
              <input type="file" class="form-control" name="cv" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Apply</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    var url = 'http://localhost/_hrm';
    //var url = 'https://hrm.nathabuana.com';

    // ambil list job dari API
    fetch(url + "/api/api/get_career_list", { method: "POST" })
      .then(response => response.json())
      .then(result => {
        if (result.status === 200) {
          let jobs = result.data;
          let tableBody = document.getElementById("jobTable");
          tableBody.innerHTML = "";

          jobs.forEach((job, index) => {
            let row = `
              <tr>
                <td>${index + 1}</td>
                <td>${job.subject}</td>
                <td>${job.justification}</td>
                <td>${job.job_level_name}</td>
                <td>${job.divname}</td>
                <td>
                  <button class="btn btn-success btn-sm" 
                          onclick="openApplyModal('${job.id}', '${job.subject}')">
                    Apply
                  </button>
                </td>
              </tr>
            `;
            tableBody.innerHTML += row;
          });
        }
      });

    // buka modal apply
    function openApplyModal(jobId, position) {
      document.getElementById("job_id").value = jobId;
      document.getElementById("job_position").value = position;
      new bootstrap.Modal(document.getElementById('applyModal')).show();
    }

    // submit form apply
    document.getElementById("applyForm").addEventListener("submit", function(e) {
      e.preventDefault();
      let formData = new FormData(this);

      fetch(url + "/api/api/save_candidates", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(res => {
        if (res.status === 200) {
          alert("Apply success!");
          bootstrap.Modal.getInstance(document.getElementById('applyModal')).hide();
          this.reset();
        } else {
          alert("Failed: " + res.message);
        }
      })
      .catch(err => console.error("Error submit:", err));
    });
  </script>
</body>
</html>
