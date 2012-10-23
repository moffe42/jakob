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
        <h1>Edit consumer</h1>
        <form action="?action=save" method="post">
            <fieldset>
                <legend>Edit</legend>
                <table border="1">
                    <tr>
                        <td>
                            <lable for="key">Consumerkey</lable>
                        </td>
                        <td colspan="3">    
                            <input type="hidden" id="origkey" name="origkey" value="<?php echo $config->consumerkey; ?>" />
                            <input type="text" id="key" name="key" value="<?php echo $config->consumerkey; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <lable for="secret">Consumersecret</lable>
                        </td>
                        <td colspan="3">    
                            <input type="text" id="secret" name="secret" value="<?php echo $config->consumersecret; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <lable for="email">Email</lable>
                        </td>
                        <td colspan="3">    
                            <input type="text" id="email" name="email" value="<?php echo $config->email; ?>" />
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
