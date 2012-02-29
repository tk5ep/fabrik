<?php
/**
 * Plugin element to render folder list
 * @package fabrikar
 * @author Rob Clayburn
 * @copyright (C) Rob Clayburn
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

require_once(JPATH_SITE . '/components/com_fabrik/models/element.php');

class plgFabrik_ElementFolder extends plgFabrik_Element {

	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_Element::render()
	 */

	function render($data, $repeatCounter = 0)
	{
		$name = $this->getHTMLName($repeatCounter);
		$id = $this->getHTMLId($repeatCounter);
		$element = $this->getElement();
		$params = $this->getParams();
		$allowAdd = $params->get('allow_frontend_addtodropdown', false);
		$selected = $this->getValue($data, $repeatCounter);
		$errorCSS = $this->elementError != '' ? ' elementErrorHighlight' : '';
		$attribs = 'class="fabrikinput inputbox' . $errorCSS . '"';
		$aRoValues 	= array();
		$path = JPATH_ROOT . '/' . $params->get('fbfolder_path');
		$opts = array();
		if ($params->get('folder_allownone', true))
		{
			$opts[] = JHTML::_('select.option', '', JText::_('NONE'));
		}
		if ($params->get('folder_listfolders', true))
		{
			$folders = JFolder::folders($path);
			foreach ($folders as $folder)
			{
				$opts[] = JHTML::_('select.option', $folder, $folder);
				if (is_array($selected) and in_array($folder, $selected))
				{
					$aRoValues[] = $folder;
				}
			}
		}

	if ($params->get('folder_listfiles', false))
	{
			$files = JFolder::files($path);
			foreach ($files as $file)
			{
				$opts[] = JHTML::_('select.option', $file, $file);
				if (is_array($selected) and in_array($file, $selected))
				{
					$aRoValues[] = $file;
				}
			}
		}

		$str = JHTML::_('select.genericlist', $opts, $name, $attribs, 'value', 'text', $selected, $id);
		if (!$this->editable)
		{
			return implode(', ', $aRoValues);
		}
		return $str;
	}

	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_Element::elementJavascript()
	 */

	function elementJavascript($repeatCounter)
	{
		$id = $this->getHTMLId($repeatCounter);
		$params = $this->getParams();
		$element = $this->getElement();
		$data = $this->getFormModel()->_data;
		$arSelected = $this->getValue($data, $repeatCounter);
		$path = JPATH_ROOT . '/' . $params->get('fbfbfolder_path');
		$folders = JFolder::folders($path);
		$params = $this->getParams();
		$opts = $this->getElementJSOptions($repeatCounter);
		$opts->defaultVal = $element->default;
		$opts->data = $folders;
		$opts = json_encode($opts);
		return "new FbFolder('$id', $opts)";
	}

}
?>