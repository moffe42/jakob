<?php
$config = array(
    // Connector class
    'class' => 'SBConnector',

    // Connector ID
    'id' => 'SB',

    // Required attributes
    'in_attributes' => array(
        'shacPersonalUniqueID',    
    ),
    // Returned attributes
    'out_attributes' => array(
        'eduPersonScopedAffiliation',
    ),
);
