<?php echo link_tag('css/login.css')?>
<div class="modal-dialog">
    <div class="loginmodal-container">
        <h1>Enter email address</h1><br>
        <?php echo form_open(base_url('login/forgot_password'));

        echo form_label('Email', 'email'); ?><span style="color: red">*</span>
        <?php echo form_input(array(
            'name'          => 'email',
            'value'         => set_value('email'),
            'placeholder'   => 'Email',

        ));
        echo form_error('email','<div class="error">', '</div>');
        ?>

        <?php echo form_submit('Submit','Send',array(
            'name'          => 'submit',
            'class'         => 'login loginmodal-submit',

        )); ?>
        <?php echo form_close();?>

    </div>
</div>



