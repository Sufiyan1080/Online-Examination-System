<?php if (!$assignment || !$exam): ?>
  <p>Invalid assignment.</p>
<?php else: ?>
<section class="card">
  <h2>Attempt Exam: <?= htmlspecialchars($exam['title']) ?></h2>
  <p>Duration: <span id="timer" data-minutes="<?= (int)$exam['duration_minutes'] ?>"></span> minutes remaining</p>
  <form id="examForm" method="post" action="index.php?page=student-submit-exam" class="form">
    <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
    <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
    <?php foreach ($questions as $q): ?>
      <div class="card">
        <p><strong><?= htmlspecialchars($q['question_text']) ?></strong> (<?= $q['marks'] ?> marks)</p>
        <?php if ($q['question_type'] === 'True/False'): ?>
          <label><input type="radio" name="answer_<?= $q['id'] ?>" value="True"> True</label>
          <label><input type="radio" name="answer_<?= $q['id'] ?>" value="False"> False</label>
        <?php else: ?>
          <label><input type="radio" name="answer_<?= $q['id'] ?>" value="<?= htmlspecialchars($q['option_a']) ?>"> <?= htmlspecialchars($q['option_a']) ?></label>
          <label><input type="radio" name="answer_<?= $q['id'] ?>" value="<?= htmlspecialchars($q['option_b']) ?>"> <?= htmlspecialchars($q['option_b']) ?></label>
          <label><input type="radio" name="answer_<?= $q['id'] ?>" value="<?= htmlspecialchars($q['option_c']) ?>"> <?= htmlspecialchars($q['option_c']) ?></label>
          <label><input type="radio" name="answer_<?= $q['id'] ?>" value="<?= htmlspecialchars($q['option_d']) ?>"> <?= htmlspecialchars($q['option_d']) ?></label>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <button type="submit">Submit Exam</button>
  </form>
</section>
<?php endif; ?>
