<?php
// nothing to see here, this page has nothing to do except it's used by a github action to sync the server every new push so it's always in sync with the git repo
$ex = shell_exec("git pull");
//$ex = shell_exec("id; whoami");
echo($ex);
?>
