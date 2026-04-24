<?php
session_start();
session_destroy();
header("Location: /Adoption-Application/login.php");
exit();