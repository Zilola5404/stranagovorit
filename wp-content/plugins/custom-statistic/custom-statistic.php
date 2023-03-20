<?php
  
/**
* Plugin Name: Statistic and UTM
* Version: 1.0.0
* Author: Albert Hubenskiy
* License: GPL2
*/

add_action( 'admin_menu', 'stat_top_menu_page', 25 );
 
function stat_top_menu_page(){
 
	add_menu_page(
		'Вывод статистики', // тайтл страницы
		'Statistic and utm', // текст ссылки в меню
		'manage_options', // права пользователя, необходимые для доступа к странице
		'statistic', // ярлык страницы
		'statistic_page_callback', // функция, которая выводит содержимое страницы
		'dashicons-images-alt2', // иконка, в данном случае из Dashicons
		21 // позиция в меню
	);
}

function statistic_page_callback(){
    $thisyear = intval(date('Y'));
    $thismath = intval(date('m'));
    $thisday = intval(date('d'));

    if($thisday <= 7){
        $count = 7 - $thisday;
        $thisday = 30 - $count;
        if($thismath > 1){
            $thismath--;
        }
        else{
            $thismath = 12;
            $thisyear--;
        }
    }
    else{
        $thisday -= 7;
    }



    if($thismath < 10) $date = "$thisyear-0$thismath-$thisday";
    else $date = "$thisyear-$thismath-$thisday";

    // echo $date;


    global $wpdb;
    $get = $wpdb->get_results("SELECT id, utm_source, timestamp FROM wp_statistic WHERE timestamp >= \"$date\"");

    
    // echo count($get);

    $wp = 0;
    $tw = 0;
    $tg = 0;
    $vk = 0;
    $lk = 0;
    $max = 0;

    foreach($get as $tag){
        if($tag->utm_source == 'wp'){
            $wp ++;
            if($wp > $max) $max = $wp;
        }
        if($tag->utm_source== 'tw'){
            $tw ++;
            if($tw > $max) $max = $tw;
        }
        if($tag->utm_source== 'tg'){
            $tg ++;
            if($tg > $max) $max = $tg;
        }
        if($tag->utm_source== 'vk'){
            $vk ++;
            if($vk > $max) $max = $vk;
        }
        if($tag->utm_source== 'lk'){
            $lk ++;
            if($lk > $max) $max = $lk;
        }
         
    }

    $params = array(
        'date_query' => array(
            array(
                'after'     => array( // после этой даты
                    'year'  => $thisyear,
                    'month' => $thismath,
                    'day'   => $thisday,
                ),
                'before'    => array( // до этой даты
                    'year'  => date('Y'),
                    'month' => date('m'),
                    'day'   => date('d'),
                ),
                // 'inclusive'=> true
            )
        )
    );
    $q = new WP_Query();
	$posts = $q->query( $params );

    // echo count($posts);

    $get_subs = $wpdb->get_results("SELECT id, pitition_id FROM wp_signature WHERE create_at >= \"$date\"");

    // echo count($get);
    $subscripts = array();

    foreach($get_subs as $sub){
        if($subscripts[$sub->pitition_id]){
            $subscripts[$sub->pitition_id]++;
        }
        else{
            $subscripts[$sub->pitition_id] = 1;
        }
    }
    // echo $subscripts['1666'];
    arsort($subscripts);
    // $not_id = "";

    // foreach($subscripts as $key => $subscript){ 
    //     $not_id+= "$key,";
    // }

    $posts_all = get_posts( array(
        'numberposts' => 10000000,
        'exclude' => $not_id,
    ));

    // echo count($posts_all);

    ?>



<head>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="graph mb-5">
                    <div class="graph_name h5">Переходы с социальных сетей последюю неделю  - <?php echo count($get) ?></div>
                    <div class="mrow d-flex">
                        <div class="row_name col-2">По ссылке</div>
                        <div class="row_line col-10"><div class="line px-2 mb-1" style="width: <?php echo ($lk/$max)*100 ?>%;"><?php echo $lk ?></div></div>
                    </div>
                    <div class="mrow d-flex">
                        <div class="row_name col-2">WhatsApp</div>
                        <div class="row_line col-10"><div class="line px-2 mb-1" style="width: <?php echo ($wp/$max)*100 ?>%;"><?php echo $wp ?></div></div>
                    </div>
                    <div class="mrow d-flex">
                        <div class="row_name col-2">Telegram</div>
                        <div class="row_line col-10"><div class="line px-2 mb-1" style="width: <?php echo ($tg/$max)*100 ?>%;"><?php echo $tg ?></div></div>
                    </div>
                    <div class="mrow d-flex">
                        <div class="row_name col-2">VK</div>
                        <div class="row_line col-10"><div class="line px-2 mb-1" style="width: <?php echo ($vk/$max)*100 ?>%;"><?php echo $vk ?></div></div>
                    </div>
                    <div class="mrow d-flex">
                        <div class="row_name col-2">Twitter</div>
                        <div class="row_line col-10"><div class="line px-2 mb-1" style="width: <?php echo ($tw/$max)*100 ?>%;"><?php echo $tw ?></div></div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="graph mb-5">
                    <div class="graph_name h5">Создано петиций за последюю неделю - <?php echo count($posts) ?></div>
 
                    
                    <?php foreach($posts as $post){ ?>
                        <div class="mrow d-flex">
                            <div class="row_name col-1">
                                <center><?php echo $post->ID ?></center>
                            </div>
                            <div class="row_line col-8"><a href='/wp-admin/post.php?post=<?php echo $post->ID ?>&action=edit'><?php echo $post->post_title ?></a></div>
                        </div>
                    <?php } ?>
                        
                    
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="graph mb-5">
                    <div class="graph_name h5">Актуальные петиции за посленюю неделю (Всего подписей за эту неделю - <?php echo count($get_subs) ?>)</div>
                    <?php foreach($subscripts as $key => $subscript){ 
                        $post = get_post( $key );
                        $need = get_field("need_goal", $post->ID);
                        $old_sub = get_field("old_count_subs", $post->ID);
                        $sub = get_field("count_subs", $post->ID);
                        $sub += $old_sub;
                        
                        ?>
                        <div class="mrow d-flex">
                        <div class="row_name col-4"><a href='/wp-admin/post.php?post=<?php echo $post->ID ?>&action=edit'><?php echo $post->post_title ?></a></div>
                        <div class="row_line col-8"><div class="line px-2 mb-1" style="width: <?php echo ($sub/$need)*100 ?>%;"><?php echo "$sub/$need"  ?></div></div>
                    </div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="graph mb-5">
                    <div class="graph_name h5">Остальные петиции - <?php echo count($posts_all) ?></div>
                    <?php foreach($posts_all as $post){
                        $need = get_field("need_goal", $post->ID);
                        $old_sub = get_field("old_count_subs", $post->ID);
                        $sub = get_field("count_subs", $post->ID);
                        $sub += $old_sub;
                        ?>

                        <div class="mrow d-flex">
                        <div class="row_name col-4"><a href='/wp-admin/post.php?post=<?php echo $post->ID ?>&action=edit'><?php echo $post->post_title ?></a></div>
                        <div class="row_line col-8"><div class="line px-2 mb-1" style="width: <?php echo ($sub/$need)*100 ?>%;"><?php echo "$sub/$need"  ?></div></div>
                    </div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
    

    <style>
        .line{
            background-color: #4382eb;
            color: white;
            min-width: max-content;
        }
    </style>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <?php
}

add_shortcode('get_utm_att', 'get_utm_att_stat_load');
function get_utm_att_stat_load(){
    if($_GET['utm_source']){
        $utm_source = $_GET['utm_source'];
        $utm_medium = $_GET['utm_medium'];
        $utm_content = $_GET['utm_content'];

        if($utm_source == 'vk' || $utm_source == 'tw' || $utm_source == 'wp' || $utm_source == 'tg' || $utm_source == 'lk'){
            if(!$utm_medium) $utm_medium = 'none';
            if(!$utm_content) $utm_content = 'none';

            global $wpdb;
            $subscr = $wpdb->insert( 'wp_statistic', array(
                'utm_source' => $utm_source,
                'utm_medium' => $utm_medium,
                'utm_content' => $utm_content,
            )); 
            
        }
    }
}

?>