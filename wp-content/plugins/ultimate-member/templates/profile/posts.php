<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	//Only for AJAX loading posts
	if ( ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			UM()->get_template( 'profile/posts-single.php', '', array( 'post' => $post ), true );
		}
	}
} else {
	if ( ! empty( $posts ) ) { ?>
		<div class="um-ajax-items">

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
						$need_co_goal = get_field("need_goal", $post_id);
						$status = get_field("status", $post_id);
						$html = '';

						if($status){
							$html = '<div style="position:absolute;font-size: 12px;padding: 0.6em 1.2em;;line-height: 1;font-weight: 600;margin: 20px;border-radius: 999px;color: white;';
							if($status == 'win'){
								$html = $html.'background-color: #ed1c24;">Победа</div>';
							}
							if($status == 'missing'){
								$html = $html.'background-color: #135c8a;">Отсутствует</div>';
							}
							if($status == 'urgent'){
								$html = $html.'background-color: #ff8800;">Срочно</div>';
							}
							if($status == 'success'){
								$html = $html.'background-color: #01d321;">Успех</div>';
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
							<a href="<?php the_permalink(); ?>" >Перейти</a>
							<!-- <a href="/pdfexp/?post_id=<?php echo $post_id ?>&pdf=2090">Скачать в PDF</a> -->
						</div>
					</div>
				</div>
               	<?php } } ?>
    		</div>
		</div>

	<?php } else { ?>

		<div class="um-profile-note">
			<span>
				<?php if ( um_profile_id() == get_current_user_id() ) {
					_e( 'You have not created any posts.', 'ultimate-member' );
				} else {
					_e( 'This user has not created any posts.', 'ultimate-member' );
				} ?>
			</span>
		</div>

	<?php }
}