<?php


/* 
** Sets up theme defaults and registers support for various WordPress features
*/
function royal_elementor_kit_setup() {

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title for us
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages
	add_theme_support( 'post-thumbnails' );

	// Custom Logo
	add_theme_support( 'custom-logo', [
		'height'      => 100,
		'width'       => 350,
		'flex-height' => true,
		'flex-width'  => true,
	] );

	add_theme_support( 'custom-header' );

	// Add theme support for Custom Background.
	add_theme_support( 'custom-background', ['default-color' => ''] );

	// Set the default content width.
	$GLOBALS['content_width'] = 960;

	// This theme uses wp_nav_menu() in one location
	register_nav_menus( array(
		'main' => __( 'Main Menu', 'royal-elementor-kit' ),
	) );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Gutenberg Embeds
	add_theme_support( 'responsive-embeds' );

	// Gutenberg Widge Images
	add_theme_support( 'align-wide' );


	// WooCommerce in general.
	add_theme_support( 'woocommerce' );

	// zoom.
	add_theme_support( 'wc-product-gallery-zoom' );
	// lightbox.
	add_theme_support( 'wc-product-gallery-lightbox' );
	// swipe.
	add_theme_support( 'wc-product-gallery-slider' );
}

add_action( 'after_setup_theme', 'royal_elementor_kit_setup' );

/*
** Enqueue scripts and styles
*/
function royal_elementor_kit_scripts() {

	// Theme Stylesheet
	wp_enqueue_style( 'royal-elementor-kit-style', get_stylesheet_uri(), array(), '1.0' );

	// Comment reply link
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'royal_elementor_kit_scripts' );


/*
** Notices
*/
require_once get_parent_theme_file_path( '/inc/activation/class-welcome-notice.php' );
require_once get_parent_theme_file_path( '/inc/activation/class-rating-notice.php' );

add_action( 'after_switch_theme', 'rek_activation_time');
add_action('after_setup_theme', 'rek_activation_time');
    
function rek_activation_time() {
	if ( false === get_option( 'rek_activation_time' ) ) {
		add_option( 'rek_activation_time', strtotime('now') );
	}
}



add_shortcode('userpage_add','userpage_add_load');
function userpage_add_load(){
    get_template_part( 'add_petition');
}

add_shortcode('userpage_petitions','userpage_petitions_load');
function userpage_petitions_load(){
    get_template_part( 'uspg_petition');
}

add_shortcode('redact_petitions','redact_petitions_load');
function redact_petitions_load(){
    get_template_part( 'redact_petition');

}

add_shortcode('redacte_petitions','redacte_petitions_load');
function redacte_petitions_load(){
    global $wpdb;
	$id_thispsot = $_GET['post_id'];
	$post_id = $id_thispsot;
	$post_img_id = get_post_thumbnail_id($post_id);
	
	
// echo $id_thispsot;

$myrows = $wpdb->get_results( "SELECT pitition_id, name, surname, region, create_at FROM wp_signature WHERE pitition_id = $id_thispsot");
$post = get_post( $id_thispsot );
?>

<div class="post_title">
    <h3><?php echo $post->post_title ?></h3>
    <br>
</div>

<div class="post_img" style="align-items: center;">
    <?php echo get_the_post_thumbnail( $post_id, $size='full', 'style=width: 100%'); ?>
	<?php echo do_shortcode('[sebscount]'); ?>
    <br><br><br>
</div>

<!-- <div class="post_info">
    <?php echo get_the_content( $more_link_text = null, $strip_teaser = false, $post ); ?>
    <br><br>
</div> -->

<div class="post_title">
    <h3><?php echo get_field("for_whos", $post_id); ?></h3>
    <br>
</div>

<div class="post_info">
    <?php echo get_field("wride_mass", $post_id); ?>
    <br><br>
</div>

<style>
    .post_info {
        text-align: left;
    }
</style>

<?php
}
//новая петиция
add_action( 'wp_ajax_addnewpetit', 'addnewpetit_ajax_load' );

add_action( 'wp_ajax_addimageajax', 'addimageajax_ajax_load' );


add_action( 'wp_ajax_nopriv_addnewpetit', 'addnewpetit_ajax_load' );
 
function addnewpetit_ajax_load(){

	$user_id = get_current_user_id();

	$zagolovk = sanitize_text_field($_POST['zagolovk_dt']);
	$descrip = sanitize_textarea_field($_POST['descrip_dt']);
	$for_whos = sanitize_text_field($_POST['for_whos_dt']);
	$wride_mass = sanitize_textarea_field($_POST['wride_mass_dt']);
	$old_need = $_POST['old_need_dt'];

	if($old_need < 100){
		$need_goal = 100;
	}else{
		$n1 = 100;
		$n2 = 200;
		$n3 = 500;
		$stop = true;

		while($stop){

			if($old_need < $n1){
				$need_goal = $n1;
				$stop = false;
				break;
			}
			elseif($old_need < $n2){
				$need_goal = $n2;
				$stop = false;
				break;
			}
			elseif($old_need < $n3){
				$need_goal = $n3;
				$stop = false;
				break;
			}
			else{
				$n1 *= 10;
				$n2 *= 10;
				$n3 *= 10;
				if($n2 == 20000) $n2 = 25000;
			}
		}
	}

	$add_data_now = date('d.m.Y');


	$post_data = array(
        'post_title'    => $zagolovk,
        'post_content'  => $descrip,
        'post_status'   => 'publish',
        'post_author'   => $user_id,
        'post_type'      => 'post',
        'meta_input'     => [ 
		'for_whos' => $for_whos,
        	'count_subs' => '0',
		'old_count_subs' => $old_need,
        	'need_goal' => $need_goal,
        	'wride_mass' => $wride_mass,
    	],

    );

    $post_id = wp_insert_post( $post_data );
    wp_set_object_terms( $post_id, 'petition', 'category' );


   	$post_datanew = array(
        'ID' => $post_id,
        'meta_input'     => ['postid' => $post_id],

    );
    wp_update_post( $post_datanew );

    
            $files = $_FILES['source_dt'];

			$upload = wp_upload_bits( $_FILES["source_dt"]["name"], null, file_get_contents( $_FILES["source_dt"]["tmp_name"]) );

			$filename = $upload['url'];
			// ID поста, к которому прикрепим вложение.
			$parent_post_id = $post_id;

			// Проверим тип поста, который мы будем использовать в поле 'post_mime_type'.
			$filetype = wp_check_filetype( basename( $filename ), null );

			// Получим путь до директории загрузок.
			$wp_upload_dir = wp_upload_dir();

			// Подготовим массив с необходимыми данными для вложения.
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			// Вставляем запись в базу данных.
			$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

			// Подключим нужный файл, если он еще не подключен
			// wp_generate_attachment_metadata() зависит от этого файла.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			// Создадим метаданные для вложения и обновим запись в базе данных.
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			set_post_thumbnail($post_id, $attach_id); 

              

die();
}

