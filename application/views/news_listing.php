<script type='text/javascript' src="<?php echo base_url(); ?>js/news.js"></script>
<?php
  if (!empty($news)) { ?>
      <div class="div-float-both news-header">
          <h2>Breaking News</h2>
      </div>

      <?php foreach($news as $news_details){ ?>
          <div class="div-float-both">
          <hr>
              <div class="row">
                  <div class="col-lg-9">
                      <h2><a href="<?php echo base_url().'news_details/'.$news_details->id?>"><?php echo $news_details->title ?></a> </h2>
                  </div>
                  
                  <div  class="add_news_btn col-lg-3">
                      <?php if($is_logged_in && $logged_in_user_id == $news_details->user_id ) { ?>
                      <a type="button" href="<?php echo base_url().'news/delete/'.$news_details->id?>" class="btn btn-danger delete-news"  >Delete</a>
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
              <?php if(!empty($news_details->thumbnail_url)) { ?>
              <div class="col-lg-4 listing-image" >
                  <img class="img-responsive" src="<?php echo base_url().'upload/'.$news_details->thumbnail_url ?>" alt="">
              </div>
              <div class="col-lg-8">
                  <p class="lead newstxt">
                      <?php
                  $read_more = "<a href='".base_url().'news_details/'.$news_details->id."'>..read more</a>";
                  echo word_limiter($news_details->description,80,$read_more) ?>
                  </p>
              </div>

              <?php } else { ?>
              <div class="col-lg-12">
                <p class="lead newstxt">

              <?php
              $read_more = "<a href='".base_url().'news_details/'.$news_details->id."'>..read more</a>";
              echo word_limiter($news_details->description,80,$read_more) ?>
              </p>
              </div>
          <?php     } ?>
          <hr>
          </div>
          <br><br>

      <?php  }
  }


$this->load->view('news_confirm');
?>


