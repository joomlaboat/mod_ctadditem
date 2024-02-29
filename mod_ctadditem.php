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
use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

$path = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_customtables'
	. DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'customtables' . DIRECTORY_SEPARATOR;

require_once($path . 'loader.php');
CustomTablesLoader();

$ct = new CT($params, true, $module->id);

if (!$ct->CheckAuthorization(1)) {
	//not authorized
	Factory::getApplication()->enqueueMessage(CTMiscHelper::JTextExtended('COM_CUSTOMTABLES_NOT_AUTHORIZED'), 'error');
	return false;
}

$tableName = $params['establename'];
$ct->getTable($tableName);
if (!isset($ct->Table->fields) or !is_array($ct->Table->fields))
	return false;

$formLink = $ct->Env->WebsiteRoot . 'index.php?option=com_customtables&amp;view=edititem' . ($ct->Params->ItemId != 0 ? '&amp;Itemid=' . $ct->Params->ItemId : '');
//if (!is_null($ct->Params->ModuleId))
$formLink .= '&amp;ModuleId=' . $module->id;

$editForm = new Edit($ct);
$editForm->load();

// Display the template
echo $editForm->render(null, $formLink, 'ctEditForm');
