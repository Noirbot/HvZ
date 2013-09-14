<?php
setcookie ("MOD_AUTH_CAS", "", time()-3600);
header("Location: http://login.gatech.edu/cas/logout");


?>