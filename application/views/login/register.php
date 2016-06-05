<?php echo link_tag('css/login.css')?>
<div class="modal-dialog">
    <div class="loginmodal-container">
        <h1>Create your Account</h1><br>
        <?php echo form_open(base_url('login/register'));

        echo form_label('First name', 'first_name'); ?><span style="color: red">*</span>
        <?php echo form_input(array(
            'name'          => 'first_name',
            'value'         => set_value('first_name'),
            'placeholder'   => 'First Name',

        ));
        echo form_error('first_name','<div class="error">', '</div>');


        echo form_label('Last name', 'last_name'); ?><span style="color: red">*</span>
        <?php echo form_input(array(
            'name'          => 'last_name',
            'value'         => set_value('last_name'),
            'placeholder'   => 'Last name',

        ));
        echo form_error('last_name','<div class="error">', '</div>');

        echo form_label('Email', 'email'); ?><span style="color: red">*</span>
        <?php echo form_input(array(
            'name'          => 'email',
            'value'         => set_value('email'),
            'placeholder'   => 'Email',

        ));
        echo form_error('email','<div class="error">', '</div>');
        ?>

        <?php echo form_submit('Submit','Register',array(
            'name'          => 'submit',
            'class'         => 'login loginmodal-submit',

        )); ?>
        <?php echo form_close();?>

    </div>
</div>



