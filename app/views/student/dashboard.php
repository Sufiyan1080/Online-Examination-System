<section>
  <h2>Student Module</h2>
  <div class="card">
    <h3>Available Exams</h3>
    <table><tr><th>Title</th><th>Subject</th><th>Schedule</th><th>Status</th><th>Action</th><th>Result</th></tr>
      <?php foreach ($assignments as $a): ?>
        <?php $result = result_for_assignment((int)$a['assignment_id']); ?>
        <tr>
          <td><?= htmlspecialchars($a['title']) ?></td>
          <td><?= htmlspecialchars($a['subject']) ?></td>
          <td><?= htmlspecialchars($a['schedule_at']) ?></td>
          <td><?= htmlspecialchars($a['assignment_status']) ?></td>
          <td>
            <?php if ($a['assignment_status'] !== 'submitted'): ?>
              <a href="index.php?page=student-attempt&assignment_id=<?= $a['assignment_id'] ?>">Attempt</a>
            <?php else: ?>Completed<?php endif; ?>
          </td>
          <td><?= $result ? $result['score'] : '-' ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</section>
