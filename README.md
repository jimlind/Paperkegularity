Paperkegularity Installation
===========================
1. Checkout this Repository

2. Install Composer

        $ curl -sS https://getcomposer.org/installer | php

3. Install Dependencies

        $ php composer.phar install

4. Create a config.php file in the ./web/ directory using your own Twitter tokens.

        <?php
        $app['twitter.key']          = 'FOO';
        $app['twitter.secret']       = 'BAR';
        $app['twitter.token']        = 'BAZ';
        $app['twitter.token.secret'] = 'QUZ';

5. Build your own .htaccess files. You know your server best.
