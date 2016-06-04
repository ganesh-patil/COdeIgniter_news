<html>
<head>
    <title>News</title>

    <?php echo link_tag('css/bootstrap.min.css')?>
    <?php echo link_tag('css/news.css')?>
    <script type='text/javascript' src="<?php echo base_url(); ?>js/jquery.js"></script>
</head>
<body>

<?php $this->load->view('header'); ?>

<!-- Page Content -->
<div class="container">
    <?php echo $this->session->flashdata('success');
    echo $this->session->flashdata('error');
    ?>
    <div class="row">

        <!-- Blog Post Content Column -->

        <div class="col-lg-8">
            <?php $this->load->view($partial); ?>
        </div>

        <!-- Blog Sidebar Widgets Column -->
        <div class="col-md-4">

            <!-- Blog Search Well -->
            <div  class="well">
                <a type="button" href="/create_post" class="btn btn-primary">Creat post</a>
            </div>
            <div class="well">
                <h4>Blog Search</h4>
                <div class="input-group">
                    <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                </div>

                <!-- /.input-group -->
            </div>

            <!-- Blog Categories Well -->
            <div class="well">
                <h4>Blog Categories</h4>
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            <li><a href="#">Category Name</a>
                            </li>
                            <li><a href="#">Category Name</a>
                            </li>
                            <li><a href="#">Category Name</a>
                            </li>
                            <li><a href="#">Category Name</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            <li><a href="#">Category Name</a>
                            </li>
                            <li><a href="#">Category Name</a>
                            </li>
                            <li><a href="#">Category Name</a>
                            </li>
                            <li><a href="#">Category Name</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.row -->
            </div>

            <!-- Side Widget Well -->
            <div class="well">
                <h4>Side Widget Well</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
            </div>

        </div>

    </div>
    <!-- /.row -->

    <hr>

    <?php $this->load->view('footer'); ?>

</div>


</body>
</html>