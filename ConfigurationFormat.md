# Job configuration #

Configuration of a job in JAKOB is done with an PHP style array. The array is integer indexed. Each toplevel entry defines a call to a specific connector, with the define options. ONLY the first connector is called
automatically. The subsequent connectors are only called if specified by a previously run connector.

## Connector configuration ##
A connector configuration has the following options:

| **Identifier** | **Type** | **Required** | **Description** |
|:---------------|:---------|:-------------|:----------------|
| `data`         | _array_  | Yes          | Configuration data for the connector. See [data](ConfigurationFormat#data.md) |
| `success`      | _integer_ | No           | Pointer to the next connector to be executed if the result of the connector is successful. See [success](ConfigurationFormat#success.md)|
| `fail`         | _integer_ | No           | Ppointer to the next connector to be executed if the result of the connector is unsuccessful. See [fail](ConfigurationFormat#fail.md) |


---


### _data_ ###
Contains the configuration for the connector to be called

A connector configuration can contain the following options:

| **Identifier** | **Type** | **Required** | **Description** |
|:---------------|:---------|:-------------|:----------------|
| `_id`          | _string_ | Yes          | The id of the connector to be used. This must match the id that is configured for the conector. |
| `_priority`    | _string(`'sync'`|`'async'`)_  | Yes          | Indicates wether the connector should be called synchronous (sync) or asynchronous (async). If the connector is called async, that JAKOB will jump directly to the success connector without waiting for a response. The result of the connector is fetched either at the end or when a connector is called sync. If a connector is called sync, JAKOB will wait for the result. Depending on that result, JAKOB will either choose the success connector or the fail connector. **NOTE** only use sync if strictly necessary. Use of sync will halt the entire process until the connector has finished. |
|`_options`      | _array_  | No           |This array contains connector specific data, that will be parsed to the connector on invokation. |
| `_timeout`     |_float_   | No           | The maximum time a connector is allowed to run until the user is presented with the JAKOB UI. The values is a float in seconds where the decimal part is micro seconds.|
| `_timeoutFatal`| _boolean_ | No           | If set, than all timeouts will be fatal. Meaning that if a timeout occurs, then the attributes gathered so far is returned with out waiting for the rest of the connectors to finish.|


---


### _success_ ###
Contains a pointer to the next connector to be executed if the result of the current connector is successful. This must be the top level integer index in the configuration array. I no success/fail pointer is set then the job will terminate after the current connector has finished.


---


### _fail_ ###
Contains a pointer to the next connector to be executed if the result of the current connector is unsuccessful. This must be the top level integer index in the configuration array. I no fail/success pointer is set then the job will terminate after the current connector has finished.


---


# Example #
```
array (
  0 => array (
    'data' => array (
      '_id' => 'CULR',
      '_priority' => 'sync',
      '_timeout' => 0.5,
      '_timeoutFatal' => true,
      '_options' => array (
        'userkey' => 'wayfuser',
        'key' => 'wayfpass',
      ),
    ),
    'success' => 1,
    'fail' => 2,
  ),
  1 => array (
    'data' => array (
      '_id' => 'CPR',
      '_priority' => 'sync',
      '_options' => array (
        'userkey' => 'wayfuser',
        'key' => 'wayfpass',
      ),
    ),
  ),
  2 => array (
    'data' => array (
      '_id' => 'BBR',
      '_priority' => 'sync',
      '_options' => array (
        'userkey' => 'wayfuser',
        'key' => 'wayfpass',
      ),
    ),
  ),
)
```