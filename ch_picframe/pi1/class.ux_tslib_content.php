<?php

class ux_ux_tslib_cObj extends ux_tslib_cObj {
    
    /**
	 * Wraps the input string in link-tags that opens the image in a new window.
	 *
	 * @param	string		String to wrap, probably an <img> tag
	 * @param	string		The original image file
	 * @param	array		TypoScript properties for the "imageLinkWrap" function
	 * @return	string		The input string, $string, wrapped as configured.
	 * @see cImage()
	 * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=316&cHash=2848266da6
	 */
	function imageLinkWrap($string,$imageFile,$conf) {
    
		$a1='';
		$a2='';
		$content=$string;
		if ($this->stdWrap($conf['enable'],$conf['enable.']))	{
			$content=$this->typolink($string, $conf['typolink.']);
				// imageFileLink:
			if ($content==$string && @is_file($imageFile)) {
				$params = '';
				if ($conf['width']) {$params.='&width='.rawurlencode($conf['width']);}
				if ($conf['height']) {$params.='&height='.rawurlencode($conf['height']);}
				if ($conf['effects']) {$params.='&effects='.rawurlencode($conf['effects']);}
				if ($conf['sample']) {$params.='&sample=1';}
				if ($conf['alternativeTempPath']) {$params.='&alternativeTempPath='.rawurlencode($conf['alternativeTempPath']);}
				if ($conf['stylesheet']) {$params.='&stylesheet='.rawurlencode($conf['stylesheet']);}
				if ($conf['imgstyle']) {$params.='&imgstyle='.rawurlencode($conf['imgstyle']);}
                                                                if ($conf['doctype']) {$params.='&doctype='.rawurlencode($conf['doctype']);}

				if ($conf['bodyTag']) {$params.='&bodyTag='.rawurlencode($conf['bodyTag']);}
				if ($conf['title']) {$params.='&title='.rawurlencode($conf['title']);}
				

				$md5_value = md5(
						$imageFile.'|'.
						$conf['width'].'|'.
						$conf['height'].'|'.
						$conf['effects'].'|'.
						$conf['bodyTag'].'|'.
						$conf['title'].'|'.
						$conf['wrap'].'|'.
						$conf['stylesheet'].'|'.
						$conf['imgstyle'].'|'.
                                                                                                $conf['doctype'].'|'.
						$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'].'|');

				$params.= '&md5='.$md5_value;
           
           
                if ($conf['wrap']) {$params.='&wrap='.rawurlencode($conf['wrap']);}
                
				$url = $GLOBALS['TSFE']->absRefPrefix.'showpic.php?file='.rawurlencode($imageFile).$params;
				if ($conf['JSwindow.']['altUrl'] || $conf['JSwindow.']['altUrl.'])	{
					$altUrl = $this->stdWrap($conf['JSwindow.']['altUrl'], $conf['JSwindow.']['altUrl.']);
					if ($altUrl)	{
						$url=$altUrl;
					}
				}

				if ($conf['JSwindow'])	{
					$gifCreator = t3lib_div::makeInstance('tslib_gifbuilder');
					$gifCreator->init();
					$gifCreator->mayScaleUp = 0;
					$dims = $gifCreator->getImageScale($gifCreator->getImageDimensions($imageFile),$conf['width'],$conf['height'],'');
					$offset = t3lib_div::intExplode(',',$conf['JSwindow.']['expand'].',');

					$a1='<a href="#" onclick="'.
						htmlspecialchars('openPic(\''.$GLOBALS['TSFE']->baseUrlWrap($url).'\',\''.($conf['JSwindow.']['newWindow']?md5($url):'thePicture').'\',\'width='.($dims[0]+$offset[0]).',height='.($dims[1]+$offset[1]).',status=0,menubar=0\'); return false;').
						'"'.$GLOBALS['TSFE']->ATagParams.'>';
					$a2='</a>';
					$GLOBALS['TSFE']->setJS('openPic');
				} else {
					$target = ' target="thePicture"';
					if (isset($conf['target'])) {
						$target= $conf['target'] ? ' target="'.$conf['target'].'"' : '';
					}
					$a1='<a href="'.htmlspecialchars($url).'"'.$target.$GLOBALS['TSFE']->ATagParams.'>';
					$a2='</a>';
				}
				$content=$a1.$string.$a2;
			}
		}

		return $content;
	}
    
}


?>
