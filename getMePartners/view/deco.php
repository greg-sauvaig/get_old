<?php
if (isset($_COOKIE['getMePartners'])) {
    unset($_COOKIE['getMePartners']);
    setcookie('getMePartners', '', time() - 360000, '/'); // empty value and old timestamp
}
header("location: ../index.php");
?>