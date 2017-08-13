<?php
$controller = ModuleHelper::getMainController("visitor_country");
$data = $controller->getAllCountries();
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th><?php translate("country");?>
</th>
			<th><?php translate("visitor_count");?></th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach($data as $dataset){?>
		<tr>
			<td><?php Template::escape($dataset->name);?></td>
			<td class="text-right"><?php echo $dataset->value?></td>
		</tr>
		<?php }?>
	</tbody>
</table>