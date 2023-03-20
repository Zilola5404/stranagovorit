<?php 
$id_thispsot = $_GET['post_id'];
// echo $id_thispsot;
$post_id = $id_thispsot;

$myrows = $wpdb->get_results( "SELECT pitition_id, name, surname, region, city, create_at FROM wp_signature WHERE pitition_id = $id_thispsot");
$post = get_post( $id_thispsot );
?>

<div class="post_title">
    <h3><?php echo $post->post_title ?></h3>
    <br>
</div>

<div class="post_img" style="align-items: center;">
    <?php echo get_the_post_thumbnail( $post_id, $size = 'large', 'style=width: 100%'); ?>
    <?php echo do_shortcode('[sebscount]'); ?>
    <br><br><br>
</div>

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

<table>
    <tr>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Регион</th>
        <th>Город</th>
        <th>Время подписи</th>
    </tr>
    
    <?php foreach($myrows as $row){
        echo "<tr>";
        echo "<td>" . $row->surname . "</td>";
        echo "<td>" . $row->name . "</td>";
        echo "<td>" . $row->region . "</td>";
        echo "<td>" . $row->city . "</td>";
        echo "<td>" . $row->create_at . "</td>";
        echo "</tr>";
    } ?>
    
</table>

