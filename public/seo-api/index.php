<?php
require_once 'config.php';
require_once 'SeoController.php';
require_once 'VisionService.php';
require_once 'TextSynthService.php';
require_once 'SeoContent.php';
require_once 'ImageDescriberClient.php';
require_once 'GoogleVisionClient.php';

$config = require 'config.php';

$controller = new SeoController($config);
$controller->handleRequest();