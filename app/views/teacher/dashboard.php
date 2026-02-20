<section>
  <h2>Teacher / Examiner Module</h2>
  <div class="grid two">
    <div class="card">
      <h3>Create Question Papers + Set Exam Parameters</h3>
      <form method="post" action="index.php?page=teacher-save-exam" class="form compact">
        <input name="title" placeholder="Exam title" required>
        <input name="subject" placeholder="Subject" required>
        <input name="topic" placeholder="Topic" required>
        <input type="number" name="duration_minutes" placeholder="Duration minutes" required>
        <input type="number" name="total_marks" placeholder="Total marks" required>
        <button>Create Exam</button>
      </form>
      <ul>
        <?php foreach ($exams as $exam): ?>
          <li>#<?= $exam['id'] ?> <?= htmlspecialchars($exam['title']) ?> | <?= $exam['duration_minutes'] ?> min | <?= $exam['total_marks'] ?> marks</li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="card">
      <h3>Add Questions (MCQ / True-False)</h3>
      <form method="post" action="index.php?page=teacher-save-question" class="form compact">
        <select name="exam_id" required>
          <option value="">Select exam</option>
          <?php foreach ($exams as $exam): ?><option value="<?= $exam['id'] ?>"><?= htmlspecialchars($exam['title']) ?></option><?php endforeach; ?>
        </select>
        <textarea name="question_text" placeholder="Question" required></textarea>
        <select name="question_type"><option>MCQ</option><option>True/False</option></select>
        <input name="option_a" placeholder="Option A">
        <input name="option_b" placeholder="Option B">
        <input name="option_c" placeholder="Option C">
        <input name="option_d" placeholder="Option D">
        <input name="correct_answer" placeholder="Correct answer" required>
        <input type="number" name="marks" placeholder="Marks" required>
        <button>Add Question</button>
      </form>
    </div>
  </div>

  <div class="card">
    <h3>View Student Performance</h3>
    <table><tr><th>Student</th><th>Exam</th><th>Score</th><th>Submitted At</th></tr>
      <?php foreach ($performance as $row): ?>
        <tr><td><?= htmlspecialchars($row['student_name']) ?></td><td><?= htmlspecialchars($row['exam_title']) ?></td><td><?= $row['score'] ?>/<?= $row['total_marks'] ?></td><td><?= $row['submitted_at'] ?></td></tr>
      <?php endforeach; ?>
    </table>
  </div>
</section>
