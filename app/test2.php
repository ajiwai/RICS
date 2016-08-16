<?php
require 'init.php';

require APP_ROOT_DIR . '/helper/ViewHelper.php';

$helper = new ViewHelper();

$tdStyle = 'xxxxxx';
$tdStyle = ViewHelper::getCountTdStyle(2, 2);
echo $tdStyle;
echo '\n';

