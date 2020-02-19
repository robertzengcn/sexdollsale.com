<?php
	/**
	 * File: Browser.php
	 * Author: Chris Schuld (http://chrisschuld.com/)
	 * Last Modified: March 14, 2009
	 * @version 1.5
	 * @package PegasusPHP
	 * 
	 * Copyright (C) 2008-2009 Chris Schuld  (chris@chrisschuld.com)
	 *
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License as
	 * published by the Free Software Foundation; either version 2 of
	 * the License, or (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details at:
	 * http://www.gnu.org/copyleft/gpl.html
	 *
	 *
	 * Typical Usage:
	 * 
	 *   $browser = new Browser();
	 *   if( $browser->getBrowser() == Browser::BROWSER_FIREFOX && $browser->getVersion() >= 2 ) {
	 *   	echo 'You have FireFox version 2 or greater';
	 *   }
	 *
	 * User Agents Sampled from: http://www.useragentstring.com/
	 * 
	 * This implementation is based on the original work from Gary White
	 * http://apptools.com/phptools/browser/
	 * 
	 * Gary White noted: "Since browser detection is so unreliable, I am
	 * no longer maintaining this script. You are free to use and or
	 * modify/update it as you want, however the author assumes no
	 * responsibility for the accuracy of the detected values."
	 * 
	 * Anyone experienced with Gary's script might be interested in these notes:
	 * 
	 *   Added class constants
	 *   Added detection and version detection for Google's Chrome
	 *   Updated the version detection for Amaya
	 *   Updated the version detection for Firefox
	 *   Updated the version detection for Lynx
	 *   Updated the version detection for WebTV
	 *   Updated the version detection for NetPositive
	 *   Updated the version detection for IE
	 *   Updated the version detection for OmniWeb
	 *   Updated the version detection for iCab
	 *   Updated the version detection for Safari
	 *   Updated Safari to remove mobile devices (iPhone)
	 *   Added detection for iPhone
	 *   Removed Netscape checks (matches heavily with firefox & mozilla)
	 * 
	 * 
	 * ADDITIONAL UPDATES:
	 * 
	 * 2008-11-07:
	 *  + Added Google's Chrome to the detection list
	 *  + Added isBrowser(string) to the list of functions special thanks to
	 *    Daniel 'mavrick' Lang for the function concept (http://mavrick.id.au)
	 * 
	 * 2008-12-09:
	 *  + Removed unused constant
	 *
	 * 2009-02-16: (Rick Hale)
	 *  + Added version detection for Android phones.
	 * 
	 * 2009-03-14:
	 *  + Added detection for iPods.
	 *  + Added Platform detection for iPhones
	 *  + Added Platform detection for iPods
	 * 
	 * 2009-04-22:
	 *  + Added detection for GoogleBot
	 *  + Added detection for the W3C Validator.
	 *  + Added detection for Yahoo! Slurp
	 * 
	 * 2009-04-27:
	 *  + Updated the IE check to remove a typo and bug (thanks John)
	 *
	 *
	 * 2009-05-29:
	 *  + Converted to PHP4 by Daniel 'mavrick' Lang (mavrick.id.au)
	 *
	 */

	class Browser {
		var $_agent = '';
		var $_browser_name = '';
		var $_version = '';
		var $_platform = '';
		var $_os = '';
		var $_is_aol = false;
		var $_aol_version = '';

		var $BROWSER_UNKNOWN = 'unknown';
		var $VERSION_UNKNOWN = 'unknown';
		
		var $BROWSER_OPERA = 'Opera';
		var $BROWSER_WEBTV = 'WebTV';
		var $BROWSER_NETPOSITIVE = 'NetPositive';
		var $BROWSER_IE = 'Internet Explorer';
		var $BROWSER_POCKET_IE = 'Pocket Internet Explorer';
		var $BROWSER_GALEON = 'Galeon';
		var $BROWSER_KONQUEROR = 'Konqueror';
		var $BROWSER_ICAB = 'iCab';
		var $BROWSER_OMNIWEB = 'OmniWeb';
		var $BROWSER_PHOENIX = 'Phoenix';
		var $BROWSER_FIREBIRD = 'Firebird';
		var $BROWSER_FIREFOX = 'Firefox';
		var $BROWSER_MOZILLA = 'Mozilla';
		var $BROWSER_AMAYA = 'Amaya';
		var $BROWSER_LYNX = 'Lynx';
		var $BROWSER_SAFARI = 'Safari';
		var $BROWSER_IPHONE = 'iPhone';
        var $BROWSER_IPOD = 'iPod';
		var $BROWSER_CHROME = 'Chrome';
        var $BROWSER_ANDROID = 'Android';
        var $BROWSER_GOOGLEBOT = 'GoogleBot';
        var $BROWSER_SLURP = 'Yahoo! Slurp';
        var $BROWSER_W3CVALIDATOR = 'W3C Validator';
        
		var $PLATFORM_UNKNOWN = 'unknown';
		var $PLATFORM_WINDOWS = 'Windows';
		var $PLATFORM_WINDOWS_CE = 'Windows CE';
		var $PLATFORM_APPLE = 'Apple';
		var $PLATFORM_LINUX = 'Linux';
		var $PLATFORM_OS2 = 'OS/2';
		var $PLATFORM_BEOS = 'BeOS';
		var $PLATFORM_IPHONE = 'iPhone';
		var $PLATFORM_IPOD = 'iPod';
		
		var $OPERATING_SYSTEM_UNKNOWN = 'unknown';
		
		function Browser() {
			$this->reset();
			$this->determine();
		}
		/**
		 * Reset all properties
		 */
		function reset() {
			$this->_agent = $_SERVER['HTTP_USER_AGENT'];
			$this->_browser_name = $this->BROWSER_UNKNOWN;
			$this->_version = $this->VERSION_UNKNOWN;
			$this->_platform = $this->PLATFORM_UNKNOWN;
			$this->_os = $this->OPERATING_SYSTEM_UNKNOWN;
			$this->_is_aol = false;
			$this->_aol_version = $this->VERSION_UNKNOWN;
		}
		
		/**
		 * Check to see if the specific browser is valid
		 * @param string $browserName
		 * @return True if the browser is the specified browser
		 */
		function isBrowser($browserName) { return( 0 == strcasecmp($this->_browser_name, trim($browserName))); }

		/**
		 * The name of the browser.  All return types are from the class contants
		 * @return string Name of the browser
		 */
		function getBrowser() { return $this->_browser_name; }
		/**
		 * Set the name of the browser
		 * @param $browser The name of the Browser
		 */
		function setBrowser($browser) { return $this->_browser_name = $browser; }
		/**
		 * The name of the platform.  All return types are from the class contants
		 * @return string Name of the browser
		 */
		function getPlatform() { return $this->_platform; }
		/**
		 * Set the name of the platform
		 * @param $platform The name of the Platform
		 */
		function setPlatform($platform) { return $this->_platform = $platform; }
		/**
		 * The version of the browser.
		 * @return string Version of the browser (will only contain alpha-numeric characters and a period)
		 */
		function getVersion() { return $this->_version; }
		/**
		 * Set the version of the browser
		 * @param $version The version of the Browser
		 */
		function setVersion($version) { $this->_version = ereg_replace('[^0-9,.,a-z,A-Z]','',$version); }
		/**
		 * The version of AOL.
		 * @return string Version of AOL (will only contain alpha-numeric characters and a period)
		 */
		function getAolVersion() { return $this->_aol_version; }
		/**
		 * Set the version of AOL
		 * @param $version The version of AOL
		 */
		function setAolVersion($version) { $this->_aol_version = ereg_replace('[^0-9,.,a-z,A-Z]','',$version); }
		/**
		 * Is the browser from AOL?
		 * @return boolean True if the browser is from AOL otherwise false
		 */
		function isAol() { return $this->_is_aol; }
		/**
		 * Set the browser to be from AOL
		 * @param $isAol
		 */
		function setAol($isAol) { $this->_is_aol = $isAol; }
		/**
		 * Get the user agent value in use to determine the browser
		 * @return string The user agent from the HTTP header
		 */
		function getUserAgent() { return $this->_agent; }
		/**
		 * Set the user agent value (the construction will use the HTTP header value - this will overwrite it)
		 * @param $agent_string The value for the User Agent
		 */
		function setUserAgent($agent_string) {
			$this->reset();
			$this->_agent = $agent_string;
			$this->determine();
		}
		/**
		 * Protected routine to calculate and determine what the browser is in use (including platform)
		 */
		function determine() {
			$this->checkPlatform();
			$this->checkBrowsers();
			$this->checkForAol();
		}

		/**
		 * Protected routine to determine the browser type
		 * @return boolean True if the browser was detected otherwise false
		 */
		function checkBrowsers() {
			return (
						$this->checkBrowserGoogleBot() ||
						$this->checkBrowserSlurp() ||
						$this->checkBrowserInternetExplorer() ||
						$this->checkBrowserFirefox() ||
						$this->checkBrowserChrome() ||
                        $this->checkBrowserAndroid() ||
						$this->checkBrowserSafari() ||
						$this->checkBrowserOpera() ||
						$this->checkBrowserNetPositive() ||
						$this->checkBrowserFirebird() ||
						$this->checkBrowserGaleon() ||
						$this->checkBrowserKonqueror() ||
						$this->checkBrowserIcab() ||
						$this->checkBrowserOmniWeb() ||
						$this->checkBrowserPhoenix() ||
						$this->checkBrowserWebTv() ||
						$this->checkBrowserAmaya() ||
						$this->checkBrowserLynx() ||
						$this->checkBrowseriPhone() ||
						$this->checkBrowseriPod() ||
						$this->checkBrowserW3CValidator() ||
						$this->checkBrowserMozilla() /* Mozilla is such an open standard that you must check it last */	
						);
		}

		/**
		 * Determine if the user is using an AOL User Agent
		 * @return boolean True if the browser is from AOL otherwise false
		 */
		function checkForAol() {
			$retval = false;
			if( eregi("AOL", $this->_agent) ) {
				$aversion = explode(' ',stristr($this->_agent, "AOL"));
				$this->setAol(true);
				$this->setAolVersion(ereg_replace("[^0-9,.,a-z,A-Z]", "", $aversion[1]));
				$retval = true;
			}
			else {
				$this->setAol(false);
				$this->setAolVersion($this->VERSION_UNKNOWN);
				$retval = true;
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is the GoogleBot or not
		 * @return boolean True if the browser is the GoogletBot otherwise false
		 */
		function checkBrowserGoogleBot() {
			$retval = false;
			if( eregi('googlebot',$this->_agent) ) {
				$aresult = explode("/",stristr($this->_agent,"googlebot"));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = $this->BROWSER_GOOGLEBOT;
				$retval = true;
			}
			return $retval;
		}
				
		/**
		 * Determine if the browser is the W3C Validator or not
		 * @return boolean True if the browser is the W3C Validator otherwise false
		 */
		function checkBrowserW3CValidator() {
			$retval = false;
			if( eregi('W3C-checklink',$this->_agent) ) {
				$aresult = explode("/",stristr($this->_agent,"W3C-checklink"));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = $this->BROWSER_W3CVALIDATOR;
				$retval = true;
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is the W3C Validator or not
		 * @return boolean True if the browser is the W3C Validator otherwise false
		 */
		function checkBrowserSlurp() {
			$retval = false;
			if( eregi('Slurp',$this->_agent) ) {
				$aresult = explode("/",stristr($this->_agent,"Slurp"));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = $this->BROWSER_SLURP;
				$retval = true;
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is Internet Explorer or not
		 * @return boolean True if the browser is Internet Explorer otherwise false
		 */
		function checkBrowserInternetExplorer() {
			$retval = false;

			// Test for v1 - v1.5 IE
			if( eregi('microsoft internet explorer', $this->_agent) ) {
				$this->setBrowser($this->BROWSER_IE);
				$this->setVersion('1.0');
				$aresult = stristr($this->_agent, '/');
				if( ereg('308|425|426|474|0b1', $aresult) ) {
					$this->setVersion('1.5');
				}
				$retval = true;
			}
			// Test for versions > 1.5
			else if( eregi('msie',$this->_agent) && !eregi('opera',$this->_agent) ) {
				$aresult = explode(' ',stristr(str_replace(';','; ',$this->_agent),'msie'));
				$this->setBrowser( $this->BROWSER_IE );
				$this->setVersion($aresult[1]);
				$retval = true;
			}
			// Test for Pocket IE
			else if( eregi('mspie',$this->_agent) || eregi('pocket', $this->_agent) ) {
				$aresult = explode(' ',stristr($this->_agent,'mspie'));
				$this->setPlatform( $this->PLATFORM_WINDOWS_CE );
				$this->setBrowser( $this->BROWSER_POCKET_IE );
				
				if( eregi('mspie', $this->_agent) ) {
					$this->setVersion($aresult[1]);
				}
				else {
					$aversion = explode('/',$this->_agent);
					$this->setVersion($aversion[1]);
				}
				$retval = true;
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is Opera or not
		 * @return boolean True if the browser is Opera otherwise false
		 */
		function checkBrowserOpera() {
			$retval = false;
			if( eregi('opera',$this->_agent) ) {
				$resultant = stristr($this->_agent, 'opera');
				if( eregi('/',$resultant) ) {
					$aresult = explode('/',$resultant);
					$aversion = explode(' ',$aresult[1]); 
					$this->setVersion($aversion[0]);
					$this->_browser_name = $this->BROWSER_OPERA;
					$retval = true;
				}
				else {
					$aversion = explode(' ',stristr($resultant,'opera'));
					$this->setVersion($aversion[1]);
					$this->_browser_name = $this->BROWSER_OPERA;
					$retval = true;
				}
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is WebTv or not
		 * @return boolean True if the browser is WebTv otherwise false
		 */
		function checkBrowserWebTv() {
			$retval = false;
			if( eregi('webtv',$this->_agent) ) {
				$aresult = explode("/",stristr($this->_agent,"webtv"));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = $this->BROWSER_WEBTV;
				$retval = true;
			}
			return $retval;
		}
				
		/**
		 * Determine if the browser is NetPositive or not
		 * @return boolean True if the browser is NetPositive otherwise false
		 */
		function checkBrowserNetPositive() {
			$retval = false;
			if( eregi('NetPositive',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'NetPositive'));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = $this->BROWSER_NETPOSITIVE;
				$this->_platform = $this->PLATFORM_BEOS;
				$retval = true;
			}
			return $retval;
		}
			
		/**
		 * Determine if the browser is Galeon or not
		 * @return boolean True if the browser is Galeon otherwise false
		 */
		function checkBrowserGaleon() {
			$retval = false;
			if( eregi('galeon',$this->_agent) ) {
				$aresult = explode(' ',stristr($this->_agent,'galeon'));
				$aversion = explode('/',$aresult[0]);
				$this->setVersion($aversion[1]);
				$this->setBrowser($this->BROWSER_GALEON);
				$retval = true;
			}
			return $retval;
		}
			
		/**
		 * Determine if the browser is Konqueror or not
		 * @return boolean True if the browser is Konqueror otherwise false
		 */
		function checkBrowserKonqueror() {
			$retval = false;
			if( eregi('Konqueror',$this->_agent) ) {
				$aresult = explode(' ',stristr($this->_agent,'Konqueror'));
				$aversion = explode('/',$aresult[0]);
				$this->setVersion($aversion[1]);
				$this->setBrowser($this->BROWSER_KONQUEROR);
				$retval = true;
			}
			return $retval;
		}
			
		/**
		 * Determine if the browser is iCab or not
		 * @return boolean True if the browser is iCab otherwise false
		 */
		function checkBrowserIcab() {
			$retval = false;
			if( eregi('icab',$this->_agent) ) {
				$aversion = explode(' ',stristr(str_replace('/',' ',$this->_agent),'icab'));
				$this->setVersion($aversion[1]);
				$this->setBrowser($this->BROWSER_ICAB);
				$retval = true;
			}
			return $retval;
		}
			
		/**
		 * Determine if the browser is OmniWeb or not
		 * @return boolean True if the browser is OmniWeb otherwise false
		 */
		function checkBrowserOmniWeb() {
			$retval = false;
			if( eregi('omniweb',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'omniweb'));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser($this->BROWSER_OMNIWEB);
				$retval = true;
			}
			return $retval;
		}
			
		/**
		 * Determine if the browser is Phoenix or not
		 * @return boolean True if the browser is Phoenix otherwise false
		 */
		function checkBrowserPhoenix() {
			$retval = false;
			if( eregi('Phoenix',$this->_agent) ) {
				$aversion = explode('/',stristr($this->_agent,'Phoenix'));
				$this->setVersion($aversion[1]);
				$this->setBrowser($this->BROWSER_PHOENIX);
				$retval = true;
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is Firebird or not
		 * @return boolean True if the browser is Firebird otherwise false
		 */
		function checkBrowserFirebird() {
			$retval = false;
			if( eregi('Firebird',$this->_agent) ) {
				$aversion = explode('/',stristr($this->_agent,'Firebird'));
				$this->setVersion($aversion[1]);
				$this->setBrowser($this->BROWSER_FIREBIRD);
				$retval = true;
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is Firefox or not
		 * @return boolean True if the browser is Firefox otherwise false
		 */
		function checkBrowserFirefox() {
			$retval = false;
			if( eregi('Firefox',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Firefox'));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser($this->BROWSER_FIREFOX);
				$retval = true;
			}
			return $retval;
		}
		
		/**
		 * Determine if the browser is Mozilla or not
		 * @return boolean True if the browser is Mozilla otherwise false
		 */
		function checkBrowserMozilla() {
			$retval = false;
			if( eregi('Mozilla',$this->_agent) && eregi('rv:[0-9].[0-9][a-b]',$this->_agent) && !eregi('netscape',$this->_agent)) {
				$aversion = explode(' ',stristr($this->_agent,'rv:'));
				eregi('rv:[0-9].[0-9][a-b]',$this->_agent,$aversion);
				$this->setVersion($aversion[0]);
				$this->setBrowser($this->BROWSER_MOZILLA);
				$retval = true;
			}
			else if( eregi('mozilla',$this->_agent) && eregi('rv:[0-9]\.[0-9]',$this->_agent) && !eregi('netscape',$this->_agent) ) {
				$aversion = explode(" ",stristr($this->_agent,'rv:'));
            	eregi('rv:[0-9]\.[0-9]\.[0-9]',$this->_agent,$aversion);
				$this->setVersion($aversion[0]);
				$this->setBrowser($this->BROWSER_MOZILLA);
				$retval = true;
			}
			return $retval;
		}

		/**
		 * Determine if the browser is Lynx or not
		 * @return boolean True if the browser is Lynx otherwise false
		 */
		function checkBrowserLynx() {
			$retval = false;
			if( eregi('libwww',$this->_agent) && eregi("lynx", $this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Lynx'));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser($this->BROWSER_LYNX);
				$retval = true;
			}
			return $retval;
		}
			
		/**
		 * Determine if the browser is Amaya or not
		 * @return boolean True if the browser is Amaya otherwise false
		 */
		function checkBrowserAmaya() {
			$retval = false;
			if( eregi('libwww',$this->_agent) && eregi("amaya", $this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Amaya'));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser($this->BROWSER_AMAYA);
				$retval = true;
			}
			return $retval;
		}
			
		/**
		 * Determine if the browser is Chrome or not
		 * @return boolean True if the browser is Safari otherwise false
		 */
		function checkBrowserChrome() {
			$retval = false;
			if( eregi('Chrome',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Chrome'));
				$aversion = explode(' ',$aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser($this->BROWSER_CHROME);
				$retval = true;
			}
			return $retval;
		}		
		
		/**
		 * Determine if the browser is Safari or not
		 * @return boolean True if the browser is Safari otherwise false
		 */
		function checkBrowserSafari() {
			$retval = false;
			if( eregi('Safari',$this->_agent) && ! eregi('iPhone',$this->_agent) && ! eregi('iPod',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Version'));
				if( isset($aresult[1]) ) {
					$aversion = explode(' ',$aresult[1]);
					$this->setVersion($aversion[0]);
				}
				else {
					$this->setVersion($this->VERSION_UNKNOWN);
				}
				$this->setBrowser($this->BROWSER_SAFARI);
				$retval = true;
			}
			return $retval;
		}		
		
		/**
		 * Determine if the browser is iPhone or not
		 * @return boolean True if the browser is iPhone otherwise false
		 */
		function checkBrowseriPhone() {
			$retval = false;
			if( eregi('iPhone',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Version'));
				if( isset($aresult[1]) ) {
					$aversion = explode(' ',$aresult[1]);
					$this->setVersion($aversion[0]);
				}
				else {
					$this->setVersion($this->VERSION_UNKNOWN);
				}
				$this->setBrowser($this->BROWSER_IPHONE);
				$retval = true;
			}
			return $retval;
		}		

		/**
		 * Determine if the browser is iPod or not
		 * @return boolean True if the browser is iPod otherwise false
		 */
		function checkBrowseriPod() {
			$retval = false;
			if( eregi('iPod',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Version'));
				if( isset($aresult[1]) ) {
					$aversion = explode(' ',$aresult[1]);
					$this->setVersion($aversion[0]);
				}
				else {
					$this->setVersion($this->VERSION_UNKNOWN);
				}
				$this->setBrowser($this->BROWSER_IPOD);
				$retval = true;
			}
			return $retval;
		}		

		/**
		 * Determine if the browser is Android or not
		 * @return boolean True if the browser is Android otherwise false
		 */
		function checkBrowserAndroid() {
			$retval = false;
			if( eregi('Android',$this->_agent) ) {
				$aresult = explode('/',stristr($this->_agent,'Version'));
				if( isset($aresult[1]) ) {
					$aversion = explode(' ',$aresult[1]);
					$this->setVersion($aversion[0]);
				}
				else {
					$this->setVersion($this->VERSION_UNKNOWN);
				}
				$this->setBrowser($this->BROWSER_ANDROID);
				$retval = true;
			}
			return $retval;
		}		

		/**
		 * Determine the user's platform
		 */
		function checkPlatform() {
			if( eregi("iPhone", $this->_agent) ) {
				$this->_platform = $this->PLATFORM_IPHONE;
			}
			else if( eregi("iPod", $this->_agent) ) {
				$this->_platform = $this->PLATFORM_IPOD;
			}
			else if( eregi("win", $this->_agent) ) {
				$this->_platform = $this->PLATFORM_WINDOWS;
			}
			elseif( eregi("mac", $this->_agent) ) {
				$this->_platform = $this->PLATFORM_APPLE;
			}
			elseif( eregi("linux", $this->_agent) ) {
				$this->_platform = $this->PLATFORM_LINUX;
			}
			elseif( eregi("OS/2", $this->_agent) ) {
				$this->_platform = $this->PLATFORM_OS2;
			}
			elseif( eregi("BeOS", $this->_agent) ) {
				$this->_platform = $this->PLATFORM_BEOS;
			}
		}
	}