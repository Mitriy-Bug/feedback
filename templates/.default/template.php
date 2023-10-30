<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$field_file = "class=mb-2";
if(empty($arParams["REQUIRED_FIELDS"]) || in_array("FILE", $arParams["REQUIRED_FIELDS"])){
      $field_file .= " required";
} 
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<form class="p-3 bg-light mb-4" action="/local/components/test/feedback/templates/.default/send.php" method="post" enctype="multipart/form-data">
      <input class="form-control mb-2" type="text" name="name" <?php if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])): ?>required<?php endif ?>>
      <input class="form-control mb-2" type="tel" name="phone" <?php if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])): ?>required<?php endif ?>>
      <textarea class="form-control mb-2" name="message" <?php if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])): ?>required<?php endif ?>></textarea>
      <?php echo CFile::InputFile("file", 20, "", "test", "3145728","UNKNOWN", $field_file ); ?>
      <input type="hidden" name="mailto" value="<?php echo $arParams["MAIL_TO"]; ?>">
      <?php if($arParams["USE_CAPTCHA"] == "Y"): ?>
      <div class="g-recaptcha" data-sitekey="<?=RE_SITE_KEY?>"></div>
      <?endif;?>
      <input type="submit" value="Отправить сообщение">
</form>
 
