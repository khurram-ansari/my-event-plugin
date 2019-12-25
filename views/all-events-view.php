<?php
$allevents=display_events_from_db();
?>
<div class="container">
<h1 style="text-align:center;">All Events</h1>
<table id="allevents_table">
  <thead class="thead-dark">
    <tr>
        <th scope="col">S No</th>
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
       $i=1;
       foreach($allevents as $key=>$value){
           ?>
           <tr>
           <td><?php echo $i; ?></td>
           <td><?php echo $value['title']; ?></td>
           <td><?php echo substr($value['description'],0,20); ?></td>
           <td><?php echo $value['slug']; ?></td>
           <td><?php echo $value['date']; ?></td>
           <td><img src="<?php echo $value['thumb']; ?>" style="height:50px;width:50px;"></td>
           <td><a class="btn btn-primary" href="admin.php?page=edit-event-plugin&edit_id=<?php echo $value['id']?>">Edit</a>&nbsp;<a href="javascript:void(0)" class="btn btn-danger deleteeventbtn" id="deleteeventbtn" data-id="<?php echo $value['id']?>" data-slug="<?php echo $value['slug']?>">Delete</a></td>
           </tr>
           <?php
           $i++;
       }
   }
   ?>
  </tbody>
</table>
</div>