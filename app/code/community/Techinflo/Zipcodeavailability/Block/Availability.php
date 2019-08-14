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
class Techinflo_Zipcodeavailability_Block_Availability extends Mage_Core_Block_Template {

    public function __construct() {
        
    }
    public function cookieparams() {
        $params = "";
        $myStatus = Mage::getSingleton('customer/session')->isLoggedIn();
        if ($myStatus == 1) {
            $customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultShipping();
            if ($customerAddressId) {
                $address = Mage::getModel('customer/address')->load($customerAddressId);
                $zip = $address->getData('postcode');

                setcookie("pin_code", $zip, time() + 3600 * 24, '/');
                setcookie("avl_pin_code", '', time() + 3600 * 24, '/');
            }
        }
    }

    public function cartitemids() {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cartItems = $quote->getAllVisibleItems();
        foreach ($cartItems as $item) {
            $productId[] = $item->getProductId();
        }
        return $productId;
    }
	
	public function cartitemSku() {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cartItems = $quote->getAllVisibleItems();
        foreach ($cartItems as $item) {
            $productSku[$item->getProductId()] = $item->getSku();
        }
        return $productSku;
    }
	
    public function getIsActive() {
        if (Mage::getStoreConfig("techinflo_zipcodeavailability/general/zipcodeactive"))
            return true;
        else
            return false;
    }

    public function getZipcode() {
        $myStatus = Mage::getSingleton('customer/session')->isLoggedIn();
        if ($myStatus) {
            $customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultShipping();
            if ($customerAddressId) {
                $address = Mage::getModel('customer/address')->load($customerAddressId);
               return $zip = $address->getData('postcode');
            }
        } else {
            return false;
        }
    }
    public function successMsg(){
        if (Mage::getStoreConfig("techinflo_zipcodeavailability/settings/success")!=""){
            return Mage::getStoreConfig("techinflo_zipcodeavailability/settings/success");
        }else{
            return "Available in your location.";
        }
    }
    
	public function getTechinfloTheme(){
        $getVersion = Mage::getVersion();
		$cssParentid = str_replace(".", "", $getVersion);
		$skinurl = explode("/", $this->getSkinUrl());
		$slasCount = count($skinurl);
		$themname = "techinflo_".$skinurl[$slasCount-3];
		return $themname;
    }
}


