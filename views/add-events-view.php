<?php wp_enqueue_media(); ?>
<div class="container">
<h1 style="text-align:center;">Add New Event</h1>
<form  action="#" id="addeventform" >
  <div class="form-group">
    <label for="txttitle">Title</label>
    <input type="text" name="txttitle" class="form-control" required id="txttitle" placeholder="Enter Event Title" >
  </div>
  <div class="form-group">
  <label for="txtdescription">Description</label>
    <textarea class="form-control" name="txtdescription" required id="txtdescription" rows="3" placeholder="Enter Event Description"></textarea>
  </div>
  <div class="form-group">
    <label for="txtdate">Date</label>
    <input type="date" name="txtdate" class="form-control" required id="txtdate" placeholder="Enter Event Date" >
  </div>
  <div class="form-group">
    <label for="txtthumb">Thumb</label>
    <input type="button" value="Upload Thumb Image" class="form-control btn btn-primary" id="txtthumb" >
    <img id="thumbimg"  src="">
    <input type="hidden" id="thumburl" name="thumburl" >
    <span class="thumberror" id="thumberror"></span>
  </div>
  <div class="form-group">
    <label for="txtslug">Slug</label>
    <input type="text" name="txtslug" class="form-control" required id="txtslug" placeholder="Enter Event Slug" >
  </div>
  <button type="submit" id="addeventbtn" class="btn btn-primary">Submit</button>
</form></div>
