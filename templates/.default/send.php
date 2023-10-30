<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$back = "<p><a href='javascript: history.back()'>Вернуться назад</a></p>";

$recaptcha = new \ReCaptcha\ReCaptcha(RE_SEC_KEY);
$resp = $recaptcha->verify($_REQUEST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

if (!$resp->isSuccess()){
foreach ($resp->getErrorCodes() as $code) {
echo "Ошибка! Проверка не пройдена. Пройдите проверку ReCaptcha";
echo $back;
return;
}
}

use Bitrix\Main\Mail\Event;


// переменные отправленные методом POST преобразованные в безопасный вид
$USER_NAME = htmlspecialcharsbx($_POST["name"]);
$USER_PHONE = htmlspecialcharsbx($_POST["phone"]);
$USER_MESSAGE = htmlspecialcharsbx($_POST["message"]);
$MAILTO = htmlspecialcharsbx($_POST["mailto"]);
// массив для предачи в метод Event::send
$arEventFields = array(
"USER_NAME" => $USER_NAME,
"USER_PHONE" => $USER_PHONE,
"USER_MESSAGE" => $USER_MESSAGE,
"MAILTO" => $MAILTO,
);

// ловим и проверяем вложение если оно есть
if (!empty($_FILES["file"]["name"])) {
// записываем в переменную последние 4 символа
$name = substr($_FILES["file"]["name"], -4, 4);
// записываем в переменную вес файла
$size = $_FILES["file"]["size"];
// проверяем вес файла
if ($size <= 3145728) {
// проверяем расширение файла
if ($name == ".jpg" || $name == ".png" || $name == ".pdf" || $name == ".txt" || $name == "docx") {
// метод сохраняет файл и регистрирует его в таблице файлов (b_file), возвращая id который потребуется в методе Event::send
$id = CFile::SaveFile($_FILES["file"], "test");
} else {
echo "Можно прикреплять файлы с расширением jpg, png, pdf, txt, docx";
echo $back;
return;
}
} else {
echo "Можно прикреплять файлы до 3 мегабайт";
echo $back;
return;
}
}
// ловим и проверяем значение переменных привязанных к обязательным полям формы
if (!empty($USER_NAME) && !empty($USER_PHONE)) {
	// метод создает почтовое событие которое будет в дальнейшем отправлено в качестве E-Mail сообщения
	$www = Event::send([
	// тип почтового события
	"EVENT_NAME" => "TEST_FORMA",
	// id почтового шаблона
	'MESSAGE_ID' => 90,
	// id сайта
	"LID" => "s1",
	// массив полей формы
	"C_FIELDS" => $arEventFields,
	// id файла
	"FILE" => array($id),
	]);

	//Создаем новый элемент информационного блока

$arFields = array(
   "ACTIVE" => "Y", 
   "IBLOCK_ID" => 4,
   "IBLOCK_SECTION_ID" => 0,
   "NAME" => $USER_NAME,
   "PROPERTY_VALUES" => array(
	   "MESSAGE" =>$USER_MESSAGE, //Производитель - свойство
	   "PHONE" =>$USER_PHONE, //Артикул производителя - свойство
	   "FILE" =>array($id) //Материал - свойство
   )
);
$oElement = new CIBlockElement();
$idElement = $oElement->Add($arFields, false, false, true); 
echo "Форма успешно отправлена";
echo $back;
return;
}
	


?>
