<?php
	$view->extend('SmartCafeAdminBundle::admin.'.$themeStyle.'.html.php') 
?>
<?php $view['slots']->start('js') ?>
<script type="text/javascript" src="/web/js/SmartCafe/admin/coffee.table.view.js" ></script>
<script>
	
</script>
<?php $view['slots']->stop() ?>
<!-- Main Content Here -->
<?php $view['slots']->start('body') ?>
<div class="portlet light">
	<div class="portlet-body">
		<?php 
			$run = 1;
			$strTable = '';
			foreach($tableList as $item){
				$tableName = isset($item->coffee_table_name) ? $item->coffee_table_name : 'Table';
				$timeOrdered = isset($item->time_ordered) ? $item->time_ordered : '';
				$ordering = isset($item->ordering) ? $item->ordering : '';
				$strOrder = '';
				if(!empty($ordering)){
					foreach ($ordering as $iOrder){
						$strOrder .= (($strOrder == '') ? '' : ', ') . $iOrder->coffee_menu_name;
					}
				} else {
					$strOrder = 'Empty menu';
				}
				if($run == 1){
					$strTable .= '<div class="row">';
				}
				$strTable .= '
					<div class="col-md-3">
						<div class="top-news">
							<a href="javascript:;" class="btn red">
							<span>
							'.$tableName.' </span>
							<em>Ordered on: '.(!empty($timeOrdered) ? date('H:i:s', $timeOrdered) : 'None').'</em>
							<em>
							<i class="fa fa-tags"></i>
							'.$strOrder.'</em>
							<i class="fa fa-coffee top-news-icon"></i>
							</a>
						</div>
					</div>
				';
				if($run == 4){
					$strTable .= '</div><div class="clearfix"></div>'; $run = 0;
				}
				$run++;
			}
			echo $strTable;
		?>
		<div class="row">
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn red">
					<span>
					Metronic News </span>
					<em>Posted on: April 16, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					Money, Business, Google </em>
					<i class="fa fa-coffee top-news-icon"></i>
					</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn green">
					<span>
					Top Week </span>
					<em>Posted on: April 15, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					Internet, Music, People </em>
					<i class="fa fa-music top-news-icon"></i>
					</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn blue">
					<span>
					Gold Price Falls </span>
					<em>Posted on: April 14, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					USA, Business, Apple </em>
					<i class="fa fa-globe top-news-icon"></i>
					</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn yellow">
					<span>
					Study Abroad </span>
					<em>Posted on: April 13, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					Education, Students, Canada </em>
					<i class="fa fa-book top-news-icon"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn red">
					<span>
					Metronic News </span>
					<em>Posted on: April 16, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					Money, Business, Google </em>
					<i class="fa fa-coffee top-news-icon"></i>
					</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn green">
					<span>
					Top Week </span>
					<em>Posted on: April 15, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					Internet, Music, People </em>
					<i class="fa fa-music top-news-icon"></i>
					</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn blue">
					<span>
					Gold Price Falls </span>
					<em>Posted on: April 14, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					USA, Business, Apple </em>
					<i class="fa fa-globe top-news-icon"></i>
					</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="top-news">
					<a href="javascript:;" class="btn yellow">
					<span>
					Study Abroad </span>
					<em>Posted on: April 13, 2013</em>
					<em>
					<i class="fa fa-tags"></i>
					Education, Students, Canada </em>
					<i class="fa fa-book top-news-icon"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $view['slots']->stop() ?>