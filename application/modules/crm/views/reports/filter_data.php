<?php 
$j = 0;
if(isset($crm_list) && !empty($crm_list)){
foreach($crm_list as $value){
    $j++;
?>
<tr id="row-<?php echo  $value['id']; ?>">
    <td><?php echo $j ?></td>
    <td><?php echo $value['name']; ?></td>
    <td><?php echo $value['project_name']; ?></td>
    <td><?php echo $value['company_name']; ?></td>
    <td><?php echo $value['email']; ?></td>
  
    <td><?php echo $value['customer_type']; ?></td>
    <td><?php echo $value['fname'].' '.$value['lname']; ?></td>
</tr>
<?php }}else{ echo '<tr><td colspan="7"><h3>No Record Found</h3></td></tr>'; }?>