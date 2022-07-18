<?php
/**
 * CustomTables Joomla! 3.x & 4.x Native Component
 * @author Ivan komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @license GNU/GPL
 **/

// no direct access
use CustomTables\CT;
use CustomTables\CTUser;

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_customtables'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'edititem.php');

if(!class_exists('ModCustomTablesViewEditItem'))
{
	class ModCustomTablesViewEditItem extends JViewLegacy
	{
		var $Model;

		function RenderForm()
		{
			$row=array();

			$mainframe = JFactory::getApplication();
			if($mainframe->getCfg( 'sef' ))
			{
				$WebsiteRoot=JURI::root(true);
				if($WebsiteRoot=='' or $WebsiteRoot[strlen($WebsiteRoot)-1]!='/') //Root must have the slash character "/" in the end
					$WebsiteRoot.='/';
			}
			else
				$WebsiteRoot='';

			$this->formLink=$WebsiteRoot.'index.php?option=com_customtables&amp;'
				.'view=edititem'.($this->Model->ct->Env->Itemid!=0 ? '&amp;Itemid='.$this->Model->ct->Env->Itemid : '');
				
			$this->formName='eseditForm';

			CTViewEdit($this->Model->ct, $row, $this->Model->pagelayout, $this->Model->BlockExternalVars,$this->formLink,$this->formName);
		}
	}
}

$path = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_customtables'
    . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'customtables' . DIRECTORY_SEPARATOR;

require_once($path.'loader.php');
CTLoader();

$config=array();

$Model = JModelLegacy::getInstance('EditItem', 'CustomTablesModel', $config);

$ct = new CT($params, true, $module->id);

$Model->load($ct,true);

if (!CTUser::CheckAuthorization($ct)) {
    //not authorized
    Factory::getApplication()->enqueueMessage(JoomlaBasicMisc::JTextExtended('COM_CUSTOMTABLES_NOT_AUTHORIZED'), 'error');
    return false;
}

if (!isset($ct->Table->fields) or !is_array($ct->Table->fields))
    return false;

$formLink = $ct->Env->WebsiteRoot . 'index.php?option=com_customtables&amp;view=edititem' . ($ct->Params->ItemId != 0 ? '&amp;Itemid=' . $ct->Params->ItemId : '');
if (!is_null($ct->Params->ModuleId))
    $formLink .= '&amp;ModuleId=' . $ct->Params->ModuleId;

CTViewEdit($ct, $Model->row, $Model->pagelayout, $formLink, 'eseditForm');
