<?php

class Techinflo_Zipcodeavailability_Model_Mysql4_Zipcodeavailability_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('zipcodeavailability/zipcodeavailability');
    }
}