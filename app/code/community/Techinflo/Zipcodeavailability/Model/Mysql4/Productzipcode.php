<?php

class Techinflo_Zipcodeavailability_Model_Mysql4_Productzipcode extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the zone_id refers to the key field in your database table.
        $this->_init('zipcodeavailability/productzipcode', 'productzipcode_id');
    }
}