//подписать
add_shortcode('subform','subform_load');
function subform_load(){
	get_template_part( 'subscribe_form');
}

//новая подпись
add_action( 'wp_ajax_addsubskr', 'addsubskr_ajax_load' );
add_action( 'wp_ajax_nopriv_addsubskr', 'addsubskr_ajax_load' );
 
function addsubskr_ajax_load(){

	$status = 'ok';

	$user_id = get_current_user_id();
	$id_thispsot = sanitize_text_field($_POST['id_thispsot']);

	$add_data_now = date('d.m.Y');

	// $count_subs = get_field("count_subs", $id_thispsot);
	// $new_count = $count_subs + 1;

	$email_subs = sanitize_text_field($_POST['email_subs']);

	$name_user = sanitize_text_field($_POST['name_user']);
	$famil_user = sanitize_text_field($_POST['famil_user']);
	$region_user = sanitize_text_field($_POST['user_region']);
	$city_user = sanitize_text_field($_POST['user_city']);
	$needreg = sanitize_text_field($_POST['needreg']);
	$logged_user = $_POST['logged_user'];
	$chkemail = true;
	

	global $wpdb;
	$emails = $wpdb->get_results( "SELECT id, email, status FROM wp_users_nosign WHERE 1");

	foreach($emails as $email){
		if($email->email == $email_subs){
			$id_user_t = $email->id;
			$chkemail = false;
		}
	}
	
	if($chkemail){
		if($logged_user == "true"){
			$wpdb->insert( 'wp_users_nosign', array(
				'email' => $email_subs,
				'status' => true,
			)); 
		}
		else {
			$wpdb->insert( 'wp_users_nosign', array(
				'email' => $email_subs,
				'status' => false,
			));
		}
		$emails = $wpdb->get_results( "SELECT id, email, status FROM wp_users_nosign WHERE 1");
	}

	foreach($emails as $email){
		if($email->email == $email_subs){
			$id_user_t = $email->id;
		}
	}

	$get = $wpdb->get_results( "SELECT pitition_id, email, name, surname, region FROM wp_signature WHERE pitition_id = $id_thispsot");
	$new_count = count($get);

	$emails = $wpdb->get_results( "SELECT id, email, status FROM wp_users_nosign WHERE id = $id_user_t");
	if($emails){
		foreach($emails as $email){
		if($email->status){
			$get = $wpdb->get_results( "SELECT pitition_id, email, name, surname, region FROM wp_signature WHERE pitition_id = $id_thispsot");
			$new_count = count($get);

			if($get){
				$chk = true;
				foreach($get as $geti){
					if($geti->email==$email_subs) $chk = false;
				}
			}
			else $chk = true;
			

			if($chk){
				
				$subscr = $wpdb->insert( 'wp_signature', array(
					'pitition_id' => $id_thispsot,
					'email' => $email_subs, 
					'name' => $name_user,
					'surname' => $famil_user, 
					'region' => $region_user,
					'city' => $city_user
				)); 

				$get = $wpdb->get_results( "SELECT id, email FROM wp_signature WHERE pitition_id = $id_thispsot");

				if($get){
					$new_count = count($get);
					foreach($get as $geti){
						if($geti->email == $email_subs) {
							$id = $geti->id;

							$post = get_post( $id_thispsot );
							$subject = "Спасибо за Вашу подпись";
							$message = "Вы только что подписали на нашем сайте петицию «" . $post->post_title . "».<br> Спасибо Вам за Вашу подпись!<br>Если Вы не подписывали петицию или подписали ее по ошибке и хотите удалить свою подпись, пройдите по ссылке: <br><a href='https://stranagovorit.ru/udalenie-podpisi/?id=".$id."'>http://test.prograkf.beget.tech/udalenie-podpisi</a><br>С уважением". get_option('blogname');
							// wp_mail( $to, $subject, $message, $headers, $attachments )
							$headers[] = 'Content-type: text/html; charset=utf-8'; // в виде массива
							wp_mail($email_subs, $subject, $message, $headers);

							$chk_whos = get_field("send_for_whos", $id_thispsot);

							if($chk_whos == 'yes'){
								$post = get_post( $id_thispsot );
								$email = get_field("for_whos_email", $id_thispsot);
								$subject = get_field("for_whos", $id_thispsot);
								$message = get_field("wride_mass", $id_thispsot);
								// wp_mail( $to, $subject, $message, $headers, $attachments )
								$headers[] = array('Content-type: text/html; charset=utf-8;', 'From: '.$name_user.' <'.$email_subs.'>;'); // в виде массива
								wp_mail($email, $subject, $message, $headers);
							}
						}
						
					}
				}
			}
		}
		else{
			$post = get_post( $id_thispsot );
			$subject = "Пройдите проверку на сайте";
			$message = "Пройдите по ссылке, чтобы активировать свою почту<br><a href='https://stranagovorit.ru/checkmail?num=$id_user_t&email=$email_subs'>Подтвердить</a><br>С уважением ". get_option('blogname');
			// wp_mail( $to, $subject, $message, $headers, $attachments )
			$headers[] = 'Content-type: text/html; charset=utf-8'; // в виде массива
			wp_mail($email_subs, $subject, $message, $headers);

			$status = 'error';
			$result = ['status' => 'error'];
				$json = json_encode($result);
        	echo $json;
			
		}
		
			
	}
	}

	
	$post_email_user = array();
	
	$post_subb_user = array();

	$name_user = sanitize_text_field($_POST['name_user']);
	$famil_user = sanitize_text_field($_POST['famil_user']);
	$region_user = sanitize_text_field($_POST['user_region']);
	$needreg = sanitize_text_field($_POST['needreg']);


	if ($needreg == 'yes'){
       	setcookie("subemail", $email_subs, time() + 7 * 86400);
        setcookie("subname", $name_user, time() + 7 * 86400);
        setcookie("subfamil", $famil_user, time() + 7 * 86400);
        setcookie("subregion", $region_user, time() + 7 * 86400);
    }
	


	$post_data = array(
        'ID' => $id_thispsot,
        'meta_input'     => [ 
                                'count_subs' => $new_count,
                                'postid' => $id_thispsot,
								
                                ],

        );
        $post_ads = wp_update_post( $post_data );

        $user_id = get_current_user_id();
        update_user_meta( $user_id, 'first_name', $name_user);
        update_user_meta( $user_id, 'last_name', $famil_user);
        update_user_meta( $user_id, 'region', $region_user);

	// }
	if (!is_wp_error( $post_ads ) && $status != "error"){
		$result = ['status' => 'ok'];
		$json = json_encode($result);
		echo $json;
	}

    

die();
}

