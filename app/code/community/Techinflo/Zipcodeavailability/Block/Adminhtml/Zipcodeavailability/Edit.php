<?php

class Techinflo_Zipcodeavailability_Block_Adminhtml_Zipcodeavailability_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'zipcodeavailability';
        $this->_controller = 'adminhtml_zipcodeavailability';
        
        $this->_updateButton('save', 'label', Mage::helper('zipcodeavailability')->__('Save Zipcode Template'));
        $this->_updateButton('delete', 'label', Mage::helper('zipcodeavailability')->__('Delete Zipcode Template'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('zipcodeavailability_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'zipcodeavailability_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'zipcodeavailability_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
		
		  
    }

    public function getHeaderText()
    {
        if( Mage::registry('zipcodeavailability_data') && Mage::registry('zipcodeavailability_data')->getId() ) {
            return Mage::helper('zipcodeavailability')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('zipcodeavailability_data')->getTitle()));
        } else {
            return Mage::helper('zipcodeavailability')->__('Add Item');
        }
    }
}