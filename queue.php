<?php
    exec('nohup /usr/local/php/7.1/bin/php artisan queue:work --daemon &');