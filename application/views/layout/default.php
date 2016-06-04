<html>
<head>
    <title>News</title>

    <?php echo link_tag('css/bootstrap.min.css')?>
    <?php echo link_tag('css/news.css')?>
    <script type='text/javascript' src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>js/custom.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
</head>
<body>

<?php $this->load->view('header'); ?>

<!-- Page Content -->
<div class="container">
    <?php if ($this->session->flashdata('success') ) { ?>
        <div class="alert alert-success"  id="hideDiv">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
   <?php  } ?>
    <?php if ($this->session->flashdata('error') ) { ?>
        <div class="alert alert-danger"  id="hideDiv">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php  } ?>

    <div class="row">

        <!-- Blog Post Content Column -->

           <div class="col-lg-12">
               <?php if($this->uri->segment(2)  != 'add') { ?>
                   <div  class="add_news_btn">
                       <a type="button" href="<?php echo base_url().'news/add'?>" class="btn btn-primary">Add News</a>
                   </div>
               <?php   }  ?>
               <?php $this->load->view($partial); ?>

           </div>






        <!-- Blog Sidebar Widgets Column -->


    </div>
    <!-- /.row -->

    <hr>

    <?php $this->load->view('footer'); ?>

</div>


</body>
</html>