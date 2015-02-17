Indeed API
===============================================================================

PHP interface to Indeed job search API.

Description about queries format you can see there https://ads.indeed.com/jobroll/xmlfeed

But in original documentation you will not find this

- Query for search specific job by him jobkey: ```q=jobkey:c798c4fed37edf59``` or several keys ```q=jobkey:01ba63a5b2d8e3d4,jobkey:f89e5b8175aada87```
- Query by title: ```q=title:Title+of+job``` will returns all jobs matched with this title
- Query by one of any word from list: ```q=php+or+js+or+javascript+or+css```

in this examples the char "+" is encoded "space" char.

Example of Usage
-------------------------------------------------------------------------------

    <?php

    require 'indeed-api.php';

    $indeedAPI = new IndeedAPI( 123455667 );
    $indeedAPI->setParams( array(
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

Contributor(s)
-------------------------------------------------------------------------------

- AdamasAntares https://github.com/adamasantares


Releases
-------------------------------------------------------------------------------

### 1.0.8 (2015-02-07)

Initial release

* Query the job search API
* Pass in default values
* Choose between JSON or XML feed
* Choose to return object or raw document
* Fix useragent and userip for CLI
