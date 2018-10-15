<?php
require_once './vendor/thiagoalessio/tesseract_ocr/src/Command.php';
require_once './vendor/thiagoalessio/tesseract_ocr/src/FriendlyErrors.php';
require_once './vendor/thiagoalessio/tesseract_ocr/src/Option.php';
require_once './vendor/thiagoalessio/tesseract_ocr/src/TesseractOCR.php';
require_once './vendor/autoload.php';
use thiagoalessio\TesseractOCR\TesseractOCR;
$text = php_ocr_recognize();
echo (new TesseractOCR('https://github.com/thiagoalessio/tesseract-ocr-for-php/raw/master/tests/EndToEnd/images/text.png'))
    ->run();
echo "5";