//проверяем на автоцель
add_action( 'wp_ajax_autocell', 'autocell_ajax_load' );
add_action( 'wp_ajax_nopriv_autocell', 'autocell_ajax_load' );
 
function autocell_ajax_load(){
	
	$id_thispsot = sanitize_text_field($_POST['id_thispsot']);
	$old_need = get_field("old_count_subs", $id_thispsot);
	$need = sanitize_text_field($_POST['need']);
	$myrows = $wpdb->get_results( "SELECT pitition_id, name FROM wp_signature WHERE pitition_id = $id_thispsot");
	$all_need = count($myrows) + $old_need;
	$needint = (int)$need;
	$stop = true;

	$n1 = 100;
	$n2 = 200;
	$n3 = 500;


	if($all_need == $needint){
		while($stop){
			if($needint = $n1){
				$newgoal = $n2;
				$stop = false;
			}
			else if($needint = $n2){
				$newgoal = $n3;
				$stop = false;
			}
			else if($needint = $n3){
				$n1 = $n1 * 10;
				$newgoal = $n1;
				$stop = false;
			}
			else{
				$n1 = $n1 * 10;
				$n2 = $n2 * 10;
				$n3 = $n3 * 10;
				if($n2 == 20000) $n2 = 25000;
			}
		}
	}
	else $newgoal = $needint;
	


	// if ($needint <= 50000){
	// 	$newgoal = $need + 50000;

	// } else if ($needint >= 50000 && $needint < 100001){

	// 	$newgoal = $need + 100000;

	// } else if ($needint > 100000 && $needint < 200001){

	// 	$newgoal = $need + 100000;	

	// } else if ($needint > 200000 && $needint < 300001){

	// 	$newgoal = $need + 100000;

	// } else if ($needint > 300000 && $needint < 400001){

	// 	$newgoal = $need + 100000;

	// } else if ($needint > 400000 && $needint < 500001){

	// 	$newgoal = $need + 100000;

	// } else if ($needint > 500000 && $needint < 1000001){

	// 	$newgoal = $need + 500000;
	// }

	$post_data = array(
        'ID' => $id_thispsot,
        'meta_input'     => [ 'need_goal' => $newgoal],

        );
        $post_ads = wp_update_post( $post_data );	



die();
}


//экспорт подписей
add_action( 'wp_ajax_getsubscribers', 'getsubscribers_ajax_load' );
add_action( 'wp_ajax_nopriv_getsubscribers', 'getsubscribers_ajax_load' );
 
