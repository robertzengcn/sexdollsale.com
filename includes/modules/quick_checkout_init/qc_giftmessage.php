<?php
  if (FEC_GIFT_MESSAGE == 'true') {
    if (isset($_POST['gift-message'])) $_SESSION['gift-message'] = zen_db_prepare_input($_POST['gift-message']);
    $gift_message = $_SESSION['gift-message'];
  }
?>