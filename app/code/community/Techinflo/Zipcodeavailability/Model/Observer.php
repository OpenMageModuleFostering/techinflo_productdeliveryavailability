<?php
  
class Techinflo_Zipcodeavailability_Model_Observer
{
    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the 
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
		$productId = $observer->getEvent()->getProduct()->getEntity_id();
        if (!self::$_singletonFlag && $productId !="") {
            self::$_singletonFlag = true;
             
            $product = $observer->getEvent()->getProduct();
            $zipcodeState =  $this->_getRequest()->getPost('new_zipcode');
			$zipcodeCites =  $this->_getRequest()->getPost('product_zip_city');
			
			$temid = array_keys($zipcodeState);
			$total = count($zipcodeState);
			$model = Mage::getModel('zipcodeavailability/productzipcode');
			if(count($zipcodeState)>0){
				foreach($temid as $zipTem){
					
					$exitTemp = $model->getCollection()
						->addFieldToFilter('product', $product->getEntity_id())
						->addFieldToFilter('zipcode_template', $zipcodeState[$zipTem]['templateid'])
						->addFieldToSelect('productzipcode_id')
						->getFirstItem();
					$tempid = $exitTemp->getProductzipcodeId();
					if(!$tempid){
						$zipcodedata = array(
								'product'=> $product->getEntity_id(),
								'zipcode_template'=> $zipcodeState[$zipTem]['templateid'],
								'product_state'=> $zipcodeState[$zipTem]['state'],
								'product_city'=> $zipcodeState[$zipTem]['city'],
								'product_zipcode'=> $zipcodeState[$zipTem]['availablezipcode'],
								'product_zipcode_exp'=> $zipcodeState[$zipTem]['exp_zipcode'],
								'created_time' =>now()
							);
						$model->setData($zipcodedata)->save();
					}
				}
				$product->save();
			}			
        }
    }
      
    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }
     
    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
	
}
