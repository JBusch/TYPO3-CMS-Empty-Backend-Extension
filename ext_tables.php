<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {

	t3lib_extMgm::addModule('{{EXTENSIONKEY}}', '', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod/');
	t3lib_extMgm::addModule('{{EXTENSIONKEY}}', 'tx{{EXTENSIONKEY}}M1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');

}

?>