<?php
	$post_id = get_the_ID();
	$coun_num = get_field("count_subs", $post_id);
	$old_coun_num = get_field("old_count_subs", $post_id);
	$need_co_goal = get_field("need_goal", $post_id);
	$status = get_field("status", $post_id);

	$terms_list = get_the_terms( $post_id, 'category' );
	foreach( $terms_list as $cur_term ){
		$res_term = $cur_term->term_id;
		if ($res_term == 39){
			$win_petition = 'yes';
		} else {
			$win_petition = 'no';
		}
	}

?>

<div class="sub_btm_block">
	<?php if ($status == 'win') { ?>
		<p class="box_title_ptt title_subbb">Победа!</p>
	<?php } else { ?>
		<p class="box_title_ptt title_subbb">Подписать петицию</p>
	<?php } ?>	

	<div class="form_box_subs">
		<form id="go_sub_podp" class="go_sub_podp" name="go_sub_podp" action="#" method="post">

			<input type="hidden" name="id_thispsot" id="id_thispsot" value="<?php echo the_ID(); ?>">
			<input type="hidden" name="count_subse" id="count_subse" value="<?php echo $coun_num; ?>">
			<input type="hidden" name="need_subse" id="need_subse" value="<?php echo $need_co_goal; ?>">
			<input type="hidden" name="old_count_subse" id="old_count_subse" value="<?php echo $old_coun_num; ?>">

			<?php 
 				if (isset($_COOKIE["subemail"])){
 					$dfgrfre = 'tre';
 				}

				if (is_user_logged_in() || $dfgrfre == 'tre'){

					$user_id = get_current_user_id(); 
					$user_info = get_userdata($user_id);

					if (is_user_logged_in()){
						$user_subse = $user_info->user_email;
						$user_name = get_user_meta( $user_id, 'first_name', true );
						$user_famil = get_user_meta( $user_id, 'last_name', true );
						$user_reg = get_user_meta( $user_id, 'region', true );
					} else {
						$user_subse = $_COOKIE["subemail"];
						$user_name = $_COOKIE["subname"];
						$user_famil = $_COOKIE["subfamil"];
						$user_reg = $_COOKIE["subregion"];
					}

					if(is_user_logged_in()){
						?><input type="hidden" name="login_user" id="login_user" value="<?php echo $user_info->user_email ?>"><?php
					}else{
						?><input type="hidden" name="login_user" id="login_user" value="no"><?php
					}
				?>
				
				

				<input type="hidden" name="user_need_reg" id="user_need_reg" value="no">

				<div class="frm_group "> 
					<label for="email_subs" class="form-label">Ваш Email</label>
					<input type="email" class="form_inpt" value="<?php echo $user_subse; ?>" id="email_subs" name="email_subs" required>
				</div>
				<div class="frm_group "> 
					<label for="name_user" class="form-label">Ваше имя</label>
					<input type="text" class="form_inpt" name="name_user" id="name_user" value="<?php echo $user_name; ?>" required>
				</div>
				<div class="frm_group "> 
					<label for="famil_user" class="form-label">Ваша фамилия</label>
					<input type="text" class="form_inpt" name="famil_user" id="famil_user" value="<?php echo $user_famil; ?>" required>
				</div>

				<div class="frm_group "> 
					<label for="user_region" class="form-label">Регион</label>
					<input class="form_inpt" list="regionaddRegion" value="<?php echo $user_reg; ?>" placeholder="Начните вводить..." id="user_region" name="user_region" >
		            <datalist id="regionaddRegion">
		                <?php echo do_shortcode('[region_list]'); ?>
		            </datalist>
				</div>
				<div class="frm_group"> 
						<label for="user_city" class="form-label">Город</label>
						<input class="form_inpt" list="cityaddCity" value="" placeholder="Начните вводить..." id="user_city" name="user_sity" >
		                <datalist id="cityaddCity">
		                    <?php echo do_shortcode('[city_list]'); ?>
		                </datalist>
					</div>

				<?php
				} else {
				?>
					<input type="hidden" name="user_need_reg" id="user_need_reg" value="yes">

					<input type="hidden" name="login_user" id="login_user" value="no">
				
					<div class="frm_group"> 
						<label for="email_subs" class="form-label">Ваш Email</label>
						<input type="email" class="form_inpt" value="" id="email_subs" name="email_subs" required>
					</div>

					<div class="frm_group"> 
						<label for="name_user" class="form-label">Ваше имя</label>
						<input type="text" class="form_inpt" value="" id="name_user" name="name_user" required>
					</div>
					<div class="frm_group"> 
						<label for="famil_user" class="form-label">Ваша фамилия</label>
						<input type="text" class="form_inpt" value="" id="famil_user" name="famil_user" required>
					</div>

					<div class="frm_group"> 
						<label for="user_region" class="form-label">Регион</label>
						<input class="form_inpt" list="regionaddRegion" value="" placeholder="Начните вводить..." id="user_region" name="user_region" >
		                <datalist id="regionaddRegion">
		                    <?php echo do_shortcode('[region_list]'); ?>
		                </datalist>
					</div>

					<div class="frm_group"> 
						<label for="user_city" class="form-label">Город</label>
						<input class="form_inpt" list="cityaddCity" value="" placeholder="Начните вводить..." id="user_city" name="user_sity" >
		                <datalist id="cityaddCity">
		                    <?php echo do_shortcode('[city_list]'); ?>
		                </datalist>
					</div>
			<?php
				}
			?>



			<button type="submit" class="btm_save_pop" style="font-size: 18px;">Подписать</button>

		</form>
		<div class="respon_errors"></div>
	</div>

	<p class="box_title_ptt title_thankkse">Спасибо!</p>

