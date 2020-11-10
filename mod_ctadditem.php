<?php
/**
 * CustomTables Joomla! 3.x Native Component
 * @version 1.0.0
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
		var $params;
		var $Model;
		var $row;
		var $langindex;
		var $userid;
		var $listing_id;
		var $esfields;

		function RenderForm()
		{
			$this->row=array();

			$this->listing_id=0;
			$this->esfields=$this->Model->esfields;
			$this->params=$this->Model->params;

			$user =  JFactory::getUser();
			$this->userid = (int)$user->get('id');
			
			$this->formLink=$WebsiteRoot.'index.php?option=com_customtables&amp;view=edititem'.($this->Model->Itemid!=0 ? '&amp;Itemid='.$this->Model->Itemid : '');//.'&amp;lang='.$lang;
			$this->formName='eseditForm';
			$this->formClass='form-validate form-horizontal well';
			$this->formAttribute=' onsubmit="return checkRequiredFields();"';

			require(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_customtables'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'edititem'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'default.php');
		}
	}
}

$config=array();
$a=new ModCustomTablesViewEditItem;

$a->params=$params;
$a->Model= JModelLegacy::getInstance('EditItem', 'CustomTablesModel', $config);
$a->Model->load($params,true);

$a->RenderForm();