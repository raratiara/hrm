<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Career - Gerbang Data Indonesia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .hero {
      /*background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                  url('http://localhost/_hrm/public/assets/images/logo/bg_careerpage.jpg') center/cover no-repeat;*/
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                  url('https://hrm.nathabuana.com/public/assets/images/logo/bg_careerpage.jpg') center/cover no-repeat;
      color: white;
      padding: 80px 20px;
      text-align: center;
      margin-bottom: 40px;
    }
    .job-card {
      border: 1px solid #eee;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      background: #fff;
      transition: 0.2s;
    }
    .job-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .job-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 15px;
    }
    /* List Style */
    .job-list-card {
      border: 1px solid #eee;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 15px;
      background: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: 0.2s;
    }
    .job-list-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .job-info {
      flex: 1;
    }
    .job-info h5 {
      margin-bottom: 5px;
      font-weight: bold;
    }
    .job-info p {
      margin: 0;
      font-size: 14px;
      color: #555;
    }
    .job-actions {
      text-align: right;
      margin-left: 20px;
    }
    .job-actions .btn {
      margin-left: 5px;
    }
    .view-toggle .btn {
      border-radius: 8px;
      margin-left: 5px;
    }
    .view-toggle .btn.active {
      background-color: #0d6efd;
      color: #fff;
    }

    .btn-navy {
      background-color: #001f54; /* biru navy */
      color: #fff;
    }
    .btn-navy:hover {
      background-color: #00163d; /* lebih gelap pas hover */
      color: #fff;
    }

    .link-navy {
      color: #001f54;
      text-decoration: none;
      font-weight: 500;
    }
    .link-navy:hover {
      text-decoration: underline;
      color: #00163d;
    }

  </style>
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="index.php">
        <!-- <img src="http://localhost/_hrm/public/assets/images/logo/gerbangdata.PNG" alt="Logo" height="50"> -->
        <img src="https://hrm.nathabuana.com/public/assets/images/logo/gerbangdata.PNG" alt="Logo" height="50">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Homepage</a></li>
          <li class="nav-item"><a class="nav-link active" href="career.php">Vacancy</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="hero">
    <h1>Join Our Team</h1>
    <p class="lead">Grow your career with Gerbang Data Indonesia</p>
  </div>

  <div class="container">
    <!-- Search + Counter + Toggle -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 id="jobCounter">Lowongan tersedia</h5>
      <div class="d-flex align-items-center">
        <input type="text" id="searchJob" class="form-control w-50 me-2" placeholder="Search position...">
        <div class="view-toggle btn-group">
          <button id="btnCard" class="btn btn-outline-secondary active" title="Card View">
            <i class="bi bi-grid-fill"></i>
          </button>
          <button id="btnList" class="btn btn-outline-secondary" title="List View">
            <i class="bi bi-list-ul"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Job List -->
    <div id="jobList" class="row"></div>
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
            <button type="submit" class="btn btn-navy">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <!-- Modal Detail -->
  <div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailTitle">Job Detail</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p><strong>Division:</strong> <span id="detailDivision"></span></p>
          <p><strong>Level:</strong> <span id="detailLevel"></span></p>
          <p><strong>Status:</strong> <span id="detailStatus"></span></p>
          <p><strong>Job Placement:</strong> <span id="detailJobplacement"></span></p>
          <p><strong>Deadline:</strong> <span id="detailDeadline"></span></p>
          <hr>

          <p><strong>Job Description</strong></p>
          <div id="detailDescription"></div>
          <hr>

          <p><strong>Requirements</strong></p>
          <div id="detailRequirement"></div>
          <hr>


        </div>
      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    /*var url = 'http://localhost/_hrm';*/
    var url = 'https://hrm.nathabuana.com';
    let currentView = "card"; // default

    fetch(url + "/api/api/get_career_list", { method: "POST" })
      .then(response => response.json())
      .then(result => {
        if (result.status === 200) {
          let jobs = result.data;
          renderJobs(jobs);

          document.getElementById("searchJob").addEventListener("keyup", function() {
            let q = this.value.toLowerCase();
            let filtered = jobs.filter(j => j.subject.toLowerCase().includes(q));
            renderJobs(filtered);
          });

          document.getElementById("btnCard").addEventListener("click", function() {
            currentView = "card";
            setActiveBtn("btnCard");
            renderJobs(jobs);
          });

          document.getElementById("btnList").addEventListener("click", function() {
            currentView = "list";
            setActiveBtn("btnList");
            renderJobs(jobs);
          });
        }
      });

    function setActiveBtn(id) {
      document.getElementById("btnCard").classList.remove("active","btn-primary");
      document.getElementById("btnList").classList.remove("active","btn-primary");
      document.getElementById("btnCard").classList.add("btn-outline-secondary");
      document.getElementById("btnList").classList.add("btn-outline-secondary");
      document.getElementById(id).classList.add("active","btn-primary");
      document.getElementById(id).classList.remove("btn-outline-secondary");
    }

    function renderJobs(jobs) {
      let jobList = document.getElementById("jobList");
      let counter = document.getElementById("jobCounter");
      counter.innerText = `(${jobs.length}) Available jobs`;
      jobList.innerHTML = "";

      if (currentView === "card") {
       
        jobs.forEach(job => {
          jobList.innerHTML += `
            <div class="col-md-4">
              <div class="job-card">
                <h5>${job.subject}</h5>
                <p class="text-muted">${job.divname} - ${job.job_level_name}</p>

                <div class="mb-2">
                  <p class="mb-1" ><i class="bi bi-briefcase"></i> ${capitalize(job.status_emp)}</p>
                  <p class="mb-1"><i class="bi bi-geo-alt"></i> ${capitalize(job.job_placement)}</p>
                  <p class="mb-1"><i class="bi bi-clock"></i> Apply Deadline: ${job.apply_deadline ?? '-'}</p>
                </div>
                <hr>
                
                <div class="job-footer">
                  <a href="javascript:void(0)" class="link-navy" onclick='openDetailModal(${JSON.stringify(job)})'>See Detail</a>
                  <button class="btn btn-navy btn-sm" onclick="openApplyModal('${job.id}', '${job.subject}')">Apply</button>

                </div>
              </div>
            </div>
          `;
        });
        
      } else {
        jobs.forEach(job => {
          jobList.innerHTML += `
            <div class="col-12">
              <div class="job-list-card">
                <div class="job-info">
                  <h5>${job.subject}</h5>
                  <p>${job.divname} - ${job.job_level_name}</p>
                  <p class="text-muted">
                  <i class="bi bi-briefcase"></i> ${capitalize(job.status_emp)}
                  <i class="bi bi-geo-alt"></i> ${capitalize(job.job_placement)}
                  <i class="bi bi-clock"></i> ${job.apply_deadline ?? '-'}
                  </p>
                </div>
                <div class="job-actions">
                  <a href="javascript:void(0)" class="link-navy" onclick='openDetailModal(${JSON.stringify(job)})'>See Detail</a>
                  <button class="btn btn-navy" onclick="openApplyModal('${job.id}', '${job.subject}')">Apply</button>
                </div>
              </div>
            </div>
          `;
        });
      }
    }

    function openApplyModal(jobId, position) {
      document.getElementById("job_id").value = jobId;
      document.getElementById("job_position").value = position;
      new bootstrap.Modal(document.getElementById('applyModal')).show();
    }

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


    function openDetailModal(job) {
      document.getElementById("detailTitle").innerText = job.subject;
      document.getElementById("detailDivision").innerText = job.divname;
      document.getElementById("detailLevel").innerText = job.job_level_name;
      document.getElementById("detailStatus").innerText = capitalize(job.status_emp);
      document.getElementById("detailJobplacement").innerText = capitalize(job.job_placement);
      document.getElementById("detailDeadline").innerText = job.apply_deadline ?? "-";

      // --- Format job_descriptions jadi list ---
      let desc = job.job_descriptions ? job.job_descriptions.split("|") : [];
      let descHtml = desc.map(d => `<li>${d.trim()}</li>`).join("");
      document.getElementById("detailDescription").innerHTML = `<ul>${descHtml}</ul>`;

      // --- Format requirements jadi list dengan label:value ---
      let req = job.requirements ? job.requirements.split("|") : [];
      let reqHtml = req.map(r => {
        let parts = r.split(":");
        if (parts.length > 1) {
          let label = parts[0].trim();
          let value = parts.slice(1).join(":").trim(); // gabungkan kalau ada ":" lebih dari 1
          return `<li><strong>${capitalize(label)}</strong> : ${value}</li>`;
        } else {
          return `<li>${r.trim()}</li>`;
        }
      }).join("");
      document.getElementById("detailRequirement").innerHTML = `<ul>${reqHtml}</ul>`;

      new bootstrap.Modal(document.getElementById('detailModal')).show();
    }

    function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }




  </script>
</body>
</html>
