<?php
    use Cloudinary\Configuration\Configuration;

    $config = new Configuration();
    $config->cloud->cloudName = getenv("CLOUD_NAME");
    $config->cloud->apiKey = getenv("CLOUD_API_KEY");
    $config->cloud->apiSecret = getenv("CLOUD_API_SECRET");
    $config->url->secure = true;
    $cloudinary = new Cloudinary($config);
?>