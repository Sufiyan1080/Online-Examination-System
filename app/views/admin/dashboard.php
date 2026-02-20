<section>
  <h2>Admin Module</h2>
  <div class="grid two">
    <div class="card">
      <h3>Manage Students (CRUD)</h3>
      <form method="post" action="index.php?page=admin-save-student" class="form compact">
        <input name="full_name" placeholder="Full name" required>
        <input name="email" placeholder="Email" required>
        <input name="password" placeholder="Password">
        <button>Add Student</button>
      </form>
      <table><tr><th>Name</th><th>Email</th><th>Action</th></tr>
        <?php foreach ($students as $s): ?>
          <tr><td><?= htmlspecialchars($s['full_name']) ?></td><td><?= htmlspecialchars($s['email']) ?></td><td>
          <form method="post" action="index.php?page=admin-delete-user"><input type="hidden" name="id" value="<?= $s['id'] ?>"><button>Delete</button></form>
          </td></tr>
        <?php endforeach; ?>
      </table>
    </div>

    <div class="card">
      <h3>Manage Teachers (CRUD)</h3>
      <form method="post" action="index.php?page=admin-save-teacher" class="form compact">
        <input name="full_name" placeholder="Full name" required>
        <input name="email" placeholder="Email" required>
        <input name="password" placeholder="Password">
        <button>Add Teacher</button>
      </form>
      <table><tr><th>Name</th><th>Email</th></tr>
        <?php foreach ($teachers as $t): ?>
          <tr><td><?= htmlspecialchars($t['full_name']) ?></td><td><?= htmlspecialchars($t['email']) ?></td></tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <div class="grid two">
    <div class="card">
      <h3>Create Exams</h3>
      <form method="post" action="index.php?page=admin-save-exam" class="form compact">
        <input name="title" placeholder="Exam title" required>
        <input name="subject" placeholder="Subject" required>
        <input name="topic" placeholder="Topic" required>
        <input name="duration_minutes" placeholder="Duration minutes" type="number" required>
        <input name="total_marks" placeholder="Total marks" type="number" required>
        <select name="teacher_id" required>
          <option value="">Assign Teacher</option>
          <?php foreach ($teachers as $t): ?><option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['full_name']) ?></option><?php endforeach; ?>
        </select>
        <button>Create Exam</button>
      </form>
      <ul><?php foreach ($exams as $e): ?><li><?= htmlspecialchars($e['title']) ?> (<?= htmlspecialchars($e['subject']) ?>)</li><?php endforeach; ?></ul>
    </div>

    <div class="card">
      <h3>Assign Exams to Students</h3>
      <form method="post" action="index.php?page=admin-assign-exam" class="form compact">
        <select name="exam_id" required><option value="">Exam</option><?php foreach ($exams as $e): ?><option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['title']) ?></option><?php endforeach; ?></select>
        <select name="student_id" required><option value="">Student</option><?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['full_name']) ?></option><?php endforeach; ?></select>
        <input type="datetime-local" name="schedule_at" required>
        <button>Assign</button>
      </form>
      <ul><?php foreach ($assignments as $a): ?><li>Assignment #<?= $a['id'] ?> | exam <?= $a['exam_id'] ?> -> student <?= $a['student_id'] ?> (<?= $a['status'] ?>)</li><?php endforeach; ?></ul>
    </div>
  </div>

  <div class="grid two">
    <div class="card">
      <h3>View Results & Reports</h3>
      <table><tr><th>Student</th><th>Exam</th><th>Score</th><th>Date</th></tr>
      <?php foreach ($reports as $r): ?>
        <tr><td><?= htmlspecialchars($r['student_name']) ?></td><td><?= htmlspecialchars($r['exam_title']) ?></td><td><?= $r['score'] ?>/<?= $r['total_marks'] ?></td><td><?= $r['submitted_at'] ?></td></tr>
      <?php endforeach; ?>
      </table>
    </div>

    <div class="card">
      <h3>System Monitoring (attendance/anti-cheat)</h3>
      <table><tr><th>User</th><th>Event</th><th>Details</th><th>At</th></tr>
      <?php foreach ($logs as $l): ?>
        <tr><td><?= htmlspecialchars($l['full_name']) ?></td><td><?= htmlspecialchars($l['event_type']) ?></td><td><?= htmlspecialchars($l['details']) ?></td><td><?= $l['created_at'] ?></td></tr>
      <?php endforeach; ?>
      </table>
    </div>
  </div>
</section>
