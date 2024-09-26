<?php

setcookie('user_logged_in', '', time() - 3600); 

header("Location: index.php"); 
exit();
?>