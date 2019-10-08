<?php
    echo exec('nohup /usr/local/php/7.1/bin/php ../artisan queue:work --daemon > my.log 2>&1 &
    echo $! > save_pid.txt &');
    // echo exec('ps -ef | grep nohup');