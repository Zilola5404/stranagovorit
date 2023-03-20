<?php
  
/**
* Plugin Name: Redact subscripts
* Version: 1.0.0
* Author: Albert Hubenskiy
* License: GPL2
*/

add_action( 'wp_ajax_editsubskr', 'editsubskr_ajax_load' );
 
function editsubskr_ajax_load(){
	global $wpdb;
    $subid = sanitize_text_field($_POST['id']);
	$email = sanitize_text_field($_POST['email']);
    $surname = sanitize_text_field($_POST['surname']);
    $name = sanitize_text_field($_POST['name']);
    $region = sanitize_text_field($_POST['region']);
    $city = sanitize_text_field($_POST['city']);
    $wpdb->update( 'wp_signature', [ 'email' => "$email", 'surname' => "$surname", 'name' => "$name", 'region' => "$region", 'city' => "$city" ], [ 'ID' => $subid ] );
	
}

add_action( 'admin_menu', 'true_top_menu_page', 25 );
 
function true_top_menu_page(){
 
	add_menu_page(
		'Вывод петиции', // тайтл страницы
		'Петиции', // текст ссылки в меню
		'manage_options', // права пользователя, необходимые для доступа к странице
		'true_subscripts', // ярлык страницы
		'true_subscripts_page_callback', // функция, которая выводит содержимое страницы
		'dashicons-images-alt2', // иконка, в данном случае из Dashicons
		20 // позиция в меню
	);
}


 
function true_subscripts_page_callback(){
    global $wpdb;

    if(!$_GET['post_id']){
        $posts = get_posts( array(
            'numberposts' => 10000000,
        ));

        ?>

        <table class="wp-list-table widefat fixed striped table-view-list posts" style="margin: 0 20px 0 0;">
            <tr>
                <th>Навание петиции</th>
                <th>Действия</th>
            </tr>
            
            <?php foreach($posts as $post){
                echo "<tr>";
                echo "<td><a href='/wp-admin/post.php?post=$post->ID&action=edit'>$post->post_title</a></td>";
                echo "<td><a href='/wp-admin/admin.php?page=true_subscripts&post_id=$post->ID'>Посмотреть подписи</a> / <a href='/pdfexp/?post_id=$post->ID&pdf=2090'>Скачать в pdf</a></td>";
                echo "</tr>";
            } ?>
            
        </table>
        <?php
    }

    if($_GET['action'] == 'delete'){
        $subid = $_GET['subid'];
        $id = $_GET['post_id'];
        $subscript = $wpdb->get_results( "SELECT id, pitition_id, email, name, surname, region, create_at FROM wp_signature WHERE id = $subid");
        if($subscript){
            $wpdb->query("DELETE FROM `wp_signature` WHERE `id` = $subid");

            $get = $wpdb->get_results( "SELECT id, email FROM wp_signature WHERE pitition_id = $id");

		    if($get){
			    $new_count = count($get);
            }
            else $new_count = 0;

            $post_data = array(
                'ID' => $id,
                'meta_input'     => [
                    'count_subs' => $new_count,
                    'postid' => $id,
                ],
            );
            $post_ads = wp_update_post( $post_data );

            echo "<h3>Подпись успешно удалена</h3>";
            echo "<a href='/wp-admin/admin.php?page=true_subscripts&post_id=$id'>Вернуться назад</a>";
        }
        else {
            echo "<h3>Данная подпись не найдена</h3>";
            echo "<a href='/wp-admin/admin.php?page=true_subscripts&post_id=$id'>Вернуться назад</a>";
        }
        
        
    }

    if($_GET['action'] == 'redact'){
        $subid = $_GET['subid'];
        $id = $_GET['post_id'];
        $subscripts = $wpdb->get_results( "SELECT id, pitition_id, email, name, city, surname, region, create_at FROM wp_signature WHERE id = $subid");
        foreach ($subscripts as $subscript){
        ?>
        <div class="redact_form">
            <center><h2>Редактирование подписи</h2></center>
            <form id="redact" class="redact" name="redact" action="#" method="post" enctype="multipart/form-data">
                <label class="label_form">Адрес электронной почты</label><br>
                <input class="imput_form" type="text" id="email" value="<?php echo $subscript->email ?>"><br>
                <label class="label_form">Фамилия</label><br>
                <input class="imput_form" type="text" id="surname" value="<?php echo $subscript->surname ?>"><br>
                <label class="label_form">Имя</label><br>
                <input class="imput_form" type="text" id="name" value="<?php echo $subscript->name ?>"><br>
                <label class="label_form">Регион</label><br>
                <input class="imput_form" type="text" id="region" value="<?php echo $subscript->region ?>"><br>
                <label class="label_form">Город</label><br>
                <input class="imput_form" type="text" id="city" value="<?php echo $subscript->city ?>"><br>
                <center><p><input type="submit"></p></center>
            </form>
        </div>
        
        <style>
            .redact_form {
                max-width: 800px;
                margin-top: 50px;
            }
            .imput_form {
                width: 100%;
                padding: 5px 15px;
                margin-bottom: 20px;
                margin-top: 5px;
            }
        </style>


        <?php
        }
        ?>
        <script>

  jQuery(document).ready(function($) {

        $('#redact').submit(function(e) {
      	e.preventDefault();

        var formData = new FormData();

       	var email = $(this).find("#email").val();
        var surname = $(this).find("#surname").val();
        var name = $(this).find("#name").val();
        var city = $(this).find("#city").val();
        var region = $(this).find("#region").val();
        var id = <?php echo $subid ?>

        formData.append('email', email);
        formData.append('surname', surname);
        formData.append('name', name);
        formData.append('region', region);
        formData.append('city', city);
        formData.append('id', id);

            formData.append("action", "editsubskr");
        		jQuery.ajax({ 
                type: 'POST',
                url: '<?php echo admin_url("admin-ajax.php") ?>',
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
               
                success: function(response){
                    window.location.href = '/wp-admin/admin.php?page=true_subscripts&post_id=<?php echo $id ?>';

                },
                error: function(e){
                    console.log(e);          
                }
            });
      });
    }(window.jQuery));
      </script>
        <?php
    }

    if($_GET['post_id'] && $_GET['action'] != 'redact' && $_GET['action'] != 'delete' && $_GET['action'] != 'save'){
        $id = $_GET['post_id'];
        $subscripts = $wpdb->get_results( "SELECT id, pitition_id, email, name, surname, region, city, create_at FROM wp_signature WHERE pitition_id = $id");
        if($subscripts){

        ?>

            <table class="wp-list-table widefat fixed striped table-view-list posts" style="margin: 0 20px 0 0;">
                <tr>
                    <th>Адрес электронной почты</th>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Регион</th>
                    <th>Город</th>
                    <th>Время подписи</th>
                    <th>Действия</th>
                </tr>
                
                <?php foreach ($subscripts as $subscript){
                    echo "<tr>";
                    echo "<td>" . $subscript->email . "</td>";
                    echo "<td>" . $subscript->surname . "</td>";
                    echo "<td>" . $subscript->name . "</td>";
                    echo "<td>" . $subscript->region . "</td>";
                    echo "<td>" . $subscript->city . "</td>";
                    echo "<td>" . $subscript->create_at . "</td>";
                    echo "<td><a href='/wp-admin/admin.php?page=true_subscripts&post_id=$id&action=delete&subid=$subscript->id'>Удалить</a> - <a href='/wp-admin/admin.php?page=true_subscripts&post_id=$id&action=redact&subid=$subscript->id'>Редактировать</a></td>";
                    echo "</tr>";
                } ?>
                
            </table>


        <?php 
        }
        else {
            echo '<h3>У данной петиции нет подписей</h3>';
            echo "<a href='/wp-admin/admin.php?page=true_subscripts'>Вернуться к списку петиций</a>";
        }
    }
}
?>