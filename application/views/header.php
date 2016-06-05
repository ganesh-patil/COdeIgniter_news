<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url() ?>">News</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if($is_logged_in) {
                    $class = '';
                    if($this->uri->segment(1) == 'my_news') {
                        $class ='active';
                    }

                    ?>
                <li class="<?php echo $class?>">
                    <a href="<?php echo base_url().'my_news'?>">My News</a>
                </li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav login-head">
                <?php if($is_logged_in) { ?>
                    <li>
                        <a href="<?php echo base_url().'logout'?>">Logout</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="<?php echo base_url().'login'?>">Login</a>
                    </li>
                <?php } ?>
                </ul>



        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>