<?php

class Techinflo_Zipcodeavailability_Adminhtml_ZipcodeavailabilityController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('zipcodeavailability/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('zipcodeavailability/zipcodeavailability')->load($id);

		$stateCollection = Mage::getModel('directory/region')->getResourceCollection()->addCountryFilter('IN')->load();

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('zipcodeavailability_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('zipcodeavailability/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('zipcodeavailability/adminhtml_zipcodeavailability_edit'))
				->_addLeft($this->getLayout()->createBlock('zipcodeavailability/adminhtml_zipcodeavailability_edit_tabs'));

			$this->renderLayout();
		} else {
		
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zipcodeavailability')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 	
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {

            if ($data = $this->getRequest()->getPost()) {
			 
			$id = $this->getRequest()->getParam('id');
			echo $id;
			
			if (!$id) {
                $collection = Mage::getModel('zipcodeavailability/zipcodeavailability')->getCollection()
                            ->addFieldToFilter('city', $data['city'])
                            ->addFieldToFilter('state', $data['state'])
                            ->getFirstItem()
                            ->getTitle();			
                if ($collection) {
                    Mage::getSingleton('adminhtml/session')->addError("Zipcode Range is already exit in <b>" . uc_words($collection) ."</b> Template so Please check and Update!");
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
			}
			
			$model = Mage::getModel('zipcodeavailability/zipcodeavailability');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zipcodeavailability')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zipcodeavailability')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('zipcodeavailability/zipcodeavailability');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $zipcodeavailabilityIds = $this->getRequest()->getParam('zipcodeavailability');
        if(!is_array($zipcodeavailabilityIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($zipcodeavailabilityIds as $zipcodeavailabilityId) {
                    $zipcodeavailability = Mage::getModel('zipcodeavailability/zipcodeavailability')->load($zipcodeavailabilityId);
                    $zipcodeavailability->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($zipcodeavailabilityIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $zipcodeavailabilityIds = $this->getRequest()->getParam('zipcodeavailability');
        if(!is_array($zipcodeavailabilityIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($zipcodeavailabilityIds as $zipcodeavailabilityId) {
                    $zipcodeavailability = Mage::getSingleton('zipcodeavailability/zipcodeavailability')
                        ->load($zipcodeavailabilityId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($zipcodeavailabilityIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'zipcodeavailability.csv';
        $content    = $this->getLayout()->createBlock('zipcodeavailability/adminhtml_zipcodeavailability_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'zipcodeavailability.xml';
        $content    = $this->getLayout()->createBlock('zipcodeavailability/adminhtml_zipcodeavailability_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
	
	
    public function stateAction() {
        $countrycode = $this->getRequest()->getParam('country');
        $xmlstates = Mage::helper('zipcodeavailability')->statesbycountry();       
        
        $state = "<option value=''>--Please Select State--</option>";
        foreach ($xmlstates as $sate) {           
            $state .= "<option value='" . $sate . "'>" . $sate . "</option>";
        }
        echo $state;
    }

    public function citiesAction($statCode) {
	$stateCode = $this->getRequest()->getParam('state');
	$xmlcities = Mage::helper('zipcodeavailability')->citybystate();
		
	$allcity = "<option value=''>--Please Select City--</option>";
	foreach ($xmlcities as $states => $state) {
            if ($stateCode == $states) {
                foreach ($xmlcities[$stateCode] as $city) {
                    $cityName = $city['cname'];
                    $allcity .= "<option value='" . $cityName . "'>" . $cityName . "</option>";
                }
            }
        }
        echo $allcity;		
    }
	
    public function cityzipcodesAction ()
	{
		$cityCode = $this->getRequest()->getParam('city');		
		$xmlzipcodes = Mage::helper('zipcodeavailability')->zipcodebycity($cityCode);	// Get all cities from xml data	
		
		$zipocdes = "";
                foreach ($xmlzipcodes as $zipcodes){
                    $total = count($zipcodes);
                    if($total >0){
                        for($i=0; $i<$total; $i++){
                            $zipocdes .= "<option value='" . $zipcodes[$i] ."'>" . $zipcodes[$i] . "</option>";
                        }
                    }                    
                }
		echo $zipocdes;
	}
	
	public function countryzoneAction()
	{
		$data = $this->getRequest()->getPost();		
		$zone = Mage::getModel('zipcodeavailability/zipcodeavailability')->load($data['seller']);
		$salesarea = $zone->getData()['zipcode'];
		if($salesarea) {
			$zipcodes = explode(",", $salesarea);
			$zipcode = "<option value=''>-- Please Select --</option>";
			foreach ($zipcodes as $zip) {
				$zipcode .= "<option value='" . $zip ."'>" . $zip . "</option>";
			}
			echo $zipcode;
		}
		
	}
	
	public function getOLDzipcodezoneAction()
	{
		$zoneid = $this->getRequest()->getPost('zone');
		$pId = $this->getRequest()->getPost('product');
		$zone = Mage::getModel('zipcodeavailability/zipcodeavailability')->getCollection()
		->addFieldToFilter('zipcodeavailability_id', $zoneid);
		$addTemplate = "";
		var_dump($zone); die;
		$addzone = $zone->getData();
		foreach($addzone as $newzone){
			$addTemplate .= "<tr>
							<td><input type='text' name='product_zip_state[]' value='". $newzone['state']. "'/></td>
							<td><input type='text' name='product_zip_city[]' value='". $newzone['city']. "'/></td>
							<td><textarea name='product_zip_range[]'>". $newzone['zipcode_range']. "</textarea></td>
							<td><input type='text' name='product_zip_exp[]' value='". $newzone['zipcode_excerpt']. "'/></td>
							</tr>";
		}
		echo $addTemplate;
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
							<input type='text' name='new_zipcode[".$tempId."][templateid]' value='". $tempId. "'/>
							<input type='text' name='new_zipcode[".$tempId."][state]' value='". $newzone['state']. "'/></td>
							<td><input type='text' name='new_zipcode[".$tempId."][city]' value='". $newzone['city']. "'/></td>
							<td><textarea name='new_zipcode[".$tempId."][availablezipcode]'>". $newzone['zipcode_range']. "</textarea></td>
							<td><textarea name='new_zipcode[".$tempId."][exp_zipcode]'  >". $newzone['zipcode_excerpt']. "</textarea></td>
							<td><button onclick='removetemplate(".$tempId.")' value=". $tempId ."> Remove</button></td>
							</tr>";
		}
		echo $addTemplate;
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
}