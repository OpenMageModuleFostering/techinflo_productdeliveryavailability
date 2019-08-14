<?php

class Techinflo_Zipcodeavailability_Model_Productzipcode extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('zipcodeavailability/productzipcode');
    }
	
	public function getCollcetion()
	{
		$saleszone = Mage::getModel('zipcodeavailability/productzipcode')->getCollection()
		->addFieldToSelect('*');
	}
	
	public function getzipcodeInExcludezone($productId, $zipcode)
	{	
		// Checking zipcode in Exclude zone
		$chekZipcode = Mage::getModel('zipcodeavailability/productzipcode')->getCollection()
					->addFieldToFilter('product', $productId)
					->addFieldToFilter('product_zipcode_exp', array('like' => '%'.$zipcode.'%'))
					->addFieldToSelect('productzipcode_id')
					->getFirstItem();
		return $chekZipcode->getProductzipcodeId();
	}
	
	public function getzipcodesByProduct($productId)
	{	
		// Get all zipcodes by product id.
		$productZips = Mage::getModel('zipcodeavailability/productzipcode')->getCollection()
				->addFieldToFilter('product', $productId)
				->addFieldToSelect('product_zipcode')
				->getData();
		$productZipcodes = "";
		foreach($productZips as $pzip => $zip ){
			$productZipcodes .= $zip['product_zipcode'].", ";
		}
		return trim($productZipcodes, ", ");		
	}
	
	public function getcheckoutpage(){
		echo "Raj";
	}
}