<?php

// This file has the sole purpose of loading all configuration files for uLogin.

function endsWith($haystack, $needle)
{
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }

  return (substr($haystack, -$length) === $needle);
}

$dirname = dirname(__FILE__);
$handle = opendir($dirname);
while (false !== ($file = readdir($handle))) {
  if (!is_dir($dirname.$file))
  
  
  if( endsWith($file, '.inc.php'))
  
    require_once($file);
}
closedir($handle);
?>
