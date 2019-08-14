<?php

class Techinflo_Zipcodeavailability_Model_Zipcodeavailability extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('zipcodeavailability/zipcodeavailability');
    }
	
}

class Techinflo_Zipcodeavailability_Model_System_Config_Source_Country extends Mage_Adminhtml_Model_System_Config_Source_Country 
{
	protected $_options;
	
	public function toOptionArray($isMultiselect=false)
	{	
		if (!$this->_options) {
            $this->_options = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray(false);
		}
		$options = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray();
			 
		return $options;
	}

}