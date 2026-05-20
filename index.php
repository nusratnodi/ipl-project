<?php
include("connection.php");

if (isset($_POST['submit'])) {
    $id              = trim($_POST['id']);
    $student_name    = trim($_POST['student_name']);
    $roll_number     = trim($_POST['roll_number']);
    $attendance_date = trim($_POST['attendance_date']);
    $status          = trim($_POST['status']);

    // EMPTY CHECK
    if (empty($student_name) || empty($roll_number) || empty($attendance_date)) {
        echo "<script>alert('All fields are required!');</script>";
    }
    // STATUS CHECK
    else if ($status != 'Present' && $status != 'Absent') {
        echo "<script>alert('Please choose Present or Absent!');</script>";
    }
    else {
        if ($id != '') {
            // UPDATE
            $sql = "UPDATE attendance SET student_name='$student_name', roll_number='$roll_number', attendance_date='$attendance_date', status='$status' WHERE id='$id'";
            $result = mysqli_query($conn, $sql);
            if ($result)
                echo "<script>alert('Attendance updated!');</script>";
            else
                echo "<script>alert('Update failed!');</script>";
        }
        else {
            // INSERT
            $sql = "INSERT INTO attendance(student_name, roll_number, attendance_date, status) VALUES('$student_name','$roll_number','$attendance_date','$status')";
            $result = mysqli_query($conn, $sql);
            if ($result)
                echo "<script>alert('Attendance recorded!');</script>";
            else
                echo "<script>alert('Save failed!');</script>";
        }
    }
}

// LOAD RECORD FOR EDIT MODE
$editRow = null;
if (isset($_GET['edit'])) {
    $editId = trim($_GET['edit']);
    $sql = "SELECT * FROM attendance WHERE id='$editId'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $editRow = mysqli_fetch_assoc($result);
    }
}

// LIST ALL RECORDS
$sql = "SELECT * FROM attendance ORDER BY attendance_date DESC, id DESC";
$result = mysqli_query($conn, $sql);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

// FORM DEFAULTS
$formId   = $editRow['id']              ?? '';
$formName = $editRow['student_name']    ?? '';
$formRoll = $editRow['roll_number']     ?? '';
$formDate = $editRow['attendance_date'] ?? date('Y-m-d');
$formStat = $editRow['status']          ?? 'Present';

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Attendance</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

  <header class="banner">
    <marquee class="marquee" behavior="scroll" direction="left">
      Student Attendance Sheet
    </marquee>
  </header>

  <main class="container">

    <section class="card js-fade-in">
      <h2><?= $editRow ? 'Edit Attendance #' . h($editRow['id']) : 'Mark Attendance' ?></h2>

      <form method="post" action="index.php" autocomplete="off" class="js-form">
        <input type="hidden" name="id" value="<?= h($formId) ?>" />

        <div class="field">
          <label for="student_name">Student name</label>
          <input
            type="text"
            id="student_name"
            name="student_name"
            required
            maxlength="150"
            value="<?= h($formName) ?>" />
        </div>

        <div class="field">
          <label for="roll_number">Roll number</label>
          <input
            type="text"
            id="roll_number"
            name="roll_number"
            required
            maxlength="50"
            value="<?= h($formRoll) ?>" />
        </div>

        <div class="field">
          <label for="attendance_date">Date</label>
          <input
            type="date"
            id="attendance_date"
            name="attendance_date"
            required
            value="<?= h($formDate) ?>" />
        </div>

        <div class="field">
          <label>Status</label>
          <div class="radio-group">
            <label class="radio">
              <input type="radio" name="status" value="Present" <?= $formStat == 'Present' ? 'checked' : '' ?> />
              <span>Present</span>
            </label>
            <label class="radio">
              <input type="radio" name="status" value="Absent" <?= $formStat == 'Absent' ? 'checked' : '' ?> />
              <span>Absent</span>
            </label>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" id="btn" value="submit" name="submit" class="btn btn-primary"><?= $editRow ? 'Update' : 'Save' ?></button>
          <?php if ($editRow): ?>
            <a href="index.php" class="btn btn-ghost">Cancel</a>
          <?php endif; ?>
        </div>
      </form>
    </section>

    <section class="card js-fade-in">
      <div class="card-header">
        <h2>Attendance Records</h2>
      </div>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Roll</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr><td colspan="6" class="empty">No attendance records yet.</td></tr>
            <?php else: foreach ($rows as $r): ?>
              <tr>
                <td><?= h($r['id']) ?></td>
                <td><?= h($r['student_name']) ?></td>
                <td><?= h($r['roll_number']) ?></td>
                <td><?= h($r['attendance_date']) ?></td>
                <td>
                  <span class="badge badge-<?= strtolower(h($r['status'])) ?>"><?= h($r['status']) ?></span>
                </td>
                <td class="actions">
                  <a href="index.php?edit=<?= h($r['id']) ?>" class="btn btn-edit">Edit</a>
                  <form method="post" action="delete.php" class="inline-form js-delete">
                    <input type="hidden" name="id" value="<?= h($r['id']) ?>" />
                    <button type="submit" name="submit" value="submit" class="btn btn-delete">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <script src="js/app.js"></script>
</body>
</html>
