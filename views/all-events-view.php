<?php 
global $wpdb;
$allevents=$wpdb->get_results(
    $wpdb->prepare("select * from ". get_table_name() ." order by date ASC",""),ARRAY_A);
?>
<div class="container">
<h1 style="text-align:center;">All Events</h1>
<table id="allevents_table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Slug</th>
      <th scope="col">Date</th>
      <th scope="col">Thumbnail</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <tbody>
   <?php 
   if (count($allevents)>0){
       foreach($allevents as $key=>$value){
           ?>
           <tr>
           <td><?php echo $value['id']; ?></td>
           <td><?php echo $value['title']; ?></td>
           <td><?php echo $value['description']; ?></td>
           <td><?php echo $value['slug']; ?></td>
           <td><?php echo $value['date']; ?></td>
           <td><img src="<?php echo $value['thumb']; ?>" style="height:50px;width:50px;"></td>
           <td><a class="btn btn-primary" href="http://localhost/event_plugin/wp-admin/admin.php?page=edit-event-plugin" data-id="<?php echo $value['id']?>">Edit</a>&nbsp;<button class="btn btn-danger" data-id="<?php echo $value['id']?>">Delete</button></td>



        
        
        
        
        
        </tr> 
           
           
           
           
           <?php
       }
   }
   ?>
  </tbody>
</table>
</div>