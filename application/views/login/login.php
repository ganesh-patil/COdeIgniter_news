
<?php echo link_tag('css/login.css')?>
<div class="modal-dialog">
  <div class="loginmodal-container">
    <h1>Login to Your Account</h1><br>
    <?php echo form_open(base_url('login'));
    echo form_label('Email', 'email'); ?><span style="color: red">*</span>
    <?php echo form_input(array(
        'name'          => 'email',
        'value'         => set_value('email'),
        'placeholder'   => 'Email',

    ));
    echo form_error('email','<div class="error">', '</div>');
    ?>
    <?php
    echo form_label('Password', 'password'); ?><span style="color: red">*</span>
    <?php echo form_password(array(
        'name'          => 'password',
        'value'         => set_value('password'),
        'placeholder'   => 'Password',
    ));
    echo form_error('password','<div class="error">', '</div>');
    ?>
    <?php echo form_submit('Login','login',array(
        'name'          => 'submit',
        'class'         => 'login loginmodal-submit',

    )); ?>
    <?php echo form_close();?>

    <div class="login-help">
      <a href="<?php echo base_url('register')?>">Create account</a> &nbsp;&nbsp; <a href="<?php echo base_url('login/forgot_password')?>">forgot password?</a>
    </div>
  </div>
</div>


