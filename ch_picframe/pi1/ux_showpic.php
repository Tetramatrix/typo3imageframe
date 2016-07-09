<?php

class ux_SC_tslib_showpic extends SC_tslib_showpic {

	/**
	 * Init function, setting the input vars in the global space.
	 *
	 * @return	void
	 */
	function init()	{    
    
			// Loading internal vars with the GET/POST parameters from outside:
		$this->file = t3lib_div::_GP('file');
		$this->width = t3lib_div::_GP('width');
		$this->height = t3lib_div::_GP('height');
		$this->sample = t3lib_div::_GP('sample');
		$this->alternativeTempPath = t3lib_div::_GP('alternativeTempPath');
		$this->effects = t3lib_div::_GP('effects');
		$this->frame = t3lib_div::_GP('frame');
		$this->bodyTag = t3lib_div::_GP('bodyTag');
		$this->title = t3lib_div::_GP('title');
		$this->wrap = t3lib_div::_GP('wrap');
		$this->stylesheet = t3lib_div::_GP('stylesheet');
		$this->imgstyle = t3lib_div::_GP('imgstyle');
                                $this->doctype = t3lib_div::_GP('doctype');

		$this->md5 = t3lib_div::_GP('md5');

		// ***********************
		// Check parameters
		// ***********************
			// If no file-param is given, we must exit
		if (!$this->file)	{
			die('Parameter Error: No file given.');
		}

			// Chech md5-checksum: If this md5-value does not match the one submitted, then we fail... (this is a kind of security that somebody don't just hit the script with a lot of different parameters
		$md5_value = md5(
				$this->file.'|'.
				$this->width.'|'.
				$this->height.'|'.
				$this->effects.'|'.
				$this->bodyTag.'|'.
				$this->title.'|'.
				$this->wrap.'|'.
				$this->stylesheet.'|'.
				$this->imgstyle.'|'.
                                                                $this->doctype.'|'.
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'].'|');

		if ($md5_value!=$this->md5) {
			die('Parameter Error: Wrong parameters sent.');
		}

		// ***********************
		// Check the file. If must be in a directory beneath the dir of this script...
		// $this->file remains unchanged, because of the code in stdgraphic, but we do check if the file exists within the current path
		// ***********************

		$test_file=PATH_site.$this->file;
		if (!t3lib_div::validPathStr($test_file))	{
			die('Parameter Error: No valid filepath');
		}
		if (!@is_file($test_file))	{
			die('The given file was not found');
		}
	}

	/**
	 * Main function which creates the image if needed and outputs the HTML code for the page displaying the image.
	 * Accumulates the content in $this->content
	 *
	 * @return	void
	 */
	function main()	{

			// Creating stdGraphic object, initialize it and make image:
		$img = t3lib_div::makeInstance('t3lib_stdGraphic');
		$img->mayScaleUp = 0;
		$img->init();
		if ($this->sample)	{$img->scalecmd = '-sample';}
		if ($this->alternativeTempPath && t3lib_div::inList($GLOBALS['TYPO3_CONF_VARS']['FE']['allowedTempPaths'],$this->alternativeTempPath))	{
			$img->tempPath = $this->alternativeTempPath;
		}

			// Need to connect to database, because this is used (typo3temp_db_tracking, cached image dimensions).
		$GLOBALS['TYPO3_DB']->sql_pconnect(TYPO3_db_host, TYPO3_db_username, TYPO3_db_password);
		$GLOBALS['TYPO3_DB']->sql_select_db(TYPO3_db);

		if (strstr($this->width.$this->height, 'm')) {$max='m';} else {$max='';}

		$this->height = t3lib_div::intInRange($this->height,0,1000);
		$this->width = t3lib_div::intInRange($this->width,0,1000);
		if ($this->frame)	{$this->frame = intval($this->frame);}
		$imgInfo = $img->imageMagickConvert($this->file,'web',$this->width.$max,$this->height,$img->IMparams($this->effects),$this->frame,'');
        
        if ($this->imgstyle) {
            $imgInfo[] = $this->imgstyle;
        }

        // Create HTML output:
        $this->content = ($this->doctype ? $this->doctype : '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">').'<html><head><title>'.htmlspecialchars($this->title ? $this->title : "Image").'</title>'.($this->stylesheet ? '<link href="'.$this->stylesheet.'" rel="stylesheet" type="text/css" />' : '').'</head>'.($this->bodyTag ? $this->bodyTag : '<body>');

		if (is_array($imgInfo))	{
			$wrapParts = explode('|',$this->wrap);
			$this->content.=trim($wrapParts[0]).$img->imgTag($imgInfo).trim($wrapParts[1]);
		}
		$this->content.='</body></html>';
	}


}


?>
