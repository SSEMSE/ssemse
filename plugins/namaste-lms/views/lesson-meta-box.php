<h4><?php _e('Assign to Courses:', 'namaste')?></h4>
 
<?php if(!sizeof($courses)) echo "<p>".__('No courses have been created yet!', 'namaste')."</p>";?> 
<p><label><?php _e('Select course:', 'namaste')?></label>
<select name="namaste_course">
<?php foreach($courses as $course):?>
	<option value="<?php echo $course->ID?>"<?php if($course->ID == $course_id) echo 'selected'?>><?php echo $course->post_title?></option>
<?php endforeach;?>
</select></p>
		
<h4><?php _e('Lesson Access', 'namaste')?></h4>

<?php if(!sizeof($other_lessons)):
	if(empty($post->post_title)):?>
		<p><?php _e('You will be able to set lesson access after you create the lesson.', 'namaste')?></p>
	<?php else:?>
		<p><?php _e('There are no other lessons in this course. So this lesson will be accessible to anyone who enrolled the course.', 'namaste')?></p>
	<?php endif;
   else: 
echo '<p>'.__('This lesson will be accessible only after the following lessons are completed:','namaste').'</p>'; 
foreach($other_lessons as $lesson):?>
	<p><input type="checkbox" name="namaste_access[]" value="<?php echo $lesson->ID?>" <?php if(in_array($lesson->ID, $lesson_access)) echo "checked"?>> <?php echo $lesson->post_title?></p>
<?php endforeach;
endif;?>

<h4><?php _e('Lesson Completeness', 'namaste')?></h4>

<p><?php _e('The minimum requirement for a lesson to be completed is to be visited by the student. However you can add some extra requirements here:', 'namaste')?></p>

<p><input type="checkbox" name="namaste_completion[]" value="admin_approval" <?php if(in_array('admin_approval', $lesson_completion)) echo 'checked'?>> <?php _e('Lesson completion will be manually verified and approved by the admin for every student.', 'namaste')?></p>

<?php if(!empty($homeworks) and sizeof($homeworks)):?>
<p><b><?php _e('The following assignments/homework must be completed:', 'namaste')?></b></p>
<ul>
	<?php foreach($homeworks as $homework):?>
		<li><input type="checkbox" name="namaste_required_homeworks[]" value="<?php echo $homework->id?>"<?php if(in_array($homework->id, $required_homeworks)) echo 'checked'?>> <?php echo stripslashes($homework->title)?></li>
	<?php endforeach;?>
</ul>
<?php endif;?>

<?php if($use_exams and sizeof($exams)):?>	
	<p><b><?php _e('The following quiz must be completed (will take effect only if the quiz is published):', 'namaste')?></b></p>
	<p><select name="namaste_required_exam" onchange="namasteLoadGrades(this.value);">
	<option value=""><?php _e('- No quiz required -', 'namaste')?></option>
	<?php foreach($exams as $exam):?>
		<option value="<?php echo $exam->ID?>" <?php if($exam->ID == $required_exam) echo 'selected'?>><?php echo $exam->name?></option>
	<?php endforeach;?>
	</select> 
	<span id='namasteGradeRequirement' style="display:<?php echo $required_exam?'inline':'none'?>">
	<?php _e('with any of the following grade(s) achieved:', 'namaste')?>
		<span id="namasteGradeSelection">
			<?php if($required_exam):?>
				<select name="namaste_required_grade[]" size="4" multiple="true">
					<option value=""><?php _e('- Any grade -')?></option>
					<?php foreach($required_grades as $grade):?>
						<option value="<?php echo $grade->ID?>" <?php if(in_array($grade->gtitle, $required_grade) or in_array($grade->ID, $required_grade)) echo 'selected'?>><?php echo $grade->gtitle?></option>
					<?php endforeach;?>
				</select>
			<?php endif;?>
		</span>
	</span>
	<?php if($use_grading_system):?>
		<br><input type="checkbox" name="namaste_watu_transfer_grade" value="1" <?php if(get_post_meta($post->ID, 'namaste_watu_transfer_grade', true) == 1) echo 'checked'?>> <?php _e('The grade from the quiz automatically becomes grade for the lesson (only if the grade title exactly matches one of your grades in Namaste)', 'namaste');?>
	<?php endif;?></p>	
	
	<script type="text/javascript" >
	function namasteLoadGrades(examID) {
		var exams = { <?php foreach($exams as $exam): echo $exam->ID.' : { ';
				foreach($exam->grades as $grade): echo $grade->ID.' : "'.str_replace('"','', $grade->gtitle).'", '; endforeach; 
			echo '}, '; endforeach;?>	
		}; // end exams object

		// construct grades dropdown
		if(!examID) {
			jQuery('#namasteGradeRequirement').hide();
			return false;
		}	
		
		html = '<select name="namaste_required_grade[]" size="4" multiple="true"> <option value=""><?php _e('- Any grade -')?></option>';
		if(!exams[examID]) return false;		
		exam = exams[examID];
		
		jQuery.each(exam, function(index, value){
			html += '<option value="'+index+'">' + value + '</option>';		
		});		
		
		jQuery('#namasteGradeSelection').html(html);
		jQuery('#namasteGradeRequirement').show();
	}
	</script>
