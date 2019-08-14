<?php

class Techinflo_Zipcodeavailability_Block_Adminhtml_Zipcodeavailability_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('zipcodeavailability_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('zipcodeavailability')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('zipcodeavailability')->__('Item Information'),
          'title'     => Mage::helper('zipcodeavailability')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('zipcodeavailability/adminhtml_zipcodeavailability_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}