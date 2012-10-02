#!/bin/bash

regex="(run-([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})-([0-9]{1,2})).tsv"

for file in www/data/*
do
    if [[ ${file} =~ $regex ]]; then
        echo "Sorting ${file}"
        sort -k 1 ${file} -o ${file}

        echo "Plotting ${file}"
        max=`sort -k 9 ${file} -n |tail -n 1|awk '{print $9}'`
        if [ $max -gt 5000 ]; then
            constplot="1000 lc rgb \"green\" lw 3 title \"Lower threshold\", 5000 lc rgb \"red\" lw 3 title \"Upper threshold\""
        else
            constplot="1000 lc rgb \"green\" lw 3 title \"Lower threshold\""
        fi

        gnuplot << EOF
set terminal pn size 1600, 800
set output "www/plots/${BASH_REMATCH[1]}.png"
set title "${BASH_REMATCH[2]} requests - ${BASH_REMATCH[4]} workers - Test: ${BASH_REMATCH[5]}"
set size 1,0.7
set grid y
set xlabel "request"
set xtics 100
set grid front
set ylabel "response time (ms)"
set style line 2 lc rgb "red" lw 3
set grid xtics nomxtics ytics nomytics noztics nomztics nox2tics nomx2tics noy2tics nomy2tics nocbtics nomcbtics
set grid front   linetype 0 linewidth 1.000,  linetype 0 linewidth 1.000
set key outside right top vertical Right noreverse enhanced autotitles nobox
plot "www/data/${BASH_REMATCH[0]}" using 9 with lines lc rgb "blue" title "${BASH_REMATCH[3]} ccc", $constplot
EOF
    fi
done
