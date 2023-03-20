<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'petisiya' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'petisiya' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', 'iM4bE0rL8e' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '@L^sek9a/*NlkNaT3G1]5g~@W3yeqU!^Iu#}mukRvtu1%biF^P,YO~+P/@O 5%,]' );
define( 'SECURE_AUTH_KEY',  'jK]OA_L>kmX$DKc8I+NAH~b hv+Y*6vb/_+ uCA+`]RNGA_77eR  t*h3 ]U5TD:' );
define( 'LOGGED_IN_KEY',    'jHO_:xX.mGU>3Ya6(:kcu8X|pkFLi6JQ<C02<]t?S%Mg^6frt13bBN&(3<R=@Xo)' );
define( 'NONCE_KEY',        '#DnNubn,O?};)@S3OX4`S?+_cK2I=tuY@EHHBE:rwpBNCt=^1:uL.3N,n-L@L4D]' );
define( 'AUTH_SALT',        'Aj+{5T}q=#RIq*w8UGGbyH0 /)?j:=K46,49^*e-=yve1;M^?pMfL]7E9gz_O&+J' );
define( 'SECURE_AUTH_SALT', 'x0F5]$Xd{6Gb[I0ew5vv} _<S_uefu.oys&1UoAZT&F8!J=<JskTNA+t*16!ta64' );
define( 'LOGGED_IN_SALT',   'oybL1jZAPw.Ad{tN2?M_fvSudB54jBp|S]pDrn>U??WhI:WO-Vm[RBrg@6=/3L8$' );
define( 'NONCE_SALT',       '9]%A/RSR.$~bzF#E3*J41t]l*$k[QtmTl,@@t);6jAO}u5=r%sd_>z^y^E+@%jW}' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
