
<?php
  if (!empty($news)) {
      foreach($news as $news_details){ ?>
          <div class="div-float-both">
          <hr>
              <div class="row">
                  <div class="col-lg-9">
                      <h2><a href="<?php echo base_url().'news_details/'.$news_details->id?>"><?php echo $news_details->title ?></a> </h2>
                  </div>
                  
                  <div  class="add_news_btn col-lg-3">
                      <a type="button" href="<?php echo base_url().'news/download/'.$news_details->id?>" class="btn btn-primary">Download</a>
                      <?php if($is_logged_in && $logged_in_user_id == $news_details->user_id ) { ?>
                      <a type="button" href="<?php echo base_url().'news/delete/'.$news_details->id?>" class="btn btn-danger">Delete</a>
                      <?php } ?>
                  </div>

              </div>

              <?php
              $post_date =date_timestamp_get(new DateTime($news_details->created));// '1079621429';
              $now = time();
              $units = 3;
              ?>
          <p class="lead">
             <?php echo timespan($post_date, $now, 1);?> ago by <a href="#"><?php echo ucfirst($news_details->first_name)." ".$news_details->last_name ?></a>
          </p>


          <!-- Preview Image -->
          <div class="col-lg-4">
          <?php
          if(!empty($news_details->thumbnail_url)){ ?>
              <img class="img-responsive" src="<?php echo base_url().'upload/'.$news_details->thumbnail_url ?>" alt="">

          <?php } ?>
          </div>
          <div class="col-lg-8">
          <p class="lead newstxt">

              <?php
          $read_more = "<a href='".base_url().'news_details/'.$news_details->id."'>..read more</a>";
          echo word_limiter($news_details->description,50,$read_more) ?>
          </p>
          </div>
          <hr>
          </div>


      <?php  }
  }
?>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete News </h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delete a news </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


