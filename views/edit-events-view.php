<?php wp_enqueue_media();
$eventid=isset($_GET['edit_id']) ? intval($_GET['edit_id']):0;
global $wpdb;
$event=$wpdb->get_row(
    $wpdb->prepare("select * from ".get_table_name()." where id=%d" ,$eventid),ARRAY_A);
?>
<div class="container">
<h1 style="text-align:center;">Edit Event</h1>
<form  action="#" id="editeventform" >
  <div class="form-group">
    <label for="txttitle">Title</label>
    <input type="text" name="txttitle" class="form-control" required id="txttitle" placeholder="Enter Event Title" value="<?php echo $event['title'];?>">
  </div>
  <div class="form-group">
  <label for="txtdescription">Description</label>
    <textarea class="form-control" name="txtdescription" required id="txtdescription" rows="3" placeholder="Enter Event Description" ><?php echo $event['description'];?></textarea>
  </div>
  <div class="form-group">
      <input type="hidden" id="event_id" value="<?php echo $eventid?>" name="event_id">
    <label for="txtdate">Date</label>
    <input type="date" name="txtdate" class="form-control" required id="txtdate" placeholder="Enter Event Date" value="<?php echo $event['date'];?>">
  </div>
  <div class="form-group">
    <label for="txtthumb">Thumb</label>
    <input type="button" value="Upload Thumb Image" class="form-control btn btn-primary" id="txtthumb" >
    <img id="thumbimg"  src="<?php echo $event['thumb'];?>" style="width: 100px;height: 100px;">
    <input type="hidden" id="thumburl" name="thumburl" >
    <span class="thumberror" id="thumberror"></span>
  </div>
  <div class="form-group">
    <label for="txtslug">Slug</label>
    <input type="text" name="txtslug" class="form-control" required id="txtslug" placeholder="Enter Event Slug" value="<?php echo $event['slug'];?>">
  </div>
  <button type="submit" id="editeventbtn" class="btn btn-primary">Submit</button>
</form></div>
