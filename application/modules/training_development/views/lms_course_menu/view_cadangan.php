<div class="lms-wrap">

  <!-- Header -->
  <div class="lms-header">
    <div>
      <h1 class="lms-title">My Learning Dashboard</h1>
      <p class="lms-subtitle">Continue your learning journey</p>
    </div>

    <div class="lms-actions">
      <button class="lms-btn lms-btn-ghost">
        <span class="lms-icon">☑</span> Select
      </button>
      <button class="lms-btn lms-btn-primary">
        <span class="lms-icon">＋</span> Add Course
      </button>
    </div>
  </div>

  <!-- Summary -->
  <div class="lms-summary">
    <div class="sum-card sum-blue">
      <div class="sum-icon">📘</div>
      <div>
        <div class="sum-label">Total Courses</div>
        <div class="sum-value"><?= (int)($total_courses ?? 0) ?></div>
      </div>
    </div>

    <div class="sum-card sum-orange">
      <div class="sum-icon">📈</div>
      <div>
        <div class="sum-label">In Progress</div>
        <div class="sum-value"><?= (int)($in_progress ?? 0) ?></div>
      </div>
    </div>

    <div class="sum-card sum-green">
      <div class="sum-icon">🎓</div>
      <div>
        <div class="sum-label">Completed</div>
        <div class="sum-value"><?= (int)($completed ?? 0) ?></div>
      </div>
    </div>
  </div>

  <!-- Search -->
  <div class="lms-search">
    <span class="lms-search-icon">🔎</span>
    <input type="text" class="lms-search-input" placeholder="Search courses, instructors..." />
  </div>

  <!-- Filter pills -->
  <div class="lms-filter">
    <div class="lms-filter-label">Filter by category:</div>
    <div class="lms-pills">
      <button class="pill active" data-cat="all">All Courses</button>
      <button class="pill" data-cat="development">Development</button>
      <button class="pill" data-cat="design">Design</button>
      <button class="pill" data-cat="data-science">Data Science</button>
      <button class="pill" data-cat="marketing">Marketing</button>
    </div>
  </div>

  <!-- Course Grid -->
  <div class="lms-grid">
    <?php if (!empty($courses)): ?>
      <?php foreach ($courses as $c): 
        // contoh mapping status
        $status = strtolower($c['status'] ?? 'not started'); // in progress|completed|not started
        $progress = (int)($c['progress'] ?? 0); // 0..100
        $cat = strtolower($c['category_slug'] ?? 'all');
      ?>
      <div class="course-card" data-cat="<?= htmlspecialchars($cat) ?>">
        <div class="course-cover">
          <img src="<?= htmlspecialchars($c['thumbnail'] ?? 'https://via.placeholder.com/800x450') ?>" alt="">
          <span class="badge-lms <?= $status ?>">
            <?= htmlspecialchars(ucwords($status)) ?>
          </span>
        </div>

        <div class="course-body">
          <h3 class="course-title"><?= htmlspecialchars($c['title'] ?? '-') ?></h3>

          <div class="course-meta">
            <div class="meta-item">👤 <?= htmlspecialchars($c['instructor'] ?? '-') ?></div>
            <div class="meta-item">⏱ <?= htmlspecialchars($c['duration'] ?? '-') ?></div>
          </div>

          <div class="course-progress">
            <div class="progress-label">
              <span>Progress</span>
              <span><?= $progress ?>%</span>
            </div>
            <div class="progress-track">
              <div class="progress-bar" style="width: <?= $progress ?>%"></div>
            </div>
          </div>

          <div class="course-footer">
            <?php if ($status === 'completed'): ?>
              <button class="lms-btn lms-btn-ghost full">View Certificate</button>
            <?php elseif ($status === 'in progress'): ?>
              <button class="lms-btn lms-btn-primary full">Continue</button>
            <?php else: ?>
              <button class="lms-btn lms-btn-dark full">Start Course</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="lms-empty">
        <div class="lms-empty-title">No courses yet</div>
        <div class="lms-empty-sub">Add a course to start learning.</div>
      </div>
    <?php endif; ?>
  </div>

</div>

<script>
  // filter pill simple (tanpa library)
  (function(){
    const pills = document.querySelectorAll('.pill');
    const cards = document.querySelectorAll('.course-card');

    pills.forEach(p => p.addEventListener('click', () => {
      pills.forEach(x => x.classList.remove('active'));
      p.classList.add('active');

      const cat = p.getAttribute('data-cat');
      cards.forEach(card => {
        const cc = card.getAttribute('data-cat') || 'all';
        card.style.display = (cat === 'all' || cc === cat) ? '' : 'none';
      });
    }));
  })();
</script>
