<!DOCTYPE html>
<html>
    <head>
        <title>JAKOB - Admin</title>
        <meta charset="utf-8" />
        <meta name="application-name" content="JAKOB" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta name="robots" content="none" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <h1>Edit configuration</h1>
        <form action="?action=save" method="post">
            <fieldset>
                <legend>Edit</legend>
                <table border="1">
                    <tr>
                        <td>
                            <lable for="id">Id</lable>
                        </td>
                        <td style="width: 100%;" colspan="3">
                            <input type="text" id="id" name="id" value="<?php echo $config->id; ?>" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <lable for="jobid">Jobid</lable>
                        </td>
                        <td>
                            <select name="targetidp">
                                <option value="NOTVALID">-- Vælg IdP --</option>
                                <?php
                                foreach ($idpmd['md:EntityDescriptor'] AS $entity ) {
                                    $selected = '';
                                    if ($config->targetidp == $entity['_entityID']) {
                                        $selected = 'selected="selected"';
                                    }
                                    echo '<option value="' . $entity['_entityID']  . '" ' . $selected . '>' . $entity['md:Organization']['md:OrganizationDisplayName'][1]['__v'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select name="targetsp">
                                <option value="NOTVALID">-- Vælg SP --</option>
                                <?php
                                foreach ($spmd['md:EntityDescriptor'] AS $entity ) {
                                    $selected = '';
                                    if ($config->targetsp == $entity['_entityID']) {
                                        $selected = 'selected="selected"';
                                    }
                                    echo '<option value="' . $entity['_entityID']  . '" ' . $selected . '>' . $entity['md:SPSSODescriptor'][0]['md:AttributeConsumingService'][0]['md:ServiceName'][1]['__v'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td style="width: 100%;">    
                            <input type="text" id="jobid" name="jobid" value="<?php echo $config->jobid; ?>" readonly="readonly" size=60/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <lable for="name">Name</lable>
                        </td>
                        <td colspan="3">    
                            <input type="text" id="name" name="name" value="<?php echo $config->name; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <lable for="config">Configuration</lable>
                        </td>
                        <td colspan="3">    
                            <textarea id="config" name="config" style="width: 100%; height: 500px;"><?php echo var_export(unserialize($config->configuration), true); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <input type="submit" value="Save" name="Save" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </body>
</html>