function getsubscribers_ajax_load(){
	
	$id_thispsot = sanitize_text_field($_POST['mupostids']);
	$allsubskr = get_field("podpisi", $id_thispsot);

	$post_sebscribs = array();

	
$temp = tmpfile();
fwrite($temp, "записываем во временный файл");
fseek($temp, 0);
echo fread($temp, 1024);

//создаем временный файл

header('Content-Type: application/vnd.ms-excel; format=attachment;');
header('Content-Disposition: attachment; filename=downloaded_' . date('Y-m-d H:i:s') . '.xls');
header('Expires: Mon, 18 Jul 1998 01:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

echo '<meta http-equiv="content-type" content="text/html; charset=utf-8"><table>';
foreach ($allsubskr as $value) {
	echo '<tr><th>'.$value.'</th></tr>';
}
echo '</table>';

//создаем временный файл


die();
}

//количество подписей
add_shortcode('sebstyte','sebstyte_load');
function sebstyte_load(){
	$post_id = get_the_ID();
	$status = get_field("status", $post_id);
	if ($status){
		$html = '<div style="opacity: 0.65;position:absolute;z-index: 999;font-size: 20px;padding: 0.6em 1.2em;line-height: 1;font-weight: 600;margin: 10px;border-radius: 999px;color: white;';
		if($status == 'win'){
			$html = $html.'background-color: #ed1c24;">Победа</div>';
		}
		if($status == 'urgent'){
			$html = $html.'background-color: #ffffff;color:#ff0000;">СРОЧНО</div>';
		}
		if($status == 'success'){
			$html = $html.'background-color: #ffffff;color:#0074bc;">Успех</div>';
		}
		echo $html;
	}
}

//количество подписей
add_shortcode('sebscount','sebscount_load');
function sebscount_load(){
	if($_GET['post_id']){
		$post_id = $_GET['post_id'];
	}else{
		$post_id = get_the_ID();
	}
	
	$coun_num = get_field("count_subs", $post_id);
	$old_coun_num = get_field("old_count_subs", $post_id);
	$coun_num += $old_coun_num;
	$need_co_goal = get_field("need_goal", $post_id);

			$need_gol = $need_co_goal;
			$now_gol = $coun_num;
			$now_formatgol = number_format_i18n($coun_num);
			$need_goal_form = number_format_i18n($need_co_goal);
			 
			$mygols = ($now_gol / $need_gol) * 100;

			$goalformat = number_format_i18n($mygols);

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
		<div class="bbarr_progress">
			<?php if(!$_GET['post_id']){ ?>
			<div class="top_line_progress">
				
				<div class="progressbar">
				  <span style="width: <?php echo $goalformat; ?>%; font-family: 'Roboto';"><?php echo $now_formatgol; ?></span>
				</div>

			</div>
			<?php } ?>
			<div class="bottom_line_progress">
				<?php if($_GET['post_id']){ ?>
					<center><p class="pit" style="color: white; font-family: 'Roboto';">Собрано <?php echo $now_formatgol; ?> подписей! Всем огромное спасибо!</p></center>
				<?php } else {?>
					<?php $win_petition = get_field("status", $post_id); ?>
					<?php if ($win_petition == 'win') { ?>
					<p style="font-family: 'Roboto';">Нам удалось собрать <?php echo $now_formatgol; ?> подписей! Всем огромное спасибо!</p>
					<?php } else { ?>
					<p style="font-family: 'Roboto';">Подписались <?php echo $now_formatgol; ?> человек. Помогите нам набрать <?php echo $need_goal_form; ?> подписей.</p>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	
<?php
}


//пользовательское меню
add_shortcode('usernavhead','usernavhead_load');
function usernavhead_load(){
	if (is_user_logged_in()){ 
		$user_id = get_current_user_id(); ?>
		<div class="box_top_navs">
			<div class="box_user_info">
				<?php echo get_avatar( um_user( 'ID' ), 60 ); ?>
				<div class="doble_boble">
					<p class="user_name"><?php echo get_user_meta( $user_id, 'first_name', true ); ?></p>
					
					<div class="list_user_nav">
						<p class="togle_open_nav">Мой аккаунт</p>
						<div class="user_nal_tog">
							<li class="subli_nav"><a href="/mypetishn/">Мои петиции</a></li>
							<li class="subli_nav"><a href="/account/">Управлять профилем</a></li>
							<li class="subli_nav"><a href="/logout/">Выйти</a></li>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<script>
		(function($) {
  			$(document).ready(function(){  	
  				$('.togle_open_nav').on('click', function(){
  					$('.user_nal_tog').toggleClass('opens');
  				});
  			});
		}(window.jQuery));
		</script>

	<?php } else { 
		?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		
		<div class="box_top_navs ">
			<svg class="li_nav mx-2" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
				<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
			</svg>
			<li class="li_nav alls_re"><a href="/login/">Вход</a></li>
			<li class="li_nav alls_re"><a href="/register/">Регистрация</a></li>
			<li class="li_nav mobilese"><a href="/login/">Личный кабинет</a></li>
		</div>
	<?php }
}




//заголовок нас уже
add_shortcode('wearecon','wearecon_load');
function wearecon_load(){
		$usercount = count_users();
		$resultcnt = $usercount['total_users']; 

		if ($resultcnt < 242563){
			$newresultcnt = $resultcnt + 242563;
			$newresultcntform = number_format_i18n($newresultcnt);
		} else {
			$newresultcnt = $resultcnt;
			$newresultcntform = number_format_i18n($newresultcnt);
		}
	?>
	<div class="we_arwe_count">
		<span class="title_conts">Нас уже </span><span class="count_slients"><?php echo $newresultcntform; ?></span>
		<?php if (!is_user_logged_in()){ ?>
			<div class="box_top_goregs"><a href="/register/">Присоединяйтесь сейчас!</a></div>
		<?php } ?>
	</div>


<?php
}



//шаблон письма
add_shortcode('massusers','massusers_load');
function massusers_load(){
	$post_id = get_the_ID();
	?>
	<div class="maxx_contr">
		<div class="box_masss_top">Петиция к: <?php echo get_field("for_whos", $post_id); ?></div>
		<div class="box_mass_contt requrfse">
			<p class="conty_massh "><?php echo get_field("wride_mass", $post_id); ?></p>
			<!-- <span class="bottom_yosub">[Ваше имя]</span> -->

			<style>
				.requrfse {
					font-style: italic;
				}
			</style>
		</div>
	</div>
	<?php
}




//Пользователь в аккаунте
add_shortcode('useringoname','useringoname_load');
function useringoname_load(){
	$user_id = get_current_user_id(); ?>
		
	<div class="box_user_paginf">
		<?php echo get_avatar( um_user( 'ID' ), 70 ); ?>
		<div class="doble_boble">
			<p class="user_name"><?php echo get_user_meta( $user_id, 'first_name', true ); ?> <?php echo get_user_meta( $user_id, 'last_name', true ); ?></p>
		</div>
	</div>

<?php
}




add_action( 'wp_ajax_deliteposts', 'deliteposts_ajax_load' );
add_action( 'wp_ajax_nopriv_deliteposts', 'deliteposts_ajax_load' );
 
function deliteposts_ajax_load(){

	$user_id = get_current_user_id();
    $mupostid = sanitize_text_field($_POST['mupostid']);

    $post_check = get_post($mupostid);
    $cur_user_post = $post_check->post_author;

    if ($cur_user_post == $user_id){
    	$post_data = array(
            'ID' => $mupostid,
            'post_status' => 'draft',
        );

        wp_update_post( $post_data );
    }

die();
}

add_action( 'wp_ajax_gowinpetitds', 'gowinpetitds_ajax_load' );
add_action( 'wp_ajax_nopriv_gowinpetitds', 'gowinpetitds_ajax_load' );
 
function gowinpetitds_ajax_load(){

	$user_id = get_current_user_id();
    $mupostid = sanitize_text_field($_POST['mupostid']);

    $post_check = get_post($mupostid);
    $cur_user_post = $post_check->post_author;

    if ($cur_user_post == $user_id){
    	$post_data = array(
            'ID' => $mupostid,
        );

        wp_update_post( $post_data );

        $post_id = wp_update_post( $post_data );
        //$cat_ids = array( 'petition', 'wins' );
    	wp_set_object_terms( $post_id, 'wins', 'category' );

    }

die();
}

add_action( 'wp_ajax_goactivepost', 'goactivepost_ajax_load' );
add_action( 'wp_ajax_nopriv_goactivepost', 'goactivepost_ajax_load' );
 
function goactivepost_ajax_load(){

	$user_id = get_current_user_id();
    $mupostids = sanitize_text_field($_POST['mupostids']);

    $post_check = get_post($mupostids);
    $cur_user_post = $post_check->post_author;

    if ($cur_user_post == $user_id){
    	$post_data = array(
            'ID' => $mupostids,
            'post_status' => 'publish',
        );

        wp_update_post( $post_data );
    }

die();
}

add_action( 'wp_ajax_updatethispost', 'updatethispost_ajax_load' );
add_action( 'wp_ajax_nopriv_updatethispost', 'updatethispost_ajax_load' );
 
function updatethispost_ajax_load(){

	$user_id = get_current_user_id();
    $mupostids = sanitize_text_field($_POST['idthpost']);

    $post_check = get_post($mupostids);
    $cur_user_post = $post_check->post_author;

    if ($cur_user_post == $user_id){

    	$zagolovk_red = sanitize_text_field($_POST['zagolovk_red']);
    	$descrip_red = sanitize_textarea_field($_POST['descrip_red']);
    	$for_whos_red = sanitize_text_field($_POST['for_whos_red']);
    	$wride_mass = sanitize_textarea_field($_POST['wride_mass']);
		$old_need = sanitize_text_field($_POST['old_need']);


    	$post_data = array(
            'ID' => $mupostids,
            'post_status' => 'publish',
            'post_title'    => $zagolovk_red,
        	'post_content'  => $descrip_red,
            'meta_input'     => [ 'for_whos' => $for_whos_red,
                                  'wride_mass' => $wride_mass,
								  'old_count_subs' => $old_need,
                                ],
        );

        wp_update_post( $post_data );
    }

die();
}


//форма спасибо за подпись - получаем петицию
add_shortcode('sandmail','sandmail_load');
function sandmail_load(){

	$to = 'xomkaximik@gmail.com';
	$subject = 'Письмо с сайта djhlf ghtcyj';
	$message = 'Gbcmvj nfr ct,t';
// wp_mail( $to, $subject, $message, $headers, $attachments );
	$result = wp_mail( $to, $subject, $message);
	echo $result;
	?>
	
	<?php
}

//форма спасибо за подпись - получаем петицию
add_shortcode('thankssubs','thankssubs_load');
function thankssubs_load(){

	//получим урл
	//вставим в блоки текст и картинку из урл

	?>
	<script>
		(function($) {
		$(document).ready(function(){

			var hashpage = document.location.hash;
			var allurl = document.location.search;	

			strsrk = hashpage.replace(/#/g, '');
			
			$('.need_img_petitl').attr('src', strsrk);
			
			url = allurl.replace('?url=', '');

			$('.copylink').on('click', function(e){
				e.preventDefault();
			
				var tmp = $("<textarea>");
				$("body").append(tmp);
				tmp.val(url).select();
				document.execCommand("copy");
				tmp.remove();
		    	
		    	$(this).html('Ссылка скопирована');
		    	
			});


		});
		}(window.jQuery));
	</script>

	<div class="box_tiop_image">
		<img src="" class="need_img_petitl">
	</div>

	<?php
}
//форма спасибо за подпись - сама форма
add_shortcode('thankform','thankform_load');
function thankform_load(){
?>
	
	<?php $postids = get_the_ID(); ?>
	<p class="box_title_ptt title_thankk">Утройте силу Вашей подписи!</p>

	<?php 
	
	$url = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$query = parse_url($url, PHP_URL_QUERY); 
	$query_clear = str_replace("url=", "", $query);
	?>

	<div class="boxfor_sharethis">
		<div class="box_list_socials">
			<div class="maintitle_share">Вы успешно подписали обращение. Теперь поделитесь им, пожалуйста, с другими, чтобы его усилить.</div>
			<div class="single-share">
			  <a class="vk" href="https://vk.com/share.php?url=<?php echo $query_clear ?>%3Futm_source=vk" target="_blank">Поделиться в VK</a>
			  <a class="twitt" href="https://twitter.com/home?status=<?php echo $query_clear ?>%3Futm_source=tw" target="_blank">Поделиться в Twitter</a>
			  <a class="teleg" href="https://telegram.me/share/url?url=<?php echo $query_clear ?>%3Futm_source=tg" target="_blank">Поделиться в Telegram</a>
			  <a class="whatsap" href="whatsapp://send?text=<?php echo $query_clear ?>%3Futm_source=wp" data-action="share/whatsapp/share" target="_blank">Поделиться в WhatsApp</a>
			  <a class="copylink" href="#" data-values="<?php echo $query_clear ?>? utm_source=link" >Скопировать ссылку</a>
			</div>
			<div class="scip_box"><a href="#" class="scip_pg_sh" id="scip_pg_sh">Пропустить</a></div>
		</div>
	</div>

	<div class="boxfor_pojertvov">
		<div class="box_list_pojertvov">
			<div class="maintitle_share">Вы серьезно поможете победе, сделав пожертвование, чтобы помочь ее распространению и нашей работе!</div>
			<div class="maintitle_und_shar">Всего за час под этой петицией могут появиться тысячи новых подписей, если каждый пожертвует сумму, равную стоимости нескольких чашек кофе. Поможете ли Вы достижению победы?</div>
			<div class="btms_row_pay">
				<a href="#" class="yesbtm gopay">Да, я хочу пожертвовать</a>
				<a href="/" class="nobtm skp_nobtm">Пожалуй, в другой раз</a>
			</div>
		</div>


		<div class="box_donate_ept inpoepfons">
			
			<div class="top_level_staps_don">
				<div class="step_one step active">
					<div class="title_steps">Сумма</div>
					<div class="number_steps">1</div>
				</div>
				<div class="step_two step">
					<div class="title_steps">Контакты</div>
					<div class="number_steps">2</div>
				</div>
				<div class="step_yhr step">
					<div class="title_steps">Оплата</div>
					<div class="number_steps">3</div>
				</div>
			</div>

			<div class="middle_inform">
				<div class="left_informs"><img src="/wp-content/uploads/2022/06/padlock2.png"><span>Безопасные платежи</span></div>
			</div>

			<form id="new_donatpet" class="new_donatpet" name="new_donatpet" action="#" method="post">
			
			<div class="amount_block active">
				<div class="btms_row_pays">
					<a data-amount="50" class="col-3 btm_itm">50 руб.</a>
					<a data-amount="100" class="col-3 btm_itm">100 руб.</a>
					<a data-amount="200" class="col-3 btm_itm">200 руб.</a>
					<a data-amount="300" class="col-3 btm_itm">300 руб.</a>
					<a data-amount="400" class="col-3 btm_itm">400 руб.</a>
					<a data-amount="500" class="col-3 btm_itm">500 руб.</a>
					<input type="number" placeholder="Другая сумма" name="oth_amount" id="oth_amount">
					<input type="hidden" name="summ_amouth" id="summ_amouth" value="0">
				</div>

				<div class="next_stp_btm">
					<a href="#" class="nexel_brtm tocontse">Следующий шаг</a>
				</div>
			</div>

			<div class="cont_block">
				<div class="box_formgrp col_one">
					<label for="don_name" class="form-label">Имя</label>
					<input class="form_inpt" id="don_name" name="don_name" value="<?php echo get_user_meta( $user_id, 'first_name', true ); ?>">
				</div>
				<div class="box_formgrp col_one">
					<label for="don_fami" class="form-label">Фамилия</label>
					<input class="form_inpt" id="don_fami" name="don_fami" value="<?php echo get_user_meta( $user_id, 'last_name', true ); ?>">
				</div>
				<div class="box_formgrp col_one">
					<label for="don_email" class="form-label">Email</label>
					<input type="email" class="form_inpt" value="<?php echo $user_info->user_email; ?>" id="don_email" name="don_email" required>
				</div>

				<div class="box_regilar_poj">
					<input type="checkbox" name="regilyarpoj" class="false_check" id="regilyarpoj">
					<label for="regilyarpoj">Хочу жертвовать регулярно</label>
				</div>

				<div class="next_stp_btm">
					<button type="submit" class="nexel_brtm_btm">Перейти к оплате</button>
				</div>
			</div>

			</form>

			<div class="box_under_don_back"></div>
		</div>
	</div>


<script>
(function($) {
	$(document).ready(function() {

		$('#scip_pg_sh').on('click', function(e){
  			e.preventDefault();
  			$('.boxfor_sharethis').hide();
  			$('.boxfor_pojertvov').show();
  		});

			  		$('.yesbtm.gopay').on('click', function(e){
			  			e.preventDefault();
			  			$('.box_donate_ept').addClass('active');
			  			$(this).hide();
			  			$('.btms_row_pay .nobtm').hide();
			  			$('.box_under_don_back').html('<div class="btms_row_pay"><a href="/" class="nobtm skp_nobtm">Пожалуй, в другой раз</a></div>');
			  		});	

			  		$('.step_one').on('click', function(e){
			  			e.preventDefault();
			  			$('.amount_block').addClass('active');
			  			$('.step_two').removeClass('active');
			  			$('.cont_block').removeClass('active');
			  		});	
			  		$('.btm_itm').on('click', function(e){
			  			e.preventDefault();
			  			var vallue = $(this).data('amount');
			  			$('#summ_amouth').val(vallue);
			  			$('.amount_block').removeClass('active');
			  			$('.cont_block').addClass('active');
			  			$('.step_two').addClass('active');
			  			$('.nexel_brtm_btm').html(`Пожертвовать ${vallue} руб.`);
			  		});	 
			  		$('#oth_amount').on('change', function(){
			  			var vallues = $(this).val();
			  			$('#summ_amouth').val(vallues);
			  			$('.nexel_brtm_btm').html(`Пожертвовать ${vallues} руб.`);
			  		});  
			  		$('.tocontse').on('click', function(e){
			  			e.preventDefault();
			  			var checksum = $('#summ_amouth').val();
			  			if(checksum !== '0'){
			  				$('.amount_block').removeClass('active');
			  				$('.cont_block').addClass('active');
			  				$('.step_two').addClass('active');
			  				$('.nexel_brtm_btm').html(`Пожертвовать ${checksum} руб.`);
			  			} else {
			  				alert('Необходимо указать сумму пожертвования');
			  			}
			  		});  

	});
}(window.jQuery));
</script>	
<?php
}




//спонсирование форма
add_shortcode('sponsorforms','sponsorforms_load');
function sponsorforms_load(){

	$user_id = get_current_user_id(); 
	$user_info = get_userdata($user_id);
?>

<div class="box_form_us_dont">
	<div class="top_level_staps_don">
		<div class="step_one step active">
			<div class="title_steps">Сумма</div>
			<div class="number_steps">1</div>
		</div>
		<div class="step_two step">
			<div class="title_steps">Контакты</div>
			<div class="number_steps">2</div>
		</div>
		<div class="step_yhr step">
			<div class="title_steps">Оплата</div>
			<div class="number_steps">3</div>
		</div>
	</div>

	<div class="middle_inform">
		<div class="left_informs"><img src="/wp-content/uploads/2022/06/padlock2.png"><span>Безопасные платежи</span></div>
	</div>

	<form id="new_donat" class="new_donat" name="new_donat" action="#" method="post">
	
	<div class="amount_block active">
		<div class="btms_row_pays">
			<a data-amount="50" class="col-3 btm_itm">50 руб.</a>
			<a data-amount="100" class="col-3 btm_itm">100 руб.</a>
			<a data-amount="200" class="col-3 btm_itm">200 руб.</a>
			<a data-amount="300" class="col-3 btm_itm">300 руб.</a>
			<a data-amount="400" class="col-3 btm_itm">400 руб.</a>
			<a data-amount="500" class="col-3 btm_itm">500 руб.</a>
			<input type="number" placeholder="Другая сумма" name="oth_amount" id="oth_amount">
			<input type="hidden" name="summ_amouth" id="summ_amouth" value="0">
		</div>

		<div class="next_stp_btm">
			<a href="#" class="nexel_brtm tocontse">Следующий шаг</a>
		</div>
	</div>

	<div class="cont_block">
		<div class="box_formgrp col_one">
			<label for="don_name" class="form-label">Имя</label>
			<input class="form_inpt" id="don_name" name="don_name" value="<?php echo get_user_meta( $user_id, 'first_name', true ); ?>">
		</div>
		<div class="box_formgrp col_one">
			<label for="don_fami" class="form-label">Фамилия</label>
			<input class="form_inpt" id="don_fami" name="don_fami" value="<?php echo get_user_meta( $user_id, 'last_name', true ); ?>">
		</div>
		<div class="box_formgrp col_one">
			<label for="don_email" class="form-label">Email</label>
			<input type="email" class="form_inpt" value="<?php echo $user_info->user_email; ?>" id="don_email" name="don_email" required>
		</div>

		<div class="box_regilar_poj">
			<input type="checkbox" name="regilyarpoj" class="false_check" id="regilyarpoj">
			<label for="regilyarpoj">Хочу жертвовать регулярно</label>
		</div>

		<div class="next_stp_btm">
			<button type="submit" class="nexel_brtm_btm">Перейти к оплате</button>
		</div>
	</div>

	</form>
</div>

<script>
(function($) {
  	$(document).ready(function() {

  		$('.step_one').on('click', function(e){
  			e.preventDefault();
  			$('.amount_block').addClass('active');
  			$('.step_two').removeClass('active');
  			$('.cont_block').removeClass('active');
  		});	
  		$('.btm_itm').on('click', function(e){
  			e.preventDefault();
  			var vallue = $(this).data('amount');
  			$('#summ_amouth').val(vallue);
  			$('.amount_block').removeClass('active');
  			$('.cont_block').addClass('active');
  			$('.step_two').addClass('active');
  			$('.nexel_brtm_btm').html(`Пожертвовать ${vallue} руб.`);
  		});	 
  		$('#oth_amount').on('change', function(){
  			var vallues = $(this).val();
  			$('#summ_amouth').val(vallues);
  			$('.nexel_brtm_btm').html(`Пожертвовать ${vallues} руб.`);
  		});  
  		$('.tocontse').on('click', function(e){
  			e.preventDefault();
  			var checksum = $('#summ_amouth').val();
  			if(checksum !== '0'){
  				$('.amount_block').removeClass('active');
  				$('.cont_block').addClass('active');
  				$('.step_two').addClass('active');
  				$('.nexel_brtm_btm').html(`Пожертвовать ${checksum} руб.`);
  			} else {
  				alert('Необходимо указать сумму пожертвования');
  			}
  		});  

  	});
}(window.jQuery));
</script>


<?php
}


add_action( 'echo_subsers', function(){	
	$coun_num = get_field("count_subs", $post_id);
	$old_coun_num = get_field("old_count_subs", $post_id);
	$coun_num += $old_coun_num;
	$now_formatgol = number_format_i18n($coun_num);

	$terms_list = get_the_terms( $post_id, 'category' );
	foreach( $terms_list as $cur_term ){
		$res_term = $cur_term->term_id;
		if ($res_term == 39){
			$win_petition = 'yes';
		} else {
			$win_petition = 'no';
		}
	}

	echo '<p class="box_bagetexts">'.$now_formatgol.' <span>подписей</span></p>';
	
});

add_action( 'echo_blabel', function(){	
	$status = get_field("status", $post_id);
	if ($status){
		$html = '<div style="opacity: 0.85;font-size: 14px;padding: 0.6em 1.2em;;line-height: 1;font-weight: 600;margin: 10px;border-radius: 999px;color: white;';
		if($status == 'win'){
			$html = $html.'background-color: #ed1c24;">Победа</div>';
		}
		if($status == 'urgent'){
			$html = $html.'background-color: #ffffff;color:#ff0000;">СРОЧНО</div>';
		}
		if($status == 'success'){
			$html = $html.'background-color: #ffffff;color:#0074bc;">Успех</div>';
		}
		echo $html;
	}
});

add_shortcode('city_list','city_list_load');
function city_list_load(){
    get_template_part('city_list');
}

add_shortcode('region_list','region_list_load');
function region_list_load(){
    get_template_part('region_list');
}


add_shortcode('check_email','check_email_load');
function check_email_load(){

	if($_GET['email']){
		if($_GET['num']){
			$id = $_GET['num'];
			$email_sub = $_GET['email'];

			global $wpdb;
			$emails = $wpdb->get_results( "SELECT id, email, status FROM wp_users_nosign WHERE id = $id");

			if($emails){
				foreach($emails as $email){
					if($email->email = $email_sub){
						if($email->status){
							echo "<h3>Ваш адрес уже подтвержден</h3>";
						}
						else{
							$wpdb->update( 'wp_users_nosign', array( 'status' => 1),array('ID'=>$id));
							echo "<h3>Ваш адрес подтвержден</h3>";
						}
					}
				}
			}
		}
	}else{
		echo "<h3>Ваш электронный адрес не подтвержден</h3>";
		echo "<h4>Пройдите по ссылке отправленной в письме для подтверждения.</h4>";
	}
}

add_shortcode('deletesubscript','deletesubscript_load');
function deletesubscript_load(){
	

	
	if($_GET['status'] == 'delete'){
		$id = sanitize_text_field($_GET['id']);
		global $wpdb;
		$get = $wpdb->get_results( "SELECT pitition_id, email, name, surname, region FROM wp_signature WHERE id = $id");

		$user_id = get_current_user_id(); 
		$user_info = get_userdata($user_id);

		if($get){
			foreach ($get as $gets){
				if($gets->email == $user_info->user_email){
					$wpdb->query( "DELETE FROM `wp_signature` WHERE `id` = $id");
					echo '<h3>Ваша подпись успешно удалена</h3>';
				}
				else echo '<h3>Произошла не ошибка. Попробуйте позже.</h3>';
			}
			
		}
		else echo '<h3>Данная подпись не айдена</h3>';
		
	}else{
		if(is_user_logged_in()){
		?>
	
			<h3>Вы уверены что хотите удалить свою подпись под петицией?</h3>
			<p>В этом случае нажмите на кнопку ниже</p>

			<a href="/udalenie-podpisi?status=delete&id=<?php echo $_GET['id'] ?>" class="btn btn"><button>Удалить</button></a>

		<?php
		}
		else {
		?>
			<h3>Перед удалением петиции авторизируйтесь на сайте</h3>
		<?php
		}
	}

}

//список в админке
add_action( 'wp_ajax_goloadsubcjrb', 'goloadsubcjrb_ajax_load' );
add_action( 'wp_ajax_nopriv_goloadsubcjrb', 'goloadsubcjrb_ajax_load' );
 
function goloadsubcjrb_ajax_load(){

	$postides = sanitize_text_field($_POST['piostid']);
 
	$box_subser = get_field("podpisi", $postides);
	$ai = 1;
	foreach ($box_subser as $boxubser) {
		$ai++;
		echo '<li><label><input type="checkbox" id="user'.$ai.'" name="user'.$ai.'" value="'.$boxubser.'" checked> '.$boxubser.'</label></li>';
	}

die();
}



add_action('admin_head', 'mysscstyle');
function mysscstyle() {
echo "<style>
	.novivivle_row{
		display: none!important;
	}
 </style>

<script>
(function($) {
 $(document).ready(function(){

 	var piostid = $('.postid input').val();
	jQuery.ajax({
	    type: 'POST',
	    url: '/wp-admin/admin-ajax.php',
	    data: {
	        action: 'goloadsubcjrb', 
	        piostid : piostid,
	    },
	    success: function(response){  
	       console.log(response);  
	       $('.box_subskrows .acf-checkbox-list').append(response);
	    },
	    error: function(e){
	        console.log(e);          
	    }
	});

});
}(window.jQuery));
</script>


 ";
}



add_shortcode('admen_reg','admen_regt_load');
function admen_regt_load(){

	$post_id = get_the_ID();
	echo '<a href="/peticzii-pdf/?post_id='.$post_id.'&pdf=2385" class="exp_adminpod">Скачать в PDF</a>';

}

add_shortcode('coockie_check','coockie_check_load');
function coockie_check_load(){
	?>
	<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"
    		data-bg="#F6F6F6"
    		data-fg="#000000"
    		data-link="#0074BC"
			data-divlinkbg="#0074BC"
      		data-divlink="#ffffff"
    		data-font-size ="13px"
    		data-message="Мы cохраняем файлы cookie: если не согласны то можете закрыть сайт"
    		data-moreinfo="https://stranagovorit.ru/soglashenie"
    		data-linkmsg="Пользовательское соглашение"
    		data-close-text="Принимаю">
	</script>

	<style>
		.cookieinfo{
    		padding-top: 10px;
    		padding-bottom: 10px;
			border: 2px solid #0074BC;
		}
		    
	</style>
	<?php
}

add_shortcode('not_found','not_found_load');
function not_found_load(){

	if(is_user_logged_in()){

	}else{
		?>

		<!-- CSS only -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
		<style type="text/css">
			body{
				background-color: #e8e9ed !important;
			}
		</style>
		<div class="container"><center><img width="80%" src="https://stranagovorit.ru/wp-content/uploads/cefe7b76-4fb8-4645-aeb2-764d868af8c8-2.jpg"></center></div>
		<?php
		header("Status: 404 Not Found");
		die();
	}

}

		
add_filter('site_url', 'wplogin_filter', 10, 3);
function wplogin_filter( $url, $path, $orig_scheme )
{
	$old = array( "/(wp-login\.php)/");
	$new = array( "myloginpage22");

	return preg_replace( $old, $new, $url, 1);
}



