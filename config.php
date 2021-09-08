<?php
    use Cloudinary\Configuration\Configuration;

    $config = new Configuration();
    $config->cloud->cloudName = 'jim-marketplace';
    $config->cloud->apiKey = '276669241878884';
    $config->cloud->apiSecret = 'CeiR-Bmx9mYxAIfxuy67mM2wtBg';
    $config->url->secure = true;
    $cloudinary = new Cloudinary($config);
?>