<?php
$config = array(
    // Connector class
    'class' => 'CULRConnector',

    // Connector ID
    'id' => 'CULR',

    // Required attributes
    'in_attributes' => array(
        'shacPersonalUniqueID',    
    ),
    // Returned attributes
    'out_attributes' => array(
        'Provider-ID',
        'Provider-ID-type',
        'Local-ID-value',
        'norEduPersonLIN',
        'Muncipality-number' 
    ),
);
