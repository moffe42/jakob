# Writing a connector for JAKOB #
To write a JAKOB connector you must implement a class the implements the `Connector` interface. The actual implementation of the connector must be placed in the `<JAKOB-DIR>/lib/WAYF/Connector/` directory

## Connector interface ##
```
interface Connector
{
    /**
     * Default method called by the gearman worker
     * @param \GearmanJob $job Job Gearman job
     */
    public function execute(\GearmanJob $job);

    public function setStore(\WAYF\Store $store);

    public function setConfig(array $config);

    public function setup();
}
```

You must also write a configuration file for the connector. Below is an example for configuration file. The configuration must be placed in `<JAKOB-DIR>/config/connectors/`. The file name should be `connector_culr.php`
```
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

    // Number of instances of the connector to start
    'amount' => 5,
);
```
Please note that this interface is still under development, so it may change in the future.

**NOTE** that the `in_attributes`and `out_attributes`har not in use at the moment, but should be filled out accordingly for future use.