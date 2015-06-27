<div class="row">
	<div class="col-md-12">
		<div class="table-responsive list-container">
			<table class="table table-bordered">
				<thead>
					<tr>
						<?php if(isset($permission->delete)):?>
							<th style=""><input type="checkbox" name="checkall" id="checkAll" /></th>
						<?php endif;?>
						<th>#</th>
						<th>Customer name</th>
						<th>Phone</th>
						<th>Address</th>
						<?php if(isset($permission->edit) || $isadmin): ?>
							<th>Edit</th>
						<?php endif;?>
						<?php if(isset($permission->delete) || $isadmin): ?>
							<th>Delete</th>
						<?php endif;?>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($list as $item): 
							$customer_name = $item['customer_name'];
							$address = $item['address'];
							$phone = $item['phone'];
							$id_edit = $item['id'];
							$attr = new stdClass();
							$attr->customer_name = $customer_name;
							$attr->address = $address;
							$attr->phone = $phone;
							$attr->id_edit = $id_edit;
					?>
						<tr >
							<?php if(isset($permission->delete) || $isadmin): ?>
								<td style=""><input type="checkbox" name="check" class="check" value="<?php echo $id_edit;?>" /></td>
							<?php endif; ?>
							<td><?php echo ($pos);?></td>
							<td><?php echo $customer_name;?></td>
							<td><?php echo $address;?></td>
							<td><?php echo $phone;?></td>
							<?php if(isset($permission->edit) || $isadmin): ?>
								<td>
									<a id="tr_<?php echo $id_edit; ?>" class="btn-icon-only edit" style="color:#098bdd;" datas='<?php echo json_encode($attr); ?>' href="javascript:;" title="Edit">
										<i class="fa fa-edit"></i>
									</a>
								</td>
							<?php endif;?>
							<?php if(isset($permission->delete) || $isadmin): ?>
								<td>
									<a class="btn-icon-only delete" style="color:#CB5A5E;" idDel="<?=$id_edit;?>" href="javascript:;" title="Delete">
									<i class="icon-trash"></i>
									</a>
								</td>
							<?php endif;?>
						</tr>
						<?php $pos++; endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="right" style="float: right;">
			<?php echo $pagination; ?>
		</div>
	</div>
</div>
