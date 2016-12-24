<?php
$message = '';
$messageType = '';

if(isset($_SESSION['notification']['text']))
{
  $messageType = $_SESSION['notification']['type'];
  $message = $_SESSION['notification']['text'];
  unset($_SESSION['notification']);
}
if(isset($messageType))
{
  switch($messageType)
  {
    case 'error': $messageType = 'alert';
    break;
    case 'success': $messageType = 'success';
    break;
    default: $messageType = '';
  }
}
?>
