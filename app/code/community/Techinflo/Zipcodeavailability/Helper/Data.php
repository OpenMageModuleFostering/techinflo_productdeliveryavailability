<?php

class Techinflo_Zipcodeavailability_Helper_Data extends Mage_Core_Helper_Abstract {

	public function getZipcodetemplates()
	{
		$zipcodeTempaltes = Mage::getModel('zipcodeavailability/zipcodeavailability')->getCollection()
		->addFieldtoFilter('status', 1)
		->getData();
	//var_dump($zipcodeTempaltes);
	return $zipcodeTempaltes;
	}
}