<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- <div class="um-item">
	<div class="um-item-link">
		<i class="um-icon-ios-paper"></i>
		<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo get_the_title( $post ); ?></a>
	</div>

	<?php if ( has_post_thumbnail( $post->ID ) ) {
		$image_id = get_post_thumbnail_id( $post->ID );
		$image_url = wp_get_attachment_image_src( $image_id, 'full', true ); ?>

		<div class="um-item-img">
			<a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
				<?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
			</a>
		</div>

	<?php } ?>

	<div class="um-item-meta">
		<span>
			<?php printf( __( '%s ago', 'ultimate-member' ), human_time_diff( get_the_time( 'U', $post->ID ), current_time( 'timestamp' ) ) ); ?>
		</span>
		<span>
			<?php _e( 'in', 'ultimate-member' ); ?>: <?php the_category( ', ', '', $post->ID ); ?>
		</span>
		<span>
			<?php $num_comments = get_comments_number( $post->ID );

			if ( $num_comments == 0 ) {
				$comments = __( 'no comments', 'ultimate-member' );
			} elseif ( $num_comments > 1 ) {
				$comments = sprintf( __( '%s comments', 'ultimate-member' ), $num_comments );
			} else {
				$comments = __( '1 comment', 'ultimate-member' );
			} ?>

			<a href="<?php echo esc_url( get_comments_link( $post->ID ) ); ?>"><?php echo $comments; ?></a>
		</span>
	</div>
</div> -->


<div class="um-item">
	

	<?php 
		$status = get_field("status", $post->ID);
		$coun_num = get_field("count_subs", $post->ID);
		$need_co_goal = get_field("need_goal", $post->ID);
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

		$image_id = get_post_thumbnail_id( $post->ID );
		$image_url = wp_get_attachment_image_src( $image_id, 'full', true ); ?>

		<div class="um-item-img" style="position:relative;">
			<?php echo $html ?>
			
			<a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
				<?php if ( has_post_thumbnail( $post->ID ) ) {?>
					<?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
				<?php } else {?>
					<img width="460" height="154" src="https://via.placeholder.com/460x255/?text=No_Image" class="attachment-medium size-medium wp-post-image" alt="" loading="lazy">
				<?php } ?>
			</a>
		</div>

	<div class="um-item-link">
		<div class="btm_box_info">

							<p class="name_petition"><?php echo get_the_title( $post ); ?></p>
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
								  			echo '<p class="activ">'.$now_formatgol.''; 
										}?>	
								  	</span>
								</div>
							</div>
						</div>
	</div>

	<div class="um-item-meta">
		<span>
			<?php printf( __( '%s ago', 'ultimate-member' ), human_time_diff( get_the_time( 'U', $post->ID ), current_time( 'timestamp' ) ) ); ?>
		</span>
		<span>
			<?php _e( 'in', 'ultimate-member' ); ?>: <?php the_category( ', ', '', $post->ID ); ?>
		</span>
		<span>
			<?php $num_comments = get_comments_number( $post->ID );

			if ( $num_comments == 0 ) {
				$comments = __( 'no comments', 'ultimate-member' );
			} elseif ( $num_comments > 1 ) {
				$comments = sprintf( __( '%s comments', 'ultimate-member' ), $num_comments );
			} else {
				$comments = __( '1 comment', 'ultimate-member' );
			} ?>

			<a href="<?php echo esc_url( get_comments_link( $post->ID ) ); ?>"><?php echo $comments; ?></a>
		</span>
	</div>
</div>

