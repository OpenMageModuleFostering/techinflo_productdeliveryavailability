<?php

class Techinflo_Zipcodeavailability_Block_Adminhtml_Zipcodeavailability_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('zipcodeavailability_form', array('legend' => Mage::helper('zipcodeavailability')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('zipcodeavailability')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('zipcodeavailability/zipcodeavailability')->load($id);
		$data = $model->getData();
		$sstate = $data['state'];
		$scity = $data['city'];

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('zipcodeavailability')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('zipcodeavailability')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('zipcodeavailability')->__('Disabled'),
                ),
            ),
        ));
        
        $country = $fieldset->addField('country', 'select', array(
            'name' => 'country',
            'label' => Mage::helper('zipcodeavailability')->__('Country'),
            'value' => 'IN',
            'values' => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
            'class' => 'required-entry',
            'required' => true,
            'onchange' => 'getstate(this)',
        ));
		
		$fieldset->addField('state', 'text', array(
            'name' => 'state',
            'label' => Mage::helper('zipcodeavailability')->__('State'),
            'class' => 'required-entry',
            'required' => true,            
        ));
		
		$fieldset->addField('city', 'text', array(
            'name' => 'city',
            'label' => Mage::helper('zipcodeavailability')->__('City'),
            'class' => 'required-entry',
            'required' => true,            
        ));
		
		$rangeCommands = " Eg: (560001-560050, 560055, 560057, 560100, etc..)";
		$fieldset->addField('zipcode_range', 'text', array(
          'label'   => Mage::helper('zipcodeavailability')->__('Available Zipcode'),
          'name'    => 'zipcode_range',
          'class'     => 'required-entry',
          'required'  => true,
          'after_element_html' => $rangeCommands,
      ));
      
      $excerptCommands = "<b>Entered zipcodes are Exception from Available zipcode (560003, 560007,560045).</b>";
      $fieldset->addField('zipcode_excerpt', 'text', array(
          'label' => Mage::helper('zipcodeavailability')->__('Excluded zipcodes'),
          'name' => 'zipcode_excerpt',
          'after_element_html' => $excerptCommands
      ));
       

        if (Mage::getSingleton('adminhtml/session')->getZipcodeavailabilityData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getZipcodeavailabilityData());
            Mage::getSingleton('adminhtml/session')->setZipcodeavailabilityData(null);
        } elseif (Mage::registry('zipcodeavailability_data')) {
            $form->setValues(Mage::registry('zipcodeavailability_data')->getData());
        }
        return parent::_prepareForm();
    }

}
