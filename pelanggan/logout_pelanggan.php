<?php
// admin/logout.php
session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_nama']);
session_destroy();
header("Location: http://localhost/taking-order-salsabila/admin/login.php");