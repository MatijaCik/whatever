<?php

$lozinka = "admin123";
$hash = password_hash($lozinka, PASSWORD_DEFAULT);
echo $hash;
?>