<!DOCTYPE html>
<html lang="en">
    <head>
        <title>JAKOB - Benchmark results</title>
        <meta charset="utf-8" />
        <meta name="robots" content="none" />
        <meta name="application-name" content="JAKOB - Benchmark results" />
    </head>
    <body>
    <?php
    $files = array();
    $dir = new DirectoryIterator(dirname(__FILE__) . "/plots/");
    foreach ($dir as $fileinfo) {
        if (!$fileinfo->isDot()) {
            $res = explode('-', $fileinfo->getFilename());
            $files[(int)$res[3]][(int)$res[1]][(int)$res[2]][(int)substr($res[4], 0, -4)] = $fileinfo->getFilename();
        }
    }
    ksort($files);

    echo "<h1>JAKOB - Benchmark results</h1>";
    echo "<table border=1>";
        foreach ($files AS $kworkers => $workers) {
            $worker = $workers['1000'];
            ksort($worker);
            echo "<tr><th colspan=5>Workers: {$kworkers}</th></tr>";
            foreach ($worker AS $kccs => $ccs) {
                ksort($ccs);
                echo "<tr>";
                echo "<th>CCC:. {$kccs}</th>";
                foreach ($ccs AS $test) {
                    //echo $test . "\n";
                    echo "<td>";
                    echo "<a href=\"http://jakob-benchmark.test.wayf.dk/plots/{$test}\"><img src=\"http://jakob-benchmark.test.wayf.dk/plots/{$test}\" width=\"400\" height=\"200\"></a><br />";
                    echo "<ul>";
                    $data = str_replace('.png', '.tsv', $test);
                    echo "<li><a href=\"http://jakob-benchmark.test.wayf.dk/data/{$data}\">Data</a></li>";
                    $result = str_replace('.png', '-res.txt', $test);
                    echo "<li><a href=\"http://jakob-benchmark.test.wayf.dk/results/{$result}\">Result</a></li>";
                    echo "</ul>";
                    echo "</td>";
                }
                echo "<tr />";
            }
        }
    echo "</table>";
    ?>
    </body>
</html>
