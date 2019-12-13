<?php
$allevents=display_events_from_db();
?>
<html>
<head>
    <title>My Now Amazing Webpage</title>
</head>
<style>
    .slick-slider{
        max-width: 100% !important;
    }
    .jbh{
        background: #fff;
        color: #3498db;
        font-size: 36px;
        height: 400px;
        /*line-height: 200px;*/
        margin: 10px;
        /*padding: 2%;*/
        position: relative;
        text-align: center;
    }
    .event-title{
        color: black;
        font-weight: bold;
    }
    .event-desc{
        color: #6c757d;
        text-align: left;
        font-size: 20px;
        height: 50px;
    }
    .event-date{
        color: #4f4848;
        text-align: left;
        font-size: 15px;
        font-weight: bold;
    }
    .img-cont{
        width: 100%;
    }
    .event-thumb{
        object-fit: contain;
        height: 200px;
        width: 100%;
    }
</style>


<div class="hee">
    <?php
    if (count($allevents)>0){
    foreach($allevents as $key=>$value){
    ?>
    <div class="jbh">
        <div class="img-cont">
            <img src="<?php echo $value['thumb'] ?>" class="event-thumb">
        </div>
        <div class="textcont">
            <a href="<?php echo $value['slug']; ?>"><p class="event-title"><?php echo $value['title'] ?></p></a>
            <p class="event-desc">
                <?php
                $aa=$value['description'];
                if (strlen($aa) >= 40) {
                    echo substr($aa, 0, 40)." ... ";
                }
                else {
                    echo $aa;
                }
                ?>
            </p>
            <p class="event-date">
                <?php
                $newDate   =   date("l M, d, Y", strtotime($value['date']));
                echo $newDate;
               ?>
            </p>


        </div>
    </div>
        <?php
    }
    }
    ?>
</div>
</html>