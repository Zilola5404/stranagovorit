<?php 

if (is_user_logged_in()){
$user_id = get_current_user_id();
$exp = 'no';

?>


<div class="userpage_mypost">

		<div class="post_grig_list">
               <?php

                $args = array(
                    'author' => $user_id,
                    'post_status' => 'publish',
                    'posts_per_page' => '100',
                    'orderby' => 'date',
                    'order'   => 'DESC',
                    'post_type' => 'post',
                );

                $query = new WP_Query( $args );

               	$its_id = 0;

               	if ($query->have_posts()){
                    while ($query->have_posts()){
                        $query->the_post();
                        $its_id++;
                        $post_id = get_the_ID();
                        $coun_num = get_field("count_subs", $post_id);
						$old_coun_num = get_field("old_count_subs", $post_id);
						$coun_num += $old_coun_num;
						$need_co_goal = get_field("need_goal", $post_id);
						$status = get_field("status", $post_id);
						$html = '';

						if($status){
							$html = '<div style="opacity: 0.85;position:absolute;font-size: 14px;padding: 0.6em 1.2em;;line-height: 1;font-weight: 600;margin: 10px;border-radius: 999px;color: white;';
							if($status == 'win'){
								$html = $html.'background-color: #ed1c24;">Победа</div>';
							}
							if($status == 'missing'){
								$html = $html.'background-color: #135c8a;">Отсутствует</div>';
							}
							if($status == 'urgent'){
								$html = $html.'background-color: #ffffff;color:#ff0000;">СРОЧНО</div>';
							}
							if($status == 'success'){
								$html = $html.'background-color: #ffffff;color:#0074bc;">Успех</div>';
							}
							
						}

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
                <div class="box_row_mypost">
					<div class="post_cont_ptt" style="position:relative;">
						<?php echo $html ?>
						<div class="top_img_wrp"><a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( $post_id, 'medium' ); ?></a></div>
						<div class="btm_box_info">
							<p class="name_petition"><?php the_title(); ?></p>
							<span class="box_date_ths"><?php echo get_the_date(); ?></span>

							<?php 

							$now_formatgol = number_format_i18n($coun_num);
							$need_goal_form = number_format_i18n($need_co_goal);
							 
							$mygols = ($now_gol / $need_gol) * 100;
							$goalformat = number_format_i18n($mygols);

							?>
							<div class="progress_bar_pett">
								<div class="progressbar">
								  <span style="width: <?php echo $goalformat; ?>%">
								  	<?php 

								  	if ($win_petition == 'yes') { echo '<p class="winn">ПОБЕДА</p>'; 


								  } else { 
								  		echo '<p class="activ">'.$now_formatgol.''; }?>
								  		
								  	</span>
								</div>
							</div>
						</div>
						<div class="btmsgroup">
							<a href="#" class="redact_thise" data-modval="<?php echo '.popup_redact'.$post_id.'';?>" >Редактировать</a>
							<a href="/pdfexp/?post_id=<?php echo $post_id ?>&pdf=2090">Скачать в PDF</a>
						</div>
					</div>	

					<?php if(true){?>
					<div class="modal-redact ppfderc popup_redact<?php echo $post_id; ?>">
						<span class="icion_clocepp"></span>
						<div class="cont_pphs">
								<div class="head_title_pp">Редактировать - <?php the_title(); ?></div>
								<div class="edit_form_message"></div>
							<div class="post_cont_frm">
								<form id="redact_petition" class="redact_petition" name="redact_petition" action="#" method="post">
									
									<div class="frm_group"> 
								          <label for="zagolovk_red" class="form-label">Заголовок петиции</label>
								          <input value="<?php the_title(); ?>" class="form_inpt" id="zagolovk_red" name="zagolovk_red" >
								    </div>

								    <div class="frm_group"> 
								        <label for="descrip_red" class="form-label">Описание петиции</label>
								        <textarea class="form_textar" id="descrip_red" name="descrip_red"><?php echo strip_tags(get_the_content( $more_link_text = null, $strip_teaser = false )); ?></textarea>
								     </div>

									<div class="frm_group"> 
										<label for="old_need" class="form-label">Сколько подписей уже собранно?<span>Подписи собранные вне данного сайта</span></label>
										<input placeholder="" type="number" value="<?php echo get_field("old_count_subs", $post_id) ?>" class="form_inpt go_nexell" id="old_need" name="old_need">
									</div>

								    <div class="frm_group"> 
								        <label for="for_whos_red" class="form-label">Кому направлена петиция</label>
								        <input value="<?php echo get_field("for_whos", $post_id); ?>" class="form_inpt" id="for_whos_red" name="for_whos_red" >
								    </div>

								    <div class="frm_group"> 
								        <label for="wride_mass" class="form-label">Текст подписи</label>
								        <textarea class="form_textar" id="wride_mass" name="wride_mass"><?php echo strip_tags(get_field("wride_mass", $post_id)); ?></textarea>
										<!-- <div contenteditable="true" class="form_textar" id="wride_mass" name="wride_mass"><?php echo get_field("wride_mass", $post_id); ?></div> -->
								     </div>

								  
								    <input type="hidden" value="<?php echo $post_id; ?>" name="idthpost" id="idthpost">

								    <div class="form_btmgr">
								    	<button class="btm_save_ptt closebtmths">Закрыть</button>
								        <button type="submit" class="btm_save_ptt obnoxs">Обновить</button>
								    </div>
								    <div class="under_btm_dels">
								    	<a class="box_delites_par" data-delpetit="<?php echo $post_id; ?>">Удалить петицию</a>

								    	<a class="box_rend_win" data-delpetit="<?php echo $post_id; ?>">Окончить сбор</a>
								    </div>

								</form>
							</div>
						</div>
					</div>

					<?php } else {?>
					
					<div class="modal-ext ppfderc pptorecexp popup_redact<?php echo $post_id; ?>">
							<span class="icion_clocepp"></span>
							<div class="cont_pphs exportsis">
								<div class="head_title_pp">Экспортировать подписи - <?php the_title(); ?></div>
								<div class="edit_form_message"></div>
								
								<div class="post_cont_frm">
								<form method="post" action="pdf_create.php"> 
 									<a href="#" class="btm_uxport pngexp submit" data-idpost="<?php echo $post_id; ?>">PDF</a>
 								</form> 
									
									<a href="#" class="btm_uxport exelexp" data-idpost="<?php echo $post_id; ?>">EXEL</a>

									<div style="margin-top: 50px;" class="boxtertg">

									</div>

								</div>

							</div>
					</div>
					<?php }?>
						
				</div>
               	<?php } } else { ?>
               		<div class="add_petition_btm">
               			<p>Вы еще не добавляли петиции</p>
               			<a href="/addnew-pt/">Добавить</a>
               		</div>
               	<?php } ?>
    	</div>





		<div class="post_grig_list">

               <?php

                $argss = array(
                    'author' => $user_id,
                    'post_status' => 'draft',
                    'posts_per_page' => '100',
                    'orderby' => 'date',
                    'order'   => 'DESC',
                    'post_type' => 'post',
                );

                $querys = new WP_Query( $argss );

               	$its_id = 0;

               	if ($querys->have_posts()){
               	echo '<div class="box_title_nore">Неопубликованные</div>';
                    while ($querys->have_posts()){
                        $querys->the_post();
                        $post_ids = get_the_ID();
                        $coun_num = get_field("count_subs", $post_ids);
						$need_co_goal = get_field("need_goal", $post_ids);
                ?>
                <div class="box_row_mypost">
					<div class="post_cont_ptt">
						<div class="top_img_wrp"><a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( $post_ids, 'medium' ); ?></a></div>
						<div class="btm_box_info">
							<p class="name_petition"><?php the_title(); ?></p>
							<span class="box_date_ths"><?php echo get_the_date(); ?></span>

							<?php 

							$now_formatgol = number_format_i18n($now_gol);
							$need_goal_form = number_format_i18n($need_gol);
							 
							$mygols = ($now_gol / $need_gol) * 100;
							$goalformat = number_format_i18n($mygols);

							?>
							<div class="progress_bar_pett">
								<div class="progressbar">
								  <span style="width: <?php echo $goalformat; ?>%">
								  	<?php if ($coun_num >= $need_co_goal) { echo '<p class="winn">ПОБЕДА</p>'; } else { 
								  		echo '<p class="activ">'.$now_formatgol.''; }?>
								  		
								  	</span>
								</div>
							</div>
						</div>
						<div class="btmsgroup">
							<a href="#" class="restore_brtm" data-delpetit="<?php echo $post_ids; ?>">Востановить</a>
						</div>
					</div>	

	
				</div>
               	<?php } } ?> 		
    	</div>


</div>
<div class="overlays"></div>
<script>
(function($) {
  	$(document).ready(function() {

  		$('.redact_thise').on('click', function(e){
  			e.preventDefault();
  			var nedpop = $(this).data('modval');
  			$(nedpop).show();
  			$('.overlays').show();
  			$('.page-template-default').toggleClass('overflex');
  		});
  		$('.overlays').on('click', function(){
  			$('.ppfderc').hide();
  			$('.page-template-default').removeClass('overflex');
  			$(this).hide();
  		});
  		$('.closebtmths').on('click', function(e){
  			e.preventDefault();
  			$('.ppfderc').hide();
  			$('.overlays').hide();
  			$('.page-template-default').removeClass('overflex');
  		});
  		
  		$('.icion_clocepp').on('click', function(){
  			$('.ppfderc').hide();
  			$('.overlays').hide();
  			$('.page-template-default').removeClass('overflex');
  		});		

  		$('.box_delites_par').on('click', function(e){
  			e.preventDefault();
  			var mupostid = $(this).data('delpetit');

  			var msg = "Вы действительно хотите удалить петицию?";
  			if (confirm(msg)){
                  jQuery.ajax({
                     type: 'POST',
                     url: '<?php echo admin_url("admin-ajax.php") ?>',
                     data: {
                           action: 'deliteposts', 
                              mupostid: mupostid,
                     },
                    success: function(response){     
                        var time_reload;
                        time_reload = setTimeout(function(){
                                 window.location.href = '/mypetishn/';
                              }, 1000);
                   },
                    error: function(e){
                        console.log(e);          
                    }
             });
            } else {
            return false;
         }

  		});	

  		$('.box_rend_win').on('click', function(e){
  			e.preventDefault();
  			var mupostid = $(this).data('delpetit');

  			var msg = "Перевести петицию в статус победы?";
  			if (confirm(msg)){
                  jQuery.ajax({
                     type: 'POST',
                     url: '<?php echo admin_url("admin-ajax.php") ?>',
                     data: {
                           action: 'gowinpetitds', 
                              mupostid: mupostid,
                     },
                    success: function(response){     
                        var time_reload;
                        time_reload = setTimeout(function(){
                                 window.location.href = '/mypetishn/';
                              }, 1000);
                   },
                    error: function(e){
                        console.log(e);          
                    }
             });
            } else {
            return false;
         }

  		});	

  		$('.restore_brtm').on('click', function(e){
  			e.preventDefault();
  			var mupostids = $(this).data('delpetit');

  			var msg = "Востановить петицию?";
  			if (confirm(msg)){
                  jQuery.ajax({
                     type: 'POST',
                     url: '<?php echo admin_url("admin-ajax.php") ?>',
                     data: {
                           action: 'goactivepost', 
                              mupostids: mupostids,
                     },
                    success: function(response){     
                        var time_reload;
                        time_reload = setTimeout(function(){
                                 window.location.href = '/mypetishn/';
                              }, 1000);
                   },
                    error: function(e){
                        console.log(e);          
                    }
             });
            } else {
            return false;
         }

  		});	



		$('.redact_petition').submit(function(e) {
         	e.preventDefault();

               var infomassage = $(".edit_form_message"); 
               $(infomassage).html('');

               var zagolovk_red = $(this).find("#zagolovk_red").val();
               var descrip_red = $(this).find("#descrip_red").val();
               var for_whos_red = $(this).find("#for_whos_red").val();
               var wride_mass = $(this).find("#wride_mass").val();
			   var old_need = $(this).find("#old_need").val();
            //    var wride_mass = $(this).find("#wride_mass").html();
			   console.log(wride_mass);
               //var nd_goal = $(this).find("#nd_goal").val();
               var idthpost = $(this).find("#idthpost").val();

               jQuery.ajax({
                     type: 'POST',
                     url: '<?php echo admin_url("admin-ajax.php") ?>',
					 dataType: 'html',
                     data: {
                           action: 'updatethispost', 
                              zagolovk_red: zagolovk_red,
                              descrip_red: descrip_red,
                              for_whos_red: for_whos_red,
                              wride_mass: wride_mass,
                              //nd_goal: nd_goal,
                              idthpost: idthpost,
							  old_need: old_need,
                     },
                    success: function(response){     
                        $(infomassage).addClass('text-center').html('Данные успешно обновлены');
                        $('.redact_petition').html('');
                        var time_reload;
                        time_reload = setTimeout(function(){
                            window.location.href = '/mypetishn/';
                        }, 1000);
                   },
                    error: function(e){
                        console.log(e);          
                    }
             	});
      	});


		$('.pngexp').on('click', function(e){
         	e.preventDefault();
         	var odtspst = $(this).data('idpost');
         	
         	jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url("admin-ajax.php") ?>',
                data: {
                    action: 'getsubscribers', 
                    mupostids: odtspst,
                },
                
                success: function(response){ 
                	$('.boxtertg').html(response);
                	window.location.href = `${response}`;   
                },
                error: function(e){
                    console.log(e);          
                }
             });

        });
        $('.exelexp').on('click', function(e){
         	e.preventDefault();


        });



  	});
}(window.jQuery));
</script>


<?php } else { ?>

<div class="row">
<script>
(function($) {
    $(document).ready(function() {  
      window.location.href = '/userlogin/';
   });
}(window.jQuery));
</script>
</div>

<?php } ?>