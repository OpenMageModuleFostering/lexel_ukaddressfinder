<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to helpdesk@lexel.co.uk so we can send you a copy immediately.
 *
 * @category   Lexel
 * @package    Lexel_UkAddressFinder
 * @copyright  Copyright (c) 2013 Lexel Software Ltd (http://www.lexel.co.uk)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Lexel_UkAddressFinder_IndexController extends Mage_Core_Controller_Front_Action
{
    public function billingaddresslistfinderAction() 
    {
		$postCode = $this->getRequest()->getPost('postcode'); 
		$userName = Mage::getStoreConfig('addressfinder/general/username');  // get username from backend system configuration
		$password = Mage::getStoreConfig('addressfinder/general/password');  // get password from backend system configuration
		$licenseKey = Mage::getStoreConfig('addressfinder/general/license_key');  // get license key from backend system configuration
		
		/* call to lexel PAF API via curl */
		
	    $url = "http://services.lexel.co.uk/paf/";	
		
		$queryString = "?Username=".$userName."&Password=".$password."&LicenceKey=".$licenseKey."&LookupType=ByPostcodeList&ID=".urlencode($postCode);
	    $response = file_get_contents($url.$queryString);
		$dom = new DOMDocument('1.0', 'iso-8859-1');
		$dom->loadXML($response);
		$infoNode = $dom->getElementsByTagName('Information')->item(0);
		
		$errorNode = $infoNode->getElementsByTagName('Error')->item(0);
		if ($errorNode->getElementsByTagName('ErrorNo')->item(0)->nodeValue != '0') {
			$message = $errorNode->getElementsByTagName('ErrorMsg')->item(0)->nodeValue;
			echo "<div>We apologize, we cannot find this address in our database, please check that your postcode is correct</div>";
		}
        echo "<select id='addressLookUp' onchange='billingExactAddressFinder(this.value);'>";
		echo "<option>----- please select your address ----</option>";
		$addressParentNode = $infoNode->childNodes->item(1);
		foreach ($addressParentNode->childNodes as $addressNode)
		{
		 echo "<option value=".$addressNode->getAttribute('AddressID').">".$addressNode->getAttribute('Description')."</option>";
		}
		echo "</select>";
    }
	
	public function billingexactaddressfinderAction() 
    {
		$addressId = $this->getRequest()->getPost('addressid'); 
		$userName = Mage::getStoreConfig('addressfinder/general/username');  // get username from backend system configuration
		$password = Mage::getStoreConfig('addressfinder/general/password');  // get password from backend system configuration
		$licenseKey = Mage::getStoreConfig('addressfinder/general/license_key');  // get license key from backend system configuration
		
		/* call to lexel PAF API via curl */
		
		
	    $url = "http://services.lexel.co.uk/paf/";	
		
		$queryString = "?Username=".$userName."&Password=".$password."&LicenceKey=".$licenseKey."&LookupType=ByAddressID&ID=".urlencode($addressId);
		print $url . $queryString;
	    $response = file_get_contents($url.$queryString);
		//$xml_object = simplexml_load_file($response);
		#print_r(htmlspecialchars($response));
		$dom = new DOMDocument('1.0', 'iso-8859-1');
		$dom->loadXML($response);
		$infoNode = $dom->getElementsByTagName('Information')->item(0);
		
		$errorNode = $infoNode->getElementsByTagName('Error')->item(0);
		if ($errorNode->getElementsByTagName('ErrorNo')->item(0)->nodeValue != '0') {
			$message = $errorNode->getElementsByTagName('ErrorMsg')->item(0)->nodeValue;
			echo "<div>We apologize, we cannot find this address in our database, please check that your postcode is correct</div>";
		}
       
		$addressParentNode = $infoNode->getElementsByTagName('Address')->item(0);
		
		$OrganisationName = $addressParentNode->getElementsByTagName('OrganisationName')->item(0)->nodeValue; 
		$AddressLine1 = $addressParentNode->getElementsByTagName('AddressLine1')->item(0)->nodeValue; 
		$AddressLine2 = $addressParentNode->getElementsByTagName('AddressLine2')->item(0)->nodeValue; 
		$County = $addressParentNode->getElementsByTagName('County')->item(0)->nodeValue; 
		$PostTown = $addressParentNode->getElementsByTagName('PostTown')->item(0)->nodeValue; 
		$PostCode = $addressParentNode->getElementsByTagName('PostCode')->item(0)->nodeValue; 
		$totalString = '~'.$OrganisationName.'~'.$AddressLine1.'~'.$AddressLine2.'~'.$County.'~'.$PostTown.'~'.$PostCode;
		echo $totalString;
    }
	
	public function shippingaddresslistfinderAction() 
    {
		$postCode = $this->getRequest()->getPost('postcode'); 
		$userName = Mage::getStoreConfig('addressfinder/general/username');  // get username from backend system configuration
		$password = Mage::getStoreConfig('addressfinder/general/password');  // get password from backend system configuration
		$licenseKey = Mage::getStoreConfig('addressfinder/general/license_key');  // get license key from backend system configuration
		
		/* call to lexel PAF API via curl */
		
	    $url = "http://services.lexel.co.uk/paf/";	
		
		$queryString = "?Username=".$userName."&Password=".$password."&LicenceKey=".$licenseKey."&LookupType=ByPostcodeList&ID=".urlencode($postCode);
	    $response = file_get_contents($url.$queryString);
		$dom = new DOMDocument('1.0', 'iso-8859-1');
		$dom->loadXML($response);
		$infoNode = $dom->getElementsByTagName('Information')->item(0);
		
		$errorNode = $infoNode->getElementsByTagName('Error')->item(0);
		if ($errorNode->getElementsByTagName('ErrorNo')->item(0)->nodeValue != '0') {
			$message = $errorNode->getElementsByTagName('ErrorMsg')->item(0)->nodeValue;
			echo "<div>We apologize, we cannot find this address in our database, please check that your postcode is correct</div>";
		}
        echo "<select id='addressLookUp' onchange='shippingExactAddressFinder(this.value);'>";
		echo "<option>----- please select your address ----</option>";
		$addressParentNode = $infoNode->childNodes->item(1);
		foreach ($addressParentNode->childNodes as $addressNode)
		{
		 echo "<option value=".$addressNode->getAttribute('AddressID').">".$addressNode->getAttribute('Description')."</option>";
		}
		echo "</select>";
    }
	
	public function shippingexactaddressfinderAction() 
    {
		$addressId = $this->getRequest()->getPost('addressid'); 
		$userName = Mage::getStoreConfig('addressfinder/general/username');  // get username from backend system configuration
		$password = Mage::getStoreConfig('addressfinder/general/password');  // get password from backend system configuration
		$licenseKey = Mage::getStoreConfig('addressfinder/general/license_key');  // get license key from backend system configuration
		
		/* call to lexel PAF API via curl */
		
		
	    $url = "http://services.lexel.co.uk/paf/";	
		
		$queryString = "?Username=".$userName."&Password=".$password."&LicenceKey=".$licenseKey."&LookupType=ByAddressID&ID=".urlencode($addressId);
		print $url . $queryString;
	    $response = file_get_contents($url.$queryString);
		//$xml_object = simplexml_load_file($response);
		#print_r(htmlspecialchars($response));
		$dom = new DOMDocument('1.0', 'iso-8859-1');
		$dom->loadXML($response);
		$infoNode = $dom->getElementsByTagName('Information')->item(0);
		
		$errorNode = $infoNode->getElementsByTagName('Error')->item(0);
		if ($errorNode->getElementsByTagName('ErrorNo')->item(0)->nodeValue != '0') {
			$message = $errorNode->getElementsByTagName('ErrorMsg')->item(0)->nodeValue;
			echo "<div>We apologize, we cannot find this address in our database, please check that your postcode is correct</div>";
		}
       
		$addressParentNode = $infoNode->getElementsByTagName('Address')->item(0);
		
		$OrganisationName = $addressParentNode->getElementsByTagName('OrganisationName')->item(0)->nodeValue; 
		$AddressLine1 = $addressParentNode->getElementsByTagName('AddressLine1')->item(0)->nodeValue; 
		$AddressLine2 = $addressParentNode->getElementsByTagName('AddressLine2')->item(0)->nodeValue; 
		$County = $addressParentNode->getElementsByTagName('County')->item(0)->nodeValue; 
		$PostTown = $addressParentNode->getElementsByTagName('PostTown')->item(0)->nodeValue; 
		$PostCode = $addressParentNode->getElementsByTagName('PostCode')->item(0)->nodeValue; 
		$totalString = '~'.$OrganisationName.'~'.$AddressLine1.'~'.$AddressLine2.'~'.$County.'~'.$PostTown.'~'.$PostCode;
		echo $totalString;
    }
}