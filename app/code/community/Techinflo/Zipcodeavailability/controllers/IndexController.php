<?php
class Techinflo_Zipcodeavailability_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {    
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/zipcodeavailability?id=15 
    	 *  or
    	 * http://site.com/zipcodeavailability/id/15 	
    	 */
    	
		$zipcodeavailability_id = $this->getRequest()->getParam('id');

  		if($zipcodeavailability_id != null && $zipcodeavailability_id != '')	{
			$zipcodeavailability = Mage::getModel('zipcodeavailability/zipcodeavailability')->load($zipcodeavailability_id)->getData();
		} else {
			$zipcodeavailability = null;
		}	
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	
    	if($zipcodeavailability == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$zipcodeavailabilityTable = $resource->getTableName('zipcodeavailability');
			
			$select = $read->select()
			   ->from($zipcodeavailabilityTable,array('zipcodeavailability_id','title','country','state','city','zipcode','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$zipcodeavailability = $read->fetchRow($select);
			
		}
		Mage::register('zipcodeavailability', $zipcodeavailability);
		
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	public function getCollection() {
        if (!$this->getData('collection')) {
            $this->setCollection(
                    Mage::getModel('zipcodeavailability/zipcodeavailability')->getCollection()                      
                       ->setOrder('id','DESC')
            );
        }
        return $this->getData('collection');
    }
	
	public function getzipcodezoneAction()
	{
		$zoneid = $this->getRequest()->getPost('zone');
		$pId = $this->getRequest()->getPost('product');
		$zone = Mage::getModel('zipcodeavailability/zipcodeavailability')->getCollection()
		->addFieldToFilter('zipcodeavailability_id', $zoneid);
		$addTemplate = "";
		$addzone = $zone->getData();
		foreach($addzone as $newzone){
			$tempId = $newzone['zipcodeavailability_id'];
			$addTemplate .= "<tr id='new_temp_".$tempId."'>
							<td>
							<input type='hidden' name='new_zipcode[".$tempId."][templateid]' value='". $tempId. "'/>
							<input type='text' name='new_zipcode[".$tempId."][state]' value='". $newzone['state']. "'/></td>
							<td><input type='text' name='new_zipcode[".$tempId."][city]' value='". $newzone['city']. "'/></td>
							<td><textarea name='new_zipcode[".$tempId."][availablezipcode]'>". $newzone['zipcode_range']. "</textarea></td>
							<td><textarea name='new_zipcode[".$tempId."][exp_zipcode]'  >". $newzone['zipcode_excerpt']. "</textarea></td>
							<td><button onclick='removetemplate(".$tempId.")' value=". $tempId ."> Remove</button></td>
							</tr>";
		}
		echo $addTemplate;
	}
	
	public function removeproductzipzoneAction()
	{
		$zoneid = $this->getRequest()->getPost('zoneid');
		$pId = $this->getRequest()->getPost('product');		
		// Delete product zipcode Template.
		$zone = Mage::getModel('zipcodeavailability/productzipcode')->getCollection()
			->addFieldToFilter('product', $pId)
			->addFieldToFilter('zipcode_template', $zoneid)
			->addFieldToSelect('productzipcode_id')
			->getFirstItem();
			
		$removeId = $zone->getProductzipcodeId(); // remove productzipcode_id 
		
		$model = Mage::getModel('zipcodeavailability/productzipcode');
		try {
			$model->setId($removeId)->delete();
			echo "Data deleted successfully.";

		} catch (Exception $e){
			echo $e->getMessage(); 
		}
	}
	
	public function updateproductzipzoneAction()
	{
		$zoneid = $this->getRequest()->getPost('zoneid');		
		$pId = $this->getRequest()->getPost('product');
		
		$city = $this->getRequest()->getPost('city');
		$state = $this->getRequest()->getPost('ustate');
		$zipcode = $this->getRequest()->getPost('upzipcode');
		$exp_zip = $this->getRequest()->getPost('upexpzip');
		// Update product zipcode Template.
		$model = Mage::getModel('zipcodeavailability/productzipcode');
		$zone = $model->getCollection()
			->addFieldToFilter('product', $pId)
			->addFieldToFilter('zipcode_template', $zoneid)
			->addFieldToSelect('productzipcode_id')
			->getFirstItem();
		$UpdateId = $zone->getProductzipcodeId();	// Update productzipcode_id 	
	
		$zipcodedata = array(
							'product_state'=> $state,
							'product_city'=> $city,
							'product_zipcode'=> $zipcode,
							'product_zipcode_exp'=> $exp_zip,
							'update_time' => now()
						);
			
		$model->setData($zipcodedata)
			->setId($UpdateId);
		$model->save();
		$updatedVal = $model->load($UpdateId)->getData();		
		echo implode("#", $updatedVal);
	}
	
	public function pdpAction()
	{
		$productId = $this->getRequest()->getPost('productid');
		$zipcode = $this->getRequest()->getPost('zipcode');		
		$zipcodeavaiableModel = Mage::getModel('zipcodeavailability/productzipcode'); // Zipcodeavailability Productzipcode model
		$isexcludezip = $zipcodeavaiableModel->getzipcodeInExcludezone($productId, $zipcode);
		$responseResult = "0";
		if(!$isexcludezip){
			$productZipcodes = $zipcodeavaiableModel->getzipcodesByProduct($productId);
			if($productZipcodes !=""){
				$pattern = "/".$zipcode."/"; // cheking zipcode is normal or its in range.
				$zipcodefound = preg_match_all($pattern, $productZipcodes, $results);
				if($zipcodefound){
					$responseResult = "1";
				}else
				{
					$spltBysZone = explode("~", $productZipcodes);
					foreach($spltBysZone as $szone){
						$splyBycitzone = explode(",", $szone);						
						foreach($splyBycitzone as $czone){
							$spltByRangezip = explode("-", $czone);
							$min = $spltByRangezip['0']; 
							$max = $spltByRangezip['1'];
							$zipStatus = filter_var(		// Checking zipcode is available in zipcode between Range values
									$zipcode,
									FILTER_VALIDATE_INT, 
									array(
										'options' => array(
											'min_range' => $min, 
											'max_range' => $max
											)
										)
									);
							if($zipStatus == $zipcode){
								$responseResult = "1";
								break;
							}
						} // End of splt By City Zone
					} // End of splt By Zone
					//if($responseResult =="") $responseResult = "-1";
				}
			}
		}
		echo $responseResult;
	}
	
	public function cartpageavailableAction()
	{  
		$productId = $this->getRequest()->getPost('productid');		
		$zipcode = $this->getRequest()->getPost('zipcode');		
		$zipcodeavaiableModel = Mage::getModel('zipcodeavailability/productzipcode'); // Zipcodeavailability Productzipcode model
		$cartItemIds = unserialize($productId);
		$responseResult = array("status"=> 0);
		$count = 0;
		$totalProducts = count($cartItemIds);
		foreach($cartItemIds as $pId){
			$isexcludezip = $zipcodeavaiableModel->getzipcodeInExcludezone($pId,$zipcode);
			if(!$isexcludezip){
				$productZipcodes = $zipcodeavaiableModel->getzipcodesByProduct($pId);	
			
				if($productZipcodes !=""){
					$pattern = "/".$zipcode."/"; // cheking zipcode is normal or its in range.
					$zipcodefound = preg_match_all($pattern, $productZipcodes, $results);
					if($zipcodefound){
						$responseResult['item'][$pId] = "1";
						$count++;
					}else
					{
						$spltBysZone = explode("~", $productZipcodes);
						foreach($spltBysZone as $szone){
							$splyBycitzone = explode(",", $szone);						
							foreach($splyBycitzone as $czone){
								$spltByRangezip = explode("-", $czone);
								$min = $spltByRangezip['0']; 
								$max = $spltByRangezip['1'];
								$zipStatus = filter_var(// Checking zipcode is available in zipcode between Range values
										$zipcode,
										FILTER_VALIDATE_INT, 
										array(
											'options' => array(
												'min_range' => $min, 
												'max_range' => $max
												)
											)
										);
								if($zipStatus == $zipcode){
									$responseResult['item'][$pId] = "1";
									$count++;
									break;
								}else {
									$responseResult['item'][$pId] = "0";
								}
							} // End of splt By City Zone
						} // End of splt By Zone
					}
				}else {
					$responseResult['item'][$pId] = "0";
				}
			}else { $responseResult['item'][$pId] = "0"; $unavalbile++; }
		}
		if($count >0 && $totalProducts == $count ){
			$responseResult['status'] = $count;
		}
		print_r(json_encode($responseResult));	
	}	
}