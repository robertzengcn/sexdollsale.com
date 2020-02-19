<?php

  $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_description = 'Allow login to customer account using master password? (Must be using ZenCart v1.5.0 or higher)' WHERE configuration_key = 'FEC_MASTER_PASSWORD'");
