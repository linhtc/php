<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-reorder"></i>
			<span>
				Summary
			</span>
			<span id="totals" style="margin-left:20px; color:#eff3f5; font-size:14px;">
				<i>Found <?php echo number_format($total) ?> results</i>
				<input id="totalValue" type="number" hidden="true" value="<?php echo $total ?>"/>
			</span>
		</div>
		<div class="tools">
			<!--<a class="" href="javascript:;">
				<button style="margin-top:-11px;  margin-right: -12px; background:none !important" type="button" class="btn blue" id="export_detail" ><span class=" fa fa-file-excel-o"></span></button>
			</a>-->
			<a class="collapse" href="javascript:;"></a>
			<a class="config" href="javascript:;"></a>
			<a class="reload" href="javascript:;"></a>
			<a href="" class="remove" href="javascript:;"></a>
		</div>
        
	</div>
	<div class="portlet-body">
		<div class="scroller" style="max-height:500px; overflow-y: auto">
			<div class="table-responsive table-scrollable" style="border: none;">
				<table id="table-content" class="table table-hover table-striped" style="margin-top: 15px;">
					<thead>
					<tr>
						<?php if(isset($right['delete'])):?>
							<th style=""><input type="checkbox" name="checkall" id="checkAll" /></th>
						<?php endif;?>
						<th>No.</th>
						<th style="text-align:center;">Location</th>
						<th style="text-align:center;">Apply time</th>
						<th style="text-align:center;">UTC</th>
						<th style="text-align:center;">Status</th>
						<?php if(isset($right['edit']) && $isadmin):?>
							<th style="text-align:center;">Edit</th>
						<?php endif;?>	
						<?php if(isset($right['delete']) && $isadmin):?>
							<th style="text-align:center;">Delete</th>
						<?php endif;?>	
					</tr>
					</thead>
					<tbody id="">
					<?php 
						$i=$pos; 
						foreach($list as $item): 
							$location_id = $item['location_id'];
							$customer_id = $item['customer_id'];
							$location_name = $item['location_name'];
							$time_change = $item['time_change'];
							$time_zone = 'UTC '.($item['utc_plus'] == 1 ? '+' : '-').$item['utc_string'];
							$id_edit = $item['id'];
							$status = $item['changed'];
							if($status == 1){
								$status = 'Changed';
							} else {
								$status = 'N/A';
							}
							$attr = new stdClass();
							$attr->customer = $customer_id;
							$attr->location_id = $location_id;
							$attr->apply_date = date('d-M-y', strtotime($time_change));
							$attr->apply_time = date('g:i:s', strtotime($time_change));
							$attr->time_zone = $time_zone;
							$attr->id_edit = $id_edit;
					?>
						<tr >
							<?php if(isset($right['delete'])):?>
								<td style=""><input type="checkbox" name="check" class="check" value="<?php echo $item['id'];?>" /></td>
							<?php endif;?>
							<td style="text-align:center;"><?php echo ($i);?></td>
							<td style="text-align:center;" class="izap_machine_sn"><?php echo $location_name;?></td>
							<td style="text-align:center;" class="izap_machine_sn"><?php echo date('d-M-y H:i:s', strtotime($time_change));?></td>
							<td style="text-align:center;" class="izap_machine_sn"><?php echo $time_zone;?></td>
							<td style="text-align:center;" class="izap_machine_sn"><?php echo $status;?></td>
							<?php if(isset($right['edit'])):?>
								<td style="text-align:center;">
									<a id="tr_<?php echo $item['id']?>" class="edit" datas='<?php echo json_encode($attr); ?>'  style="cursor:pointer; color:#098bdd;">
										<i class="fa fa-edit"></i>
									</a>
								</td>
							<?php endif;?>
							<?php if(isset($right['delete']) && $isadmin):?>
								<td style="text-align:center; ">
									<a style="cursor:pointer; color:#f95a04; font-weight:100;text-align:center;" class="delete" idDel="<?=$id_edit;?>" href="javascript:;">
										 <i class="fa fa-times-circle-o"></i>
									</a>
								</td>
							<?php endif;?>
						</tr>
						<?php $i++; endforeach; ?>
					</tbody>
				</table>
			</div>           
		</div>
        <div class="row">
            <div class="col-md-12">
				<div style="float:right">
					<?php echo $pagination?>
				</div>
			</div>
        </div>
	</div>
</div>