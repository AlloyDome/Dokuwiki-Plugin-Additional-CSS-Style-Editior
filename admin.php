<?php

/**
 * DokuWiki acse 插件（管理员界面） · DokuWiki plugin acse (admin UI)
 *
 * @license		MIT License
 * @author		AlloyDome
 * 
 * @version		1.0.0, beta (210615)
 */

if(!defined('DOKU_INC'))
	die();	// 必须在 Dokuwiki 下运行 · Must be run within Dokuwiki

class admin_plugin_acse extends DokuWiki_Admin_Plugin {
	var $output = false;
	
	/**
	 * Constructor
	 */
 	public function __construct() {
		define('USERSTYLE_CSS', DOKU_INC . 'conf/userstyle.css');
        define('USERPRINT_CSS', DOKU_INC . 'conf/userprint.css');
		define('USERFEED_CSS', DOKU_INC . 'conf/userfeed.css');
		define('USERALL_CSS', DOKU_INC . 'conf/userall.css');
	}

	/**
	 * Handle user request
	 * 
	 * @version	1.0.0, beta (210615)
	 * @since	1.0.0, beta (210615)
	 */
	public function handle() {
		if (!isset($_REQUEST['cmd']))
			return;   // first time - nothing to do
		if (!checkSecurityToken())
			return;
		if (!is_array($_REQUEST['cmd']))
			return;

		switch (key($_REQUEST['cmd'])) {
			case 'update': {
				$cssText = $_REQUEST['css-file'];
				settype($cssText, 'string');

				io_saveFile(USERSTYLE_CSS, $cssText);
				break;
			}
		}
	}

	/**
	 * 输出管理界面的 HTML · Output HTML for admin UI
	 * 
	 * @version	1.0.0, beta (210615)
	 * @since	1.0.0, beta (210615)
	 */
	public function html() {
		ptln($this->locale_xhtml('intro'));
		ptln('<hr /><form action="' . wl($ID) . '"method="post">');
		// output hidden values to ensure dokuwiki will return back to this plugin
		ptln('<input type="hidden" name="do" value="admin" />');
		ptln('<input type="hidden" name="page" value="'. $this->getPluginName(). '" />');
		formSecurityToken();

		ptln(
			'<label for="css-file">' . $this->getLang('textareaHintLabel') . '</label>' .
			'<textarea class="edit" id="css-file" name="css-file" rows="10">' .
			$this->getStyleSheet() .
			'</textarea>'
		);	// 输入框 · input box
		ptln(
			'<input type="submit" name="cmd[update]" value="' . 
			$this->getLang('updateButton') .
			'" />'
		);	// “更新”按钮 · "Update" button
		ptln('</form>');
	}

	/**
	 * 获取 conf/userstyle.css 的内容 · Get the contents of conf/userstyle.css
	 * 
	 * @version	1.0.0, beta (210615)
	 * @since	1.0.0, beta (210615)
	 */
	private function getStyleSheet() {
		if (file_exists(USERSTYLE_CSS)) {
			return (string)(file_get_contents(USERSTYLE_CSS));
		} else {
			return '';
		}
	}
}