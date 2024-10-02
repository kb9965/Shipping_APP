<?php
session_start();

if (isset($_SESSION['user_id'])) {
    echo 'user';
} elseif (isset($_SESSION['transporter_id'])) {
    echo 'transporter';
} else {
    echo 'unknown';
}
?>
