<?php 

if (isset($_SESSION['id'])) {
    $email = $_SESSION['id'];
} else {
    session_destroy();
    header("location: ../../");
}
?>