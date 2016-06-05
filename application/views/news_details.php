<?php
  if (!empty($news)) { ?>

          <h2><?php echo $news->title ?></h2>

      <div class="row">
          <div class="col-lg-9">
              <h2<?php echo $news->title ?> </h2>
          </div>

          <div  class="add_news_btn col-lg-3">
              <a type="button" href="<?php echo base_url().'news/download/'.$news->id?>" class="btn btn-primary">Download</a>
              <?php if($is_logged_in && $logged_in_user_id == $news->user_id ) { ?>
                  <a type="button" href="<?php echo base_url().'news/delete/'.$news->id?>" class="btn btn-danger">Delete</a>
              <?php } ?>
          </div>

      </div>
          <?php
          $post_date =date_timestamp_get(new DateTime($news->created));// '1079621429';
          $now = time();
          $units = 3;
          ?>
          <p class="lead">
              <?php echo timespan($post_date, $now, 1);?> ago by <a href="#"><?php echo ucfirst($news->first_name)." ".$news->last_name ?></a>
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

