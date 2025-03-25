<?php
ini_set("log_errors", 1);
ini_set ('display_errors', 1);
error_reporting (E_ALL);
try
{   $sd = new CPSignedData();
    $content = "test content";
    $sd = new CPSignedData();
    $sd->set_Content($content);
    printf("test init OK\n");
}
catch (Exception $e)
{
    printf($e->getMessage());
}
?>