<?php else: printf('<p style="font-weight:bold;">'.__('If you install %s or %s you can also require certain tests and quizzes to be completed.', 'namaste'), 
	"<a href='http://wordpress.org/extend/plugins/watu/' target='_blank'>Watu</a>", "<a href='http://calendarscripts.info/watupro/' target='_blank'>WatuPRO</a>").'</p>';
endif;?>

<?php if(!empty($use_points_system)):?>
	<p><?php _e('Reward', 'namaste')?> <input type="text" size="4" name="namaste_award_points" value="<?php echo $award_points?>"> <?php _e('points for completing this lesson.', 'namaste')?></p>
<?php endif;?>


<h3><?php _e('Shortcodes', 'namaste')?></h3>

<p><?php _e('You can use the shortcode', 'namaste')?> <input type="text" value="[namaste-todo]" readonly="readonly" onclick="this.select();"> <?php _e('inside the lesson content to display what the student needs to do to complete the lesson.', 'namaste')?></p>

<p><?php _e('The shortcode', 'namaste')?> <input type="text" value="[namaste-mark]" readonly="readonly" onclick="this.select();"> <?php _e('will display a "Mark Completed" button so the student can mark the lesson completed themselves. <b>If such button is included in the lesson it will not be marked as completed until the student does it!</b> The button will appear <b>only after the "Lesson Completeness" requirements are satisfied.</b>', 'namaste')?></p>

<p><?php _e('The shortcode', 'namaste')?> <input type="text" value="[namaste-course-link]" readonly="readonly" onclick="this.select();"> <?php _e('will display a link to the course that this lesson belongs to. You can pass attribute "text" to set a clickable text for the link. Otherwise the course title will be used.', 'namaste')?></p>

<h4><?php _e('Did you know?', 'namaste')?></h4>
<?php if(is_plugin_active('namaste-pro/namaste-pro.php')):?>
	<p><?php printf(__('You can set <a href="%s" target="_blank">delayed access</a> to this lesson.', 'namaste'), 'admin.php?page=namastepro_delayed')?></p>
<?php else:?>
	<p><?php printf(__('If you <a href="%s" target="_blank">upgrade to PRO</a> you will be able to set delayed access to this lesson, namage classes and teachers, and a lot more.', 'namaste'),'http://namaste-lms.org/pro.php')?></p>
<?php endif;?>

<h4><?php _e('Use the Excerpt Box Below:', 'namaste');?></h4>

<p><?php _e('If you enter some content in the Excerpt box on this page, the content will be shown to users who cannot access the lesson  for some reason: non-logged in students, students who did not enroll the course, students with unsatisfied lesson access requirements etc. The content will be shown instead of or before the default text that is shown in these cases. You can also show these excerpts by passing the <b>show_excerpts=1</b> attribute to the <b>namaste-course-lessons</b> shortcode.', 'namaste');?></p>