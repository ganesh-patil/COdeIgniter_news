<?php
    echo form_open_multipart(base_url().'news'.DIRECTORY_SEPARATOR.'add');

?>
<div class="form-group">
    <?php
    echo form_label('News Title', 'title'); ?><span style="color: red">*</span>
    <?php echo form_input(array(
        'name'          => 'title',
        'value'         => set_value('title'),
        'class'         => 'form-control',
        'maxlength'     => '100',
        'size'          => '50',
        'style'         => 'width:80%'
    ));
    echo form_error('title');
    ?>
</div>
<div class="form-group">
    <?php  echo form_label('Description', 'description'); ?><span style="color: red">*</span>

    <?php echo form_textarea(array(
        'name'          => 'description',
        'value'         => set_value('description'),
        'class'         => 'form-control',

        'style'         => 'width:80%'
    ));

    echo form_error('description');
    ?>
</div>
<div class="form-group">
<?php  echo form_label('Upload Image', 'image_url'); ?><span style="color: red"> (Image dimensions should be more that 300*200px.)</span>
<input type="file" name="image_url" size="20" />
</div>

<?php echo form_submit('Add News','Add News',array(
    'name'          => 'submit',
    'class'         => 'btn btn-default',

)); ?>

<?php form_close() ?>

