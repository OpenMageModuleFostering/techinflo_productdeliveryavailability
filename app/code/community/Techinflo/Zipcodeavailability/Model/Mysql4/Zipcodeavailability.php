<?php

class Techinflo_Zipcodeavailability_Model_Mysql4_Zipcodeavailability extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the zipcodeavailability_id refers to the key field in your database table.
        $this->_init('zipcodeavailability/zipcodeavailability', 'zipcodeavailability_id');
    }
}