</div>


<?php 
$loadavto = get_field("vklyuchit_avtoczel", $post_id);
if ($loadavto == 'yes'){
?>

<script>
(function($) {
 $(window).load(function(){
	    var count = $('#count_subse').val();
		var old_count = $('#old_count_subse').val();
		count = count + old_count;
	    var need = $('#need_subse').val();
	    var newneed = need - 5;

	    if (count > newneed){

	    	var id_thispsot = $('#id_thispsot').val();

	    	jQuery.ajax({
	            type: 'POST',
	            url: '<?php echo admin_url("admin-ajax.php") ?>',
	            data: {
	                action: 'autocell', 
	                    id_thispsot: id_thispsot,
	                    need: need,
	            },
	            success: function(response){  
	            	console.log(response);  
	            },
	            error: function(e){
	                console.log(e);          
	            }
	        });

	    }

});
}(window.jQuery));
</script>
<?php } ?>

<script>
(function($) {

  	$(document).ready(function() {

  		$("#user_region").focusout(function(){
         	var val_region = $(this).val();
         	$(this).attr('value', val_region);
      	});

		  $("#user_city").focusout(function(){
         	var val_city = $(this).val();
         	$(this).attr('value', val_city);
      	});

  		$('.btm_go_load').on('click', function(e){
  			e.preventDefault();
  			$('.frm_group').removeClass('hiddednow');
  			$('.box_user_block').hide();

  		});

  		$('#go_sub_podp').submit(function(e) {
	      	e.preventDefault();

	       	var email_subs = $(this).find("#email_subs").val();
	       	var name_user = $(this).find("#name_user").val();
	       	var famil_user = $(this).find("#famil_user").val();
	       	var user_region = $(this).find("#user_region").val();
			var user_city = $(this).find("#user_city").val();
	       	var id_thispsot = $(this).find("#id_thispsot").val();
	       	var needreg = $(this).find("#user_need_reg").val();
			var logged_user = $(this).find("#login_user").val();

	        if (email_subs == ''){
	            $('.respon_errors').html('Все поля обязательны для заполнения');
			    return false;
			} 
			if (name_user == ''){
	            $('.respon_errors').html('Все поля обязательны для заполнения');
			    return false;
			} 
			if (famil_user == ''){
	            $('.respon_errors').html('Все поля обязательны для заполнения');
			    return false;
			} 
			if (user_region == ''){
	            $('.respon_errors').html('Все поля обязательны для заполнения');
			    return false;
			}


	        jQuery.ajax({
	            type: 'POST',
	            url: '<?php echo admin_url("admin-ajax.php") ?>',
	            data: {
	                action: 'addsubskr', 
	                    email_subs: email_subs,
	                    name_user: name_user,
	                    famil_user: famil_user,
	                    user_region: user_region,
						user_city: user_city,
	                    id_thispsot: id_thispsot,
	                    needreg: needreg,
						logged_user: logged_user,
	            },
	            success: function(response){  
					console.log(response);
	            	var result = JSON.parse(response);
	            	if (result['status'] === 'error') {
	            		window.location.href=`/checkmail`;
		            } else {

		              	$('.form_box_subs').hide();
	                	$('.boxfor_sharethis').show();
	                	$('.title_subbb').hide();
	                	$('.title_thankkse').show();
	                	$('.respon_errors').html('');

	                	var image_petition = $('.box_img_petit img').attr('src');
	                	var title_petition = $('.box_title_pertits h1').text();

	                	window.location.href=`/sharedpage/?url=${window.location.href}#${image_petition}`;

		            }
	                
	            },
	            error: function(e){
	                console.log(e);          
	            }
	        });
      	});

  	});
}(window.jQuery));
</script>

