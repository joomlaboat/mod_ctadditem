<?php
/**
 * CustomTables Joomla! 3.x & 4.x Native Component
 * @author Ivan komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @license GNU/GPL
 **/

// no direct access
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

$config=array();
$a=new ModCustomTablesViewEditItem;

$a->Model= JModelLegacy::getInstance('EditItem', 'CustomTablesModel', $config);
$a->Model->load($params,true);
$a->RenderForm();