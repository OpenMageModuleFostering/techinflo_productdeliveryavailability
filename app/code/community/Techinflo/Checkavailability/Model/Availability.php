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
class Techinflo_Checkavailability_Model_Availability extends Mage_Core_Model_Abstract {

    public function _construct() {
        $this->_init('checkavailability/availability');
    }

    public function detailpage($pid, $pincode) {

        $product_id = $pid;
        $product = Mage::getModel('catalog/product')->load($product_id);
        $pin_code = $pincode;

        $avilble_vendors = explode("||", $product->getVendorAvailability());
        //print_r($avilble_vendors);
        foreach ($avilble_vendors as $vendarr) {
            $vand_ar1 = explode(":", $vendarr);
            $zips = explode(",", $vand_ar1[1]);
            foreach ($zips as $ziprange) {

                if (strpos($ziprange, "-")) {
                    //echo "true".$zip;
                    $rang = explode("-", $ziprange);
                    $r1 = trim($rang[0]);
                    $r2 = trim($rang[1]);
                    for ($i = $r1; $i <= $r2; $i++) {
                        $vendors[$vand_ar1[0]][] = $i;
                    }
                } else {
                    $vendors[$vand_ar1[0]][] = trim($ziprange);
                }
            }
        }
        $reponse = "";
        foreach ($vendors as $key => $vend) {
            if (in_array($pin_code, $vend) || in_array('ALL', $vend)) {
                $reponse = $pin_code . ",";
            } else {
                $reponse = "1," . $pin_code;
            }
        }
        echo " " . rtrim($reponse, ",");
    }

    public function cartpage($pid, $pincode) {
        $product_ids = unserialize($pid);

        foreach ($product_ids as $product_id) {
            $product = Mage::getModel('catalog/product')->load($product_id);
            $pin_code = $pincode;
            $avilble_vendors = explode("||", $product->getVendorAvailability());
            foreach ($avilble_vendors as $vendarr) {
                $vand_ar1 = explode(":", $vendarr);
                $zips = explode(",", $vand_ar1[1]);
                foreach ($zips as $ziprange) {
                    if (strpos($ziprange, "-")) {
                        //echo "true".$zip;
                        $rang = explode("-", $ziprange);
                        $r1 = trim($rang[0]);
                        $r2 = trim($rang[1]);
                        for ($i = $r1; $i <= $r2; $i++) {
                            $vendors[$vand_ar1[0]][] = $i;
                        }
                    } else {
                        $vendors[$vand_ar1[0]][] = trim($ziprange);
                    }
                }
            }

            //$reponse="";
            foreach ($vendors as $key => $vend) {
                if (in_array($pin_code, $vend) || in_array('ALL', $vend)) {
                    $reponse_yes = $pin_code . ",";
                } else {
                    $reponse_no = $pin_code;
                }
            }
        }
        if (isset($reponse_no) || !empty($reponse_no)) {
            echo '1,' . $pin_code;
        } else {
            echo $pin_code;
        }
    }

    public function checkoutpage($pid) {

        $product = Mage::getModel('catalog/product')->load($pid);
        if (isset($_COOKIE["pin_code"]) && $_COOKIE["pin_code"] != '') {
            $pin_code = $_COOKIE["pin_code"];
        } else {
            $pin_code = $_COOKIE["avl_pin_code"];
        }
        if ($this->getZipcode()) {
            $pin_code = $this->getZipcode();
        }
        $avilble_vendors = explode("||", $product->getVendorAvailability());
        foreach ($avilble_vendors as $vendarr) {
            $vand_ar1 = explode(":", $vendarr);
            $zips = explode(",", $vand_ar1[1]);
            foreach ($zips as $ziprange) {
                if (strpos($ziprange, "-")) {
                    //echo "true".$zip;
                    $rang = explode("-", $ziprange);
                    $r1 = trim($rang[0]);
                    $r2 = trim($rang[1]);
                    for ($i = $r1; $i <= $r2; $i++) {
                        $vendors[$vand_ar1[0]][] = $i;
                    }
                } else {
                    $vendors[$vand_ar1[0]][] = trim($ziprange);
                }
            }
        }

        //$reponse="";
        foreach ($vendors as $key => $vend) {
            if (in_array($pin_code, $vend) || in_array('ALL', $vend)) {
                return $reponse_yes = "Yes";
            } else {
                return $reponse_no = "No";
            }
        }
    }

}
