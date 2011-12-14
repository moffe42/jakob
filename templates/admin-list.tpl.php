<html>
    <head>
        <title>JAKOB - Admin</title>
    </head>
    <body>
        <h1>Avaliable configurations</h1>
        <table border="1">
        <?php
        foreach($configs AS $config) {
            echo '<tr>';
            echo '<td>';
            echo '<a href="?action=edit&jobid=' . $config->id . '">' . $config->name . '</a>';
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
