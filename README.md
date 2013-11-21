Indeed API Class
===============================================================================

PHP class to interact with the Indeed API.

I will write better documentation when I can but look through the source for
more detail.


Example of Usage
-------------------------------------------------------------------------------

    <?php

    require 'indeed-api.php';

    $indeedAPI = new IndeedAPI( 123455667 );
    $indeedAPI->setDefaultParams( array(
        'co' => 'gb'
    ) );

    // Pass a basic query
    $output = $indeedAPI->query('web developer');
    print_r($output);

    // Pass in more options
    $output = $indeedAPI->query(array(
        'q' => 'web developer',
        'l' => 'London',
        'start' => 10,
        'limit' => 3
    ));
    print_r($output);


Author(s)
-------------------------------------------------------------------------------

- Neil Sweeney <neil.sweeney@fubra.com>