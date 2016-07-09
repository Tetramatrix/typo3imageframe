<?php

class ux_t3lib_stdGraphic extends t3lib_stdGraphic {

	/**
	 * Returns Image Tag for input image information array.
	 *
	 * @param	array		Image information array, key 0/1 is width/height and key 3 is the src value
	 * @return	string		Image tag for the input image information array.
	 */
	function imgTag ($imgInfo) {
		return '<img src="'.$imgInfo[3].'" width="'.$imgInfo[0].'" height="'.$imgInfo[1].'" '.$imgInfo[4].' border="0" alt="" />';
	}
    
}

?>