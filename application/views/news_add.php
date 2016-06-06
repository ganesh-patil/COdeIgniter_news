<script type='text/javascript' src="<?php echo base_url(); ?>js/ckeditor.js"></script>

<h2> Add News </h2>
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
    echo form_error('title','<div class="error">', '</div>');
    ?>
</div>
<div class="form-group">
    <?php  echo form_label('Description', 'description'); ?><span style="color: red">*</span>

    <?php echo form_textarea(array(
        'name'          => 'description',
        'value'         => set_value('description'),
        'class'         => 'form-control',
         'id'           =>'description',
    ));

    echo form_error('description','<div class="error">', '</div>');
    ?>
</div>
<div class="form-group">
<?php  echo form_label('Upload Image', 'image_url'); ?><span style="color: red"> (Image dimensions should be between  300X200. and  1400X800)</span>
<input type="file" name="image_url" size="20" />
</div>

<?php echo form_submit('Add News','Add News',array(
    'name'          => 'submit',
    'class'         => 'btn btn-default',

)); ?>

<?php form_close() ?>
<script type='text/javascript' src="<?php echo base_url(); ?>js/sample.js"></script>

