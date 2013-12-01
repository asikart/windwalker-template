<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_flower
 * @author      Simon ASika <asika32764@gmail.com>
 * @copyright   Copyright (C) 2013 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

include_once AKPATH_COMPONENT . '/viewitem.php';

/**
 * View class for a item edit of Flower.
 *
 * @package     Joomla.Site
 * @subpackage  com_flower
 */
class FlowerViewSakura extends AKViewItem
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since    1.6
	 */
	protected $text_prefix = 'COM_FLOWER';

	/**
	 * Item to edit.
	 *
	 * @var array
	 */
	protected $item;

	/**
	 * Model state to get some configuration.
	 *
	 * @var JRegistry
	 */
	protected $state;

	/**
	 * The Component option name.
	 *
	 * @var    ing
	 */
	protected $option = 'com_flower';

	/**
	 * The URL view list variable.
	 *
	 * @var    ing
	 */
	protected $list_name = 'sakuras';

	/**
	 * The URL view item variable.
	 *
	 * @var    ing
	 */
	protected $item_name = 'sakura';

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   12.2
	 */
	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->state    = $this->get('State');
		$this->params   = $this->state->get('params');
		$this->item     = $this->get('Item');
		$this->category = $this->get('Category');
		$this->canDo    = FlowerHelper::getActions();

		$layout = $this->getLayout();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		if ($layout == 'edit')
		{
			$this->form         = $this->get('Form');
			$this->fields_group = $this->get('FieldsGroup');
			$this->fields       = $this->get('FieldsName');

			parent::displayWithPanel($tpl);

			return true;
		}

		// Prepare setting data
		$this->item = new JObject(get_object_vars($this->item));
		$item       = $this->item;

		// Link
		// =====================================================================================
		$item->link = new JURI("index.php?option=com_flower&view=sakura&id={$item->id}");
		$item->link->setVar('alias', $item->get('alias'));
		$item->link->setVar('catid', $item->get('catid'));
		$item->link = JRoute::_((string) $item->link);

		// Dsplay Data
		// =====================================================================================
		$item->created_user = JFactory::getUser($item->created_by)->get('name');
		$item->cat_title    = isset($this->category) ? $this->category->title : null;

		if ($item->modified == '0000-00-00 00:00:00')
		{
			$item->modified = '';
		}

		// Can Edit
		// =====================================================================================
		if (!$user->get('guest'))
		{
			$userId = $user->get('id');
			$asset  = 'com_flower.sakura.' . $item->id;

			// Check general edit permission first.
			if ($user->authorise('core.edit', $asset))
			{
				$this->params->set('access-edit', true);
			}
			// Now check if edit.own is available.
			elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
			{
				// Check for a valid user and that they are the owner.
				if ($userId == $item->created_by)
				{
					$this->params->set('access-edit', true);
				}
			}
		}

		// View Level
		// =====================================================================================
		if ($access = $this->state->get('filter.access'))
		{
			// If the access filter has been set, we already know this user can view.
			$this->params->set('access-view', true);
		}
		else
		{
			// If no access filter is set, the layout takes some responsibility for display of limited information.
			$user   = JFactory::getUser();
			$groups = $user->getAuthorisedViewLevels();

			if (!$item->get('catid') || empty($this->category->access))
			{
				$this->params->set('access-view', in_array($item->get('access'), $groups));
			}
			else
			{
				$this->params->set('access-view', in_array($item->access, $groups) && in_array($this->category->access, $groups));
			}
		}

		// Publish Date
		// =====================================================================================
		$pup  = JFactory::getDate($item->get('publish_up'), JFactory::getConfig()->get('offset'))->toUnix(true);
		$pdw  = JFactory::getDate($item->get('publish_down'), JFactory::getConfig()->get('offset'))->toUnix(true);
		$now  = JFactory::getDate('now', JFactory::getConfig()->get('offset'))->toUnix(true);
		$null = JFactory::getDate('0000-00-00 00:00:00', JFactory::getConfig()->get('offset'))->toUnix(true);

		if (($now < $pup && $pup != $null) || ($now > $pdw && $pdw != $null))
		{
			$item->published = 0;
		}

		// Plugins
		// =====================================================================================
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('content');

		$item->text = $this->params->get('show_intro', 1) ? $item->introtext . $item->fulltext = $item->fulltext : $item->fulltext;
		$results    = $dispatcher->trigger('onContentPrepare', array('com_flower.sakura', &$item, &$this->params, 0));

		$item->event                    = new stdClass;
		$results                        = $dispatcher->trigger('onContentAfterTitle', array('com_flower.sakura', &$item, &$this->params, 0));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results                           = $dispatcher->trigger('onContentBeforeDisplay', array('com_flower.sakura', &$item, &$this->params, 0));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results                          = $dispatcher->trigger('onContentAfterDisplay', array('com_flower.sakura', &$item, &$this->params, 0));
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		// Params
		// =====================================================================================
		// Merge sakura params. If this is single-sakura view, menu params override article params
		// Otherwise, sakura params override menu item params
		$active       = $app->getMenu()->getActive();
		$temp         = clone ($this->params);
		$item->params = new JRegistry($item->params);

		// Check to see which parameters should take priority
		if ($active)
		{
			$currentLink = $active->link;

			// If the current view is the active item and an sakura view for this sakura,
			// then the menu item params take priority
			if (strpos($currentLink, 'view=sakura') && (strpos($currentLink, '&id=' . (string) $item->id)))
			{
				// $item->params are the sakura params, $temp are the menu item params
				// Merge so that the menu item params take priority
				$item->params->merge($temp);

				// Load layout from active query (in case it is an alternative menu item)
				if (isset($active->query['layout']))
				{
					$this->setLayout($active->query['layout']);
				}
			}
			else
			{
				// Current view is not a single sakura, so the sakura params take priority here
				// Merge the menu item params with the sakura params so that the sakura params take priority
				$temp->merge($item->params);
				$this->params = $temp;

				// Check for alternative layouts (since we are not in a single-sakura menu item)
				// Single-sakura menu item layout takes priority over alt layout for an sakura
				if ($layout = $this->params->get('sakura_layout'))
				{
					$this->setLayout($layout);
				}

				// If not Active, set Title
				$this->setTitle($item->get('title'));
			}
		}
		else
		{
			// Merge so that article params take priority
			$temp->merge($this->params);
			$this->params = $temp;

			// Check for alternative layouts (since we are not in a single-article menu item)
			// Single-article menu item layout takes priority over alt layout for an article
			if ($layout = $this->params->get('sakura_layout'))
			{
				$this->setLayout($layout);
			}

			// If not Active, set Title
			$this->setTitle($item->get('title'));
		}

		$item->params = $this->params;

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 */
	protected function addToolbar()
	{
		AKToolBarHelper::title('Sakura' . ' ' . JText::_('COM_FLOWER_TITLE_ITEM_EDIT'), 'article-add.png');

		parent::addToolbar();
	}

	/**
	 * Set page title by JDocument.
	 *
	 * @param  string $title Title.
	 *
	 * @return void
	 */
	public function setTitle($title = '')
	{
		parent::setTitle($title);
	}

	/**
	 * Show or hide some fields setting.
	 *
	 * @return void
	 */
	public function handleFields()
	{
		$form = $this->form;

		parent::handleFields();

		// For Joomla! 3.0
		if (JVERSION >= 3)
		{
			// $form->removeField('name', 'fields');
		}
		else
		{
			// $form->removeField('name', 'fields');
		}
	}
}
