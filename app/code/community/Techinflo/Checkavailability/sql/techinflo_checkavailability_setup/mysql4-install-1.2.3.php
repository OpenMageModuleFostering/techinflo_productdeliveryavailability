<?php
 
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Techinflo   
 * @package     Techinflo_Checkavailability
 * @copyright   Techinflo(www.techinflo.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 $installer = $this;
    /* @var $installer Mage_Eav_Model_Entity_Setup */

    $installer->startSetup();

        $data= array (
            'attribute_set'=>'Default',
					'group'=>'Techinflo Check Availability',
					'type'=>'varchar',
					'input'=>'textarea',
					'label'=>'Vendor Availability',
					'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
					'required'=>false,
					'visible' => true,
					'system' => false,
					'user_defined' => 1
        );

        $installer->addAttribute('catalog_product','vendor_availability',$data);

        $installer->endSetup();