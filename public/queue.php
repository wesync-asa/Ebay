<?php
    echo exec('nohup /usr/local/php/7.1/bin/php ../artisan queue:listen --tries=3 --daemon &');
    // echo exec('ps -ef | grep nohup');