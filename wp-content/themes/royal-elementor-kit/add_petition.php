<?php 
acf_form_head();
if (is_user_logged_in()){ 
$user_id = get_current_user_id();

?>


<div class="box_content_addpetition">
	<div class="title_petitadd">Создание новой петиции</div>
	<div class="mini_title_petitadd">Ваша кампания за перемены начинается здесь!</div>

	<div class="form_petition_row">
		<div id="adds_form_message" class="infomassageuser"></div>
		<form id="new_petition" class="new_petition" name="new_petition" action="#" method="post" enctype="multipart/form-data">
			

			<div class="frm_group"> 
          <label for="zagolovk" class="form-label">Напишите заголовок петиции<span>Опишите ситуацию и расскажите, что бы вы хотели изменить.</span></label>
          <input placeholder="" class="form_inpt" id="zagolovk" name="zagolovk" required>
      </div>

      <div class="frm_group"> 
          <label for="descrip" class="form-label">Опишите проблему подробнее<span>Объясните проблему и почему она вам небезразлична. Понимая, как это повлияет на вас, вашу семью или ваше сообщество, люди с большей вероятностью поддержат ваше требование.</span></label>
          <textarea class="form_textar" id="descrip" name="descrip" required></textarea>
      </div>

      <div class="frm_group"> 
          <label for="old_need" class="form-label">Сколько подписей уже собранно?<span>Подписи собранные вне данного сайта</span></label>
          <input placeholder="" type="number" value="0" class="form_inpt go_nexell" id="old_need" name="old_need" min="0" required>
      </div>

      <div class="frm_group"> 
          <label for="for_whos" class="form-label">Кому или куда адресовано обращение?<span>Название организации, имя и должность адресата </span></label>
          <input placeholder="" class="form_inpt go_nexell" id="for_whos" name="for_whos" required>
      </div>

      <div class="frm_group hided_aft"> 
          <label for="wride_mass" class="form-label">Шаблон письма<span>Подготовьте шаблон письма для пользователей желающих оставить подпись</span></label>
          <textarea class="form_textar" id="wride_mass" name="wride_mass" required></textarea>
      </div>

      <div class="frm_group imageboxe"> 
          <label for="image_petit" class="form-label add_image_fotm">Загрузить фото<span>Ваша петиция будет выглядеть лучше, если Вы сопроводите ее картинкой</span><br><span>Разрешенный размер файла до 2 мегабайт</span><br><span class="red" style="color: red;">Загрузите превью для вашей петиции.</span></label>
          <input id="image_petit" type="file" name="image_petit" accept="image/jpg,image/jpeg">
          <img src="" id="img-preview" style="display: none">
      </div>

      <!-- <div class="frm_group chackbox">
        <input type="checkbox" id="subscribeNews" name="subscribe" value="1" checked>
        <label for="subscribeNews">Я робот</label>
    </div> -->

      <div class="form_btmgr">
          <button type="submit" id="btm_save_ptt" class="btm_save_ptt">Создать</button>
      </div>

		</form>
	</div>
</div>

<style>
    .chackbox {
        border: 1px solid silver;
        padding: 6px 10px;
        border-radius: 10px;
    }
</style>

<script>
(function($) {
  $(document).ready(function() {
    
    $('.red').hide();
    $('#image_petit').change(function () {
        var input = $(this)[0];
        if (input.files && input.files[0]) {
            
            if (input.files[0].type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.red').hide();
                    if(input.files[0].size > 2097152){
                        $('#img-preview').hide();
                        alert("Размер файла превышает допустимый размер.");
                    }
                    else{
                        $('#img-preview').attr('src', e.target.result);
                        $('#img-preview').show();
                    }
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                $('.red').show();
                $('#img-preview').hide();
                console.log('ошибка, не изображение');
            }
            
        } else {
            $('.red').show();
            $('#img-preview').hide();
            console.log('хьюстон у нас проблема');
        }
    });

        $('#image_petit').change(function() {
            var selectedFile = $(this)[0].file;
        });

      $('.go_nexell').on('click', function(){
          $('.hided_aft').show();
      });


    	$('#new_petition').submit(function(e) {
      	e.preventDefault();
        document.getElementById("btm_save_ptt").disabled = true; 

      	var infomassage = $('#adds_form_message'); 
      	$(infomassage).html('');
      	$(infomassage).html('<div class="spinner-border text-danger" role="status">Загружаем</div>');

        var formData = new FormData();
        function encodeHTML(dirtyString) {
            var container = document.createElement('div');
            var text = document.createTextNode(dirtyString);
            container.appendChild(text);
            return container.innerHTML; // innerHTML will be a xss safe string
        }

       	var zagolovk = encodeHTML($(this).find("#zagolovk").val());
        var descrip = encodeHTML($(this).find("#descrip").val());
        var for_whos = encodeHTML($(this).find("#for_whos").val());
        var wride_mass = encodeHTML($(this).find("#wride_mass").val());
        var old_need = $(this).find("#old_need").val();

        var robot = $(this).find("#subscribeNews").val();

        if($("#image_petit")[0].files[0].size < 2097152){
            var image_petit = $("#image_petit")[0].files[0];
        }else{
            alert("Размер файла превышает допустимый размер.");
            document.getElementById("btm_save_ptt").disabled = false;
            return false;
        } 
        image_petit = $("#image_petit")[0].files[0];
        
        formData.append('zagolovk_dt', zagolovk);
        formData.append('descrip_dt', descrip);
        formData.append('for_whos_dt', for_whos);
        formData.append('wride_mass_dt', wride_mass);
        formData.append('old_need_dt', old_need);

        formData.append('source_dt', image_petit);


            if (zagolovk == ''){
            $(infomassage).addClass('text-center').html('Все поля обязательны для заполнения');
                alert("Ошибка ввода данных, проверьте правильность введенных данных.");
                document.getElementById("btm_save_ptt").disabled = false; 
		        return false;
		    } 
		    if (descrip == ''){
		        $(infomassage).addClass('text-center').html('Все поля обязательны для заполнения');
                alert("Ошибка ввода данных, проверьте правильность введенных данных.");
                document.getElementById("btm_save_ptt").disabled = false; 
		        return false;
		    } 
		    if (for_whos == ''){
		        $(infomassage).addClass('text-center').html('Все поля обязательны для заполнения');
                alert("Ошибка ввода данных, проверьте правильность введенных данных.");
                document.getElementById("btm_save_ptt").disabled = false; 
		        return false;
		    }
            if (!image_petit){
                $('.red').show();
		        $(infomassage).addClass('text-center').html('Все поля обязательны для заполнения');
                alert("Ошибка ввода данных, проверьте правильность введенных данных.");
                document.getElementById("btm_save_ptt").disabled = false; 
		        return false;
		    }

            if(image_petit){
            formData.append("action", "addnewpetit");
        		jQuery.ajax({ 
                type: 'POST',
                url: '<?php echo admin_url("admin-ajax.php") ?>',
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
               
                success: function(response){     
                    $(infomassage).addClass('text-center').html('Вы успешно создали свою новую петицию!');
                    $('.new_petition').hide();
                    var time_reload;
                    time_reload = setTimeout(function(){
                        window.location.href = '/mypetishn/';
                    }, 2000);

                },
                error: function(e){
                    console.log(e);          
                }
            });
        }



      });

  });
}(window.jQuery));
</script>





<?php } else { ?>

<div class="box_content_addpetition">
		  <div class="title_petitadd">Создать мою петицию</div>
      <div class="mini_title_petitadd">Вам необходимо зарегистрироватся или войти перед добавлением петиции</div>
      <div class="btms_rows_noreg">
        <a class="btms_logs" href="/login/">Вход</a>
        <a class="btms_logs" href="/register/">Регистрация</a>
      </div>
</div>

<?php } ?>
