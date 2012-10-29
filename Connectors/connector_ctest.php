<?php
$config = array(
    // Connector class
    'class' => 'CTestConnector',

    // Connector ID
    'id' => 'CTest',

    // Required attributes
    'in_attributes' => array(
        'eduPersonPrincipalName',    
    ),
    // Returned attributes
    'out_attributes' => array(
        'schacCountryOfCitizenship',
    ),
);
