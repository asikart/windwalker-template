<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_flower
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

if( AKDEBUG ){
	FlowerHelper::_('include.quickedit');
}


// Init some API objects
// ================================================================================
$app 	= JFactory::getApplication() ;
$date 	= JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
$doc 	= JFactory::getDocument();
$uri 	= JFactory::getURI() ;
$user	= JFactory::getUser();
$userId	= $user->get('id');



// List Control
// ================================================================================
$nested		= $this->state->get('items.nested') ;
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$orderCol 	= $this->state->get('list.orderCol', 'a.ordering') ;
$canOrder	= $user->authorise('core.edit.state', 'com_flower');
$saveOrder	= $listOrder == $orderCol || ($listOrder == 'a.lft' && $listDirn == 'asc');
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$show_root	= JRequest::getVar('show_root') ;


// For Joomla!3.0
// ================================================================================
if( JVERSION >= 3 ) {
	if ($saveOrder)
	{
		$method = $nested ? 'saveOrderNestedAjax' : 'saveOrderAjax' ;
		$saveOrderingUrl = 'index.php?option=com_flower&task=sakuras.'.$method.'&tmpl=component';
		JHtml::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, $nested);
	}
}
?>

<!-- List Table -->
<table class="table table-striped adminlist" id="itemList">
	<thead>
		<tr>
			<!--SORT-->
			<?php if( JVERSION >= 3 ): ?>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', $orderCol, $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
			</th>
			<?php endif; ?>
			
			<!--CHECKBOX-->
			<th width="1%" class="center">
				<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>
			
			<!--TITLE-->
			<th class="center">
				<?php echo JHtml::_('grid.sort',  'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
			</th>
			
			<!--PUBLISHED-->
			<th width="5%" class="nowrap center">
				<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
			</th>
			
			<!--ORDERING-->
			<?php if( JVERSION < 3 ): ?>
			<th width="10%" class="center">
				<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', $orderCol, $listDirn, $listOrder); ?>
				<?php if ($canOrder && $saveOrder) :?>
					<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'sakuras.saveorder'); ?>
				<?php endif; ?>
			</th>
			<?php endif; ?>
			
			<!--CATEGORY-->
			<th width="10%"  class="center">
				<?php echo JHtml::_('grid.sort',  'JCATEGORY', 'b.title', $listDirn, $listOrder); ?>
			</th>
			
			<!--ACCESS VIEW LEVEL-->
			<th width="5%" class="center">
				<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ACCESS', 'd.title', $listDirn, $listOrder); ?>
			</th>
			
			<!--CREATED-->
			<th width="10%" class="center">
				<?php echo JHtml::_('grid.sort',  'JDATE', 'a.created', $listDirn, $listOrder); ?>
			</th>
			
			<!--USER-->
			<th width="10%" class="center">
				<?php echo JHtml::_('grid.sort',  'JAUTHOR', 'c.name', $listDirn, $listOrder); ?>
			</th>
			
			<!--LANGUAGE-->
			<th width="5%" class="center">
				<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_LANGUAGE', 'e.title', $listDirn, $listOrder); ?>
			</th>
			
			<!--ID-->
			<th width="1%" class="nowrap center">
				<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
			</th>

		</tr>
	</thead>
	
	<!--PAGINATION-->
	<tfoot>
		<tr>
			<td colspan="15">
				<div class="pull-left">
					<?php echo $this->pagination->getListFooter(); ?>
				</div>
				
				<?php if( JVERSION >= 3 ): ?>
				<!-- Limit Box -->
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<?php endif; ?>
			</td>
		</tr>
	</tfoot>
	
	
	<tbody>
	<?php foreach ($this->items as $i => $item) :
		
		$item = new JObject($item);
		
		$ordering	= ($listOrder == $orderCol);
		$canEdit	= $user->authorise('core.edit',			'com_flower.sakura.'.$item->a_id);
		$canCheckin	= $user->authorise('core.manage',		'com_flower.sakura.'.$item->a_id) || $item->a_checked_out == $userId || $item->a_checked_out == 0;
		$canChange	= $user->authorise('core.edit.state',	'com_flower.sakura.'.$item->a_id) && $canCheckin;
		$canEditOwn = $user->authorise('core.edit.own',		'com_flower.sakura.'.$item->a_id) && $item->get('c_id') == $userId;
		
		
		
		// Nested ordering
		// ================================================================
		if($nested){
			
			if($item->a_id == 1) {
				$item->a_title = JText::_('JGLOBAL_ROOT') ;
				$canEdit 	= false ;
				$canChange 	= false ;
				$canEditOwn = false ;
			}
			
			$orderkey = array_search($item->a_id, $this->ordering[$item->a_parent_id]);
		
			// Get the parents of item for sorting
			if ($item->a_level > 1)
			{
				$parentsStr = "";
				$_currentParentId = $item->a_parent_id;
				$parentsStr = " ".$_currentParentId;
				for ($n = 0; $n < $item->a_level; $n++)
				{
					foreach ($this->ordering as $k => $v)
					{
						$v = implode("-", $v);
						$v = "-".$v."-";
						if (strpos($v, "-" . $_currentParentId . "-") !== false)
						{
							$parentsStr .= " ".$k;
							$_currentParentId = $k;
							break;
						}
					}
				}
			}
			else
			{
				$parentsStr = "";
			}
		}
		// ================================================================
		// Nested ordering END

		?>
		<tr class="row<?php echo $i % 2; ?>"
			<?php if( $nested && JVERSION >= 3 ): ?>
				sortable-group-id="<?php echo $item->a_parent_id ;?>" item-id="<?php echo $item->a_id?>" parents="<?php echo $parentsStr?>" level="<?php echo $item->a_level?>"
			<?php elseif( JVERSION >= 3 ): ?>
				sortable-group-id="<?php echo $item->a_catid ;?>"
			<?php endif; ?>
		>
		<?php if( JVERSION >= 3 ): ?>
			<!-- Drag sort for Joomla!3.0 -->
			<td class="order nowrap center hidden-phone">
			<?php if ($canChange) :
				$disableClassName = '';
				$disabledLabel	  = '';

				if (!$saveOrder) :
					$disabledLabel    = JText::_('JORDERINGDISABLED');
					$disableClassName = 'inactive tip-top';
				endif; ?>
				<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
					<i class="icon-menu"></i>
				</span>
				<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $nested ? $orderkey + 1 : $item->a_ordering;?>" class="width-20 text-area-order " />
			<?php else : ?>
				<span class="sortable-handler inactive" >
					<i class="icon-menu"></i>
				</span>
			<?php endif; ?>
			</td>
		<?php endif; ?>
		
			<!--CHECKBOX-->
			<td class="center">
				<?php echo JHtml::_('grid.id', $i, $item->a_id); ?>
			</td>
			
			<!--TITLE-->
			<td class="n/owrap has-context quick-edit-wrap"
				quick-edit-id="<?php echo $item->get('a_id'); ?>"
				quick-edit-field="title"
			>
				
				<!-- Nested dashs -->
				<?php if( $nested ): ?>
				<div class="pull-left fltlft">
					<?php $offset = $show_root ? 0 : 1 ; ?>
					<?php echo str_repeat('<span class="gi">&mdash;</span>', $item->a_level - $offset) ; ?>
				</div>
				<?php endif; ?>
				
				<div class="pull-left fltlft">
				
				<div class="item-title" >
					<!-- Checkout -->
					<?php if ($item->get('a_checked_out')) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->get('c_name'), $item->get('a_checked_out_time'), 'sakuras.', $canCheckin); ?>
					<?php endif; ?>
					
					
					<!-- Title -->
					<?php if ($canEdit || $canEditOwn) : ?>
						<a class="quick-edit-content" href="<?php echo JRoute::_('index.php?option=com_flower&task=sakura.edit&id='.$item->a_id); ?>">
							<?php echo $item->get('a_title'); ?>
						</a>
					<?php else: ?>
						<?php echo $item->get('a_title'); ?>
					<?php endif; ?>
				</div>
				
				<!-- Sub Title -->
				<?php if( JVERSION >= 3 ): ?>
				<div class="small">
					<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape( $item->get('a_alias') ));?>
				</div>
				<?php else: ?>
				<p class="smallsub">
					<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape( $item->get('a_alias') ));?>
				</p>
				<?php endif; ?>
				</div>
				
				
				<?php if( JVERSION >= 3 ): ?>
				<!-- Title Edit Button -->
				<div class="pull-left">
					<?php
						// Create dropdown items
						if($canEdit || $canEditOwn){
							JHtml::_('dropdown.edit', $item->a_id, 'sakura.');
							JHtml::_('dropdown.divider');
						}
						
						
						if($canChange || $canEditOwn) {
							if ($item->a_published) :
							JHtml::_('dropdown.unpublish', 'cb' . $i, 'sakuras.');
							else :
								JHtml::_('dropdown.publish', 'cb' . $i, 'sakuras.');
							endif;
							JHtml::_('dropdown.divider');
						}
						
						
						if ($item->a_checked_out && $canCheckin) :
							JHtml::_('dropdown.checkin', 'cb' . $i, 'sakuras.');
						endif;
						
						if($canChange || $canEditOwn) {
							if ($trashed) :
								JHtml::_('dropdown.untrash', 'cb' . $i, 'sakuras.');
							else :
								JHtml::_('dropdown.trash', 'cb' . $i, 'sakuras.');
							endif;
						}
						
						// Render dropdown list
						echo JHtml::_('dropdown.render');
						?>
				</div>
				<?php endif; ?>
			</td>
			
			<!--PUBLISHED-->
			<td class="center">
				<?php echo JHtml::_('jgrid.published', $item->a_published, $i, 'sakuras.', $canChange, 'cb', $item->get('a_publish_up'), $item->get('a_publish_down')); ?>
			</td>
			
			<!--J25 ORDERING-->
			<?php if( JVERSION < 3 ): ?>
			<td class="order">
				<?php if ($canChange) : ?>
					<?php if ($saveOrder) :?>
						<?php if ($listDirn == 'asc') : ?>
							<span><?php echo $this->pagination->orderUpIcon($i, true, 'sakuras.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'sakuras.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php elseif ($listDirn == 'desc') : ?>
							<span><?php echo $this->pagination->orderUpIcon($i, true, 'sakuras.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'sakuras.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php endif; ?>
					<?php endif; ?>
					<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $nested ? $orderkey + 1 : $item->get('a_ordering');?>" <?php echo $disabled ?> class="text-area-order input-mini" />
				<?php else : ?>
					<?php echo $nested ? $orderkey + 1 : $item->get('a_ordering');?>
				<?php endif; ?>
			</td>
			<?php endif; ?>
			
			<!--CATEGORY-->
			<td class="center">
				<?php echo $item->get('b_title'); ?>
			</td>
			
			<!--ACCESS VIEW LEVEL-->
			<td class="center">
				<?php echo $item->get('d_title'); ?>
			</td>
			
			<!--CREATED-->
			<td class="center">
				<?php echo JHtml::_('date', $item->get('a_created'), JText::_('DATE_FORMAT_LC4')); ?>
			</td>
			
			<!--USER-->
			<td class="center">
				<?php echo $item->get('c_name'); ?>
			</td>
			
			<!--LANGUAGE-->
			<td class="center">
				<?php if ($item->get('a_language')=='*'):?>
					<?php echo JText::alt('JALL', 'language'); ?>
				<?php else:?>
					<?php echo $item->get('e_title') ? $this->escape($item->e_title) : JText::_('JUNDEFINED'); ?>
				<?php endif;?>
			</td>
			
			<!--ID-->
			<td class="center">
				<span
					<?php if( $nested ): ?>
					class="hasTip" title="<?php echo $item->a_lft.'-'.$item->a_rgt; ?>"
					<?php endif; ?>
				><?php echo (int) $item->get('a_id'); ?></span>
			</td>

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>