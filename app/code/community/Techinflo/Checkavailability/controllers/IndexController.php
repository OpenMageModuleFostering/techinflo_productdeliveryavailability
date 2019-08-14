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
class Techinflo_Checkavailability_IndexController extends Mage_Core_Controller_Front_Action {
  
    function detailAction(){
      $param= $this->getRequest()->getParams();
        $pid=$param['id'];
       $pincode=$param['pincode'];
       Mage::getModel('checkavailability/availability')->detailpage($pid,$pincode);
        }
        function cartAction(){
         $param= $this->getRequest()->getParams();
        $pid=$param['id'];
       $pincode=$param['pincode'];
       Mage::getModel('checkavailability/availability')->cartpage($pid,$pincode);
        }
   }

?>
