<?php
// workaround for Suhosin
//if (extension_loaded('suhosin') || (defined('SUHOSIN_PATCH') && constant('SUHOSIN_PATCH'))) {
//    $_GET["module"] = $_REQUEST["module"];
//}

if (isset($_REQUEST["module"])) {
    $_GET["module"] = $_REQUEST["module"];
}

?>
