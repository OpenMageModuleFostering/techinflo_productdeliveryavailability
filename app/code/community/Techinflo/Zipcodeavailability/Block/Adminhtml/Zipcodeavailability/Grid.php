<?php

class Techinflo_Zipcodeavailability_Block_Adminhtml_Zipcodeavailability_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('zipcodeavailabilityGrid');
      $this->setDefaultSort('zipcodeavailability_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('zipcodeavailability/zipcodeavailability')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('zipcodeavailability_id', array(
          'header'    => Mage::helper('zipcodeavailability')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'zipcodeavailability_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('zipcodeavailability')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

	  $this->addColumn('state', array(
			'header'    => Mage::helper('zipcodeavailability')->__('State'),
			'width'     => '150px',
			'index'     => 'state',
      ));
	  $this->addColumn('city', array(
			'header'    => Mage::helper('zipcodeavailability')->__('City'),
			'width'     => '150px',
			'index'     => 'city',
      ));
	  
      $this->addColumn('status', array(
          'header'    => Mage::helper('zipcodeavailability')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('zipcodeavailability')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('zipcodeavailability')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		
		
		$this->addExportType('*/*/exportCsv', Mage::helper('zipcodeavailability')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('zipcodeavailability')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('zipcodeavailability_id');
        $this->getMassactionBlock()->setFormFieldName('zipcodeavailability');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('zipcodeavailability')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('zipcodeavailability')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('zipcodeavailability/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('zipcodeavailability')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('zipcodeavailability')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}