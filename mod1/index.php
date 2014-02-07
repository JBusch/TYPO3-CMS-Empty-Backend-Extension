<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Your Name <your@email.tld>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

$GLOBALS['LANG']->includeLLFile('EXT:{{EXTENSIONKEY}}/mod1/locallang.xml');
$GLOBALS['BE_USER']->modAccess($MCONF, 1);      // This checks permissions and exits if the users has no permission for entry.

/**
 * Module '{{EXTENSIONKEY}}_tx{{EXTENSIONKEY}}M1' for the '{{EXTENSIONKEY}}' extension.
 *
 * @author      Your Name <your@email.tld>
 * @package     TYPO3
 * @subpackage  tx_{{EXTENSIONKEY}}
 */
class tx_{{EXTENSIONKEY}}_module1 extends t3lib_SCbase {

	/**
	 * The Extension Configuration
	 *
	 * @var array
	 */
	protected $extensionConfiguration;

	/**
	 * Initialize
	 *
	 * @return void
	 */
	public function init() {
		parent::init();
		$this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['{{EXTENSIONKEY}}']);
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 * If you chose "web" as main module, you will need to consider the $this->id
	 * parameter which will contain the uid-number of the page clicked in the page tree.
	 *
	 * @return void
	 */
	public function main() {

		// Set the page id and get the page info
		$this->id = (int) $this->extensionConfiguration['pid'];
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);

		// Get the returnPath
		$returnPath = rawurlencode($this->MCONF['_']);

		// Draw the header
		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->backPath = $GLOBALS['BACK_PATH'];

		// Access check!
		$access = is_array($this->pageinfo) ? 1 : 0;
		if (($this->id && $access) || ($GLOBALS['BE_USER']->user['admin'] && !$this->id)) {

			// Template
			$this->doc->setModuleTemplate('templates/db_list.html');

			// JavaScript
			$this->doc->JScode = '
			<script language="javascript" type="text/javascript">
			script_ended = 0;
			function jumpToUrl(URL) {
				document.location = URL;
			}
			</script>
			';

			// Get Title
			$this->content .= $this->doc->header($GLOBALS['LANG']->getLL('title'));

			/* ... do some BE Stuff ... */
			$this->content .= '<p>' . $GLOBALS['LANG']->getLL('helloWorld') . '</p>';

			// Set Buttons
			$buttons = array(
				'LEVEL_UP' => '',
				'BACK' => '',
				'NEW_RECORD' => '',
				'PASTE' => '',
				'VIEW' => '',
				'EDIT' => '',
				'MOVE' => '',
				'HIDE_UNHIDE' => '',
				'CSV' => '',
				'EXPORT' => '',
				'CACHE' => '',
				'RELOAD' => '',
				'SHORTCUT' => '',
			);
			// Replace markers
			$markers = array(
				'CSH' => '',
				'CONTENT' => $this->content,
			);

			$this->content = $this->doc->moduleBody($this->pageinfo, $buttons, $markers);
			$this->content = $this->doc->render(
				$GLOBALS['LANG']->getLL('title'),
				$this->content
			);

		} else {
			// If no access or if ID == zero
			$this->content .= $this->doc->startPage($GLOBALS['LANG']->getLL('title'));
			$this->content .= $this->doc->header($GLOBALS['LANG']->getLL('title'));
			$this->content .= $this->doc->spacer(5);
			$this->content .= $this->doc->spacer(10);
			$this->content .= '<p>' . $GLOBALS['LANG']->getLL('accessDenied') . '</p>';
			$this->content .= $this->doc->endPage();
		}

	}

	/**
	 * Prints out the module HTML.
	 *
	 * @return void
	 */
	public function printContent() {
		echo $this->content;
	}

}

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_{{EXTENSIONKEY}}_module1');
$SOBE->init();

// Include files?
foreach ($SOBE->include_once as $INC_FILE) {
	include_once($INC_FILE);
}

$SOBE->main();
$SOBE->printContent();

?>