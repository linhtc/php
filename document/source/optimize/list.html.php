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
				<table class="table table-hover table-striped" style="margin-top: 15px;">
					<thead>
					<tr>
						<th>No.</th>
						<?php if(isset($right['delete'])):?>
							<th style=""><input type="checkbox" name="checkall" id="checkAll" /></th>
						<?php endif;?>
						<th>Program Name</th>
						<th>Customer</th>
						<?php if(isset($right['edit'])):?>
							<th><b>Edit</b></th>
						<?php endif;?>
						<?php if(isset($right['delete']) && $isadmin):?>
							<th style="text-align:center;">Delete</th>
						<?php endif;?>
					</tr>
					</thead>
					<tbody id="">
					<?php $i=$pos; 
						foreach($list as $item): 
							$customerid = $item['customerid'];
							$customer_name = $item['customer_name'];
							$site_name = $item['site_name'];
							$id_edit = $item['id'];
							$attr = new stdClass();
							$attr->customerid = $customerid;
							$attr->site_name = $site_name;
							$attr->id_edit = $id_edit;
					?>
						<tr>
							<td><?php echo ($i);?></td>
							<?php if(isset($right['delete'])):?>
								<td style=""><input type="checkbox" name="check" class="check" value="<?php echo $item['id'];?>" /></td>
							<?php endif;?>
							<td class=""><?php echo $site_name; ?></td>
							<td><?php echo $customer_name; ?></td>
							<?php if(isset($right['edit'])):?>
							<td>
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