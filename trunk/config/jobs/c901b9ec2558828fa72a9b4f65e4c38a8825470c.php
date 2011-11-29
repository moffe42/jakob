<?php
$job = array(
    'tasks' => array(
        0 => array(
            '_id' => 'CPR',    
            '_priority' => 'sync',
            '_options' => array(
                'userkey' => 'wayf',        
                'key' => 'wayf4ever',        
            ),
            '_success' => 1,
        ),
        1 => array(
            '_id' => 'VIP',    
            '_priority' => 'async',
            '_options' => array(
                'userkey' => 'wayf',        
                'key' => 'wayf4evervip',        
            ),
        ),
        2 => array(
            '_id' => 'CPR',    
            '_priority' => 'async',
            '_options' => array(
                'userkey' => 'wayf',        
                'key' => 'wayf4ever',        
            ),
        ),
    ),
);
