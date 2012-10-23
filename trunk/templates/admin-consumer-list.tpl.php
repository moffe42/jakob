<html>
    <head>
        <title>JAKOB - Admin</title>
    </head>
    <body>
        <h1>JAKOB Consumers</h1>
        <table border="1">
        <?php
        foreach($configs AS $config) {
            echo '<tr>';
            echo '<td>';
            echo '<a href="?action=edit&key=' . $config->consumerkey . '">' . $config->consumerkey . '</a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
            <tr>
                <td>
                    <a href="?action=create">Create</a>
                </td>
            </tr>
        </table>
    </body>
</html>
