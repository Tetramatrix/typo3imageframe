<?php

if (!defined ("TYPO3_MODE")) die ("Access denied.");

t3lib_extMgm::addPageTSConfig('TCEFORM.tt_content.tx_croncssstyledimgtext_renderMethod.addItems.picframe1-caption = Picture Frame 1 with Caption');
t3lib_extMgm::addPageTSConfig('TCEFORM.tt_content.tx_croncssstyledimgtext_renderMethod.addItems.picframe1-nocaption = Picture Frame 1 without Caption');

t3lib_extMgm::addPageTSConfig('TCEFORM.tt_content.tx_croncssstyledimgtext_renderMethod.addItems.picframe2-caption = Picture Frame 2 with Caption');
t3lib_extMgm::addPageTSConfig('TCEFORM.tt_content.tx_croncssstyledimgtext_renderMethod.addItems.picframe2-nocaption = Picture Frame 2 without Caption');


$TYPO3_CONF_VARS['FE']['XCLASS']['ext/cron_cssstyledimgtext/class.ux_tslib_content.php']=t3lib_extMgm::extPath($_EXTKEY).'pi1/class.ux_tslib_content.php';
$TYPO3_CONF_VARS['FE']['XCLASS']['tslib/showpic.php']=t3lib_extMgm::extPath('ch_picframe').'pi1/ux_showpic.php';
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['t3lib/class.t3lib_stdgraphic.php']=t3lib_extMgm::extPath($_EXTKEY).'pi1/class.ux_t3lib_stdGraphic.php';


?>