<?php
  if (!empty($news)) { ?>

          <h2><?php echo $news->title ?></h2>
          <?php
          $post_date =date_timestamp_get(new DateTime($news->created));// '1079621429';
          $now = time();
          $units = 3;
          ?>
          <p class="lead">
              <?php echo timespan($post_date, $now, 1);?> ago by <a href="#">Start Bootstrap</a>
          </p>

          <hr>

          <!-- Preview Image -->
          <?php
          if(!empty($news->image_url)){ ?>
              <img class="img-responsive" src="<?php echo base_url().'upload/'.$news->image_url ?>" alt="">

          <?php } ?>

          <p class="lead">
              <?php echo $news->description ?>
          </p>



  <?php }

?>

