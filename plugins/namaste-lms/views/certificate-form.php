<h1><?php _e("Add/Edit Certificate", 'namaste')?></h1>

<div class="wrap">
	<form class="namaste-form" onsubmit="return namasteValidateForm(this);" method="post">
		<p><label><?php _e('Certificate Title:', 'namaste')?></label> <input type="text" name="title" value="<?php if(!empty($certificate->title)) echo $certificate->title?>" size="100"></p>
		
		<p><label><?php _e('Certificate Contents:', 'namaste')?></label> <?php echo wp_editor(stripslashes(@$certificate->content), 'content')?></p>
		
		<p><?php _e('You can use the following variables in the certificate contents:', 'namaste')?></p>
		
		<p><strong>{{name}}</strong> <?php _e('- The user full name or login name (whatever is available)', 'namaste')?><br>
		<strong>{{courses}}</strong> <?php _e('- The names of the courses which were completed to acquire this certificate', 'namaste')?><br>
		<strong>{{courses-extended}}</strong> <?php _e('- The names and descriptions of the courses which were completed to acquire this certificate. The post "excerpt" will be used as course description.', 'namaste')?><br>
		<strong>{{date}}</strong> <?php _e('- Date when the certificate was acquired', 'namaste')?><br></p>
		<strong>{{id}}</strong> <?php _e('- Unique ID of this certificate', 'namaste')?><br></p>
		
		<p><strong><?php _e('Assign this certificate upon completing all of the following courses:', 'namaste')?></strong>
		
		<?php if(!sizeof($courses)): _e('You have not created any courses yet!', 'namaste');
		else:?>
			<ul>
				<?php foreach($courses as $course):?>
					<li><input type="checkbox" name="course_ids[]" value="<?php echo $course->ID?>" <?php if(!empty($certificate->id) and strstr($certificate->course_ids, '|'.$course->ID.'|')) echo "checked"?>> <?php echo $course->post_title?></li>
				<?php endforeach;?>
			</ul>
		<?php endif;?>
		
		<?php do_action('namaste-certificate-pdf-settings', @$certificate->id);?>
		
		<div align="center">
			<input type="submit" name="ok" value="<?php _e('Save Certificate', 'namaste')?>">
			<?php if(!empty($certificate->id)):?>
				<input type="button" value="<?php _e('Delete', 'namaste')?>" onclick="namasteConfirmDelete(this.form);">
				<input type="hidden" name='del' value='0'>
			<?php endif;?>		
		</div>		
		<?php wp_nonce_field('namaste_certificate');?>
	</form>
</div>

<script type="text/javascript">
function namasteConfirmDelete(frm) {
	if(confirm("<?php _e('Are you sure?')?>")) {
		frm.del.value=1;
		frm.submit();
	}
}

function namasteValidateForm(frm) {
	if(frm.title.value=='') {
		alert("<?php _e('Please enter certificate title', 'namaste')?>");
		frm.title.focus();
		return false;
	}
	
	return true;
}
</script>