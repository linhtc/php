<table class="table table-bordered">
	<thead>
		<tr>
			<?php if(isset($permission->delete)):?>
				<th style=""><input type="checkbox" name="checkall" id="checkAll" /></th>
			<?php endif;?>
			<th>#</th>
			<th style="text-align:center;">Customer name</th>
			<th style="text-align:center;">Phone</th>
			<th style="text-align:center;">Address</th>
			<?php if(isset($permission->edit)):?>
				<th>Edit</th>
			<?php endif;?>
			<?php if(isset($permission->delete)):?>
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
				<?php if(isset($right['delete'])):?>
					<td style=""><input type="checkbox" name="check" class="check" value="<?php echo $id_edit;?>" /></td>
				<?php endif;?>
				<td><?php echo ($pos);?></td>
				<td><?php echo $customer_name;?></td>
				<td><?php echo $address;?></td>
				<td><?php echo $phone;?></td>
				<?php if(isset($permission->edit)):?>
					<td>
						<a id="tr_<?php echo $id_edit; ?>" class="edit" datas='<?php echo json_encode($attr); ?>'  style="cursor:pointer; color:#098bdd;">
							<i class="fa fa-edit"></i>
						</a>
					</td>
				<?php endif;?>
				<?php if(isset($permission->delete)):?>
					<td style="text-align:center; ">
						<a style="cursor:pointer; color:#f95a04; font-weight:100;text-align:center;" class="delete" idDel="<?=$id_edit;?>" href="javascript:;">
							<i class="fa fa-times-circle-o"></i>
						</a>
					</td>
				<?php endif;?>
			</tr>
			<?php $pos++; endforeach; ?>
	</tbody>
</table>