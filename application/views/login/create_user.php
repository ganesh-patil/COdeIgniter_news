<?php echo link_tag('css/login.css')?>
<div class="modal-dialog">
      <div class="loginmodal-container">
            <h1>Enter Your Password</h1><br>
            <?php $hidden = array('id' => $id); ?>
            <?php echo form_open('' ,array('class'=>''),$hidden);
            echo form_label('Password', 'password'); ?><span style="color: red">*</span>
            <?php echo form_password(array(
                'name'          => 'password',
                'value'         => set_value('password'),
                'placeholder'   => 'Password',
            ));
            echo form_error('password','<div class="error">', '</div>');
            ?>
            <?php
            echo form_label('Confirm password', 'password_confirm'); ?><span style="color: red">*</span>
            <?php echo form_password(array(
                'name'          => 'password_confirm',
                'value'         => set_value('password_confirm'),
                'placeholder'   => 'Confirm Password',
            ));
            echo form_error('password_confirm','<div class="error">', '</div>');
            ?>
            <?php echo form_submit('Save','save',array(
                'name'          => 'submit',
                'class'         => 'login loginmodal-submit',

            )); ?>
            <?php echo form_close();?>
      </div>
</div>



