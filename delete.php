<?php
include("connection.php");

if (isset($_POST['submit'])) {
    $id = trim($_POST['id']);

    if (empty($id)) {
        echo "<script>alert('Invalid record id!'); window.location.href='index.php';</script>";
        exit();
    }

    $sql = "DELETE FROM attendance WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_affected_rows($conn) > 0)
        echo "<script>alert('Record deleted!'); window.location.href='index.php';</script>";
    else
        echo "<script>alert('Delete failed!'); window.location.href='index.php';</script>";
}
else {
    header('Location: index.php');
    exit();
}
?>
