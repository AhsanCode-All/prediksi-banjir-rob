<?php

$url = 'http://127.0.0.1:8000/health';

$response = file_get_contents($url);

echo $response;