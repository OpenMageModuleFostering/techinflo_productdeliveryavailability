<?php
class Techinflo_Zipcodeavailability_Block_Adminhtml_Zipcodeavailability extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_zipcodeavailability';
    $this->_blockGroup = 'zipcodeavailability';
    $this->_headerText = Mage::helper('zipcodeavailability')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('zipcodeavailability')->__('Add Zipcode Template');
    parent::__construct();
  }
}