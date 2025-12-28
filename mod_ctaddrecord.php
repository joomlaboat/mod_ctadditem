<?php
/**
 * CustomTables Joomla! 3.x/4.x/5.x/6.x Module
 * @author Ivan komlev <support@joomlaboat.com>
 * @link https://www.joomlaboat.com
 * @license GNU/GPL
 **/

namespace CustomTables;

// no direct access
defined('_JEXEC') or die('Restricted access');

use CustomTables\CT;
use CustomTables\CTUser;
use CustomTables\Params;
use CustomTables\Edit;
use CustomTables\common;
use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Registry\Registry;

jimport('joomla.html.pane');

$path = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_customtables'
	. DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'customtables' . DIRECTORY_SEPARATOR;

require_once($path . 'loader.php');
CustomTablesLoader();

$ct = new CT([], true);
$menu_params = new Registry;//Joomla Specific
$menu_params->loadString($module->params);
$menu_paramsArray = Params::menuParamsRegistry2Array($menu_params);
$ct->Params->setParams($menu_paramsArray);
$ct->Params->ModuleId = $module->id;


$ct->getTable($ct->Params->tableName);
if (!isset($ct->Table->fields) or !is_array($ct->Table->fields))
	return false;

$layout = new Layouts($ct);
$taskObjectName = 'task' . $module->id;
$task = common::inputPostCmd($taskObjectName);
$result = $layout->renderMixedLayout($ct->Params->editLayout, CUSTOMTABLES_LAYOUT_TYPE_EDIT_FORM, $task);

if ($result['success']) {

	if (isset($result['redirect']))
		common::redirect($result['redirect'],$result['message'], true);

	common::loadJSAndCSS($ct->Params, $ct->Env, $ct->Table->fieldInputPrefix);

	if (!empty($result['style']))
		Factory::getApplication()->getDocument()->addCustomTag('<style>' . $result['style'] . '</style>');

	if (!empty($result['script']))
		Factory::getApplication()->getDocument()->addCustomTag('<script>' . $result['script'] . '</script>');

	if (isset($result['content'])) {

		common::loadJSAndCSS($ct->Params, $ct->Env, $ct->Table->fieldInputPrefix);
		echo $result['content'];
	}
} else {
	common::enqueueMessage($result['message'], 'error');
}
