<?php
$allevents=display_events_from_db();
?>
<div class="main-eps">
    <?php
    if (count($allevents)>0){
    foreach($allevents as $key=>$value){
    ?>
    <div class="eps-event-cards">
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
