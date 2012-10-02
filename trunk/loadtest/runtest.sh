#!/bin/bash

##########################
#                        #
# JAKOB load test script #
#                        #
##########################
echo -e "JAKOB load test script v. 0.1\n"

RES_COL=$(tput cols)
let RES_COL=RES_COL-8
MOVE_TO_COL="echo -en \\033[${RES_COL}G"
echo_done() {
    $MOVE_TO_COL
    echo "[ DONE ]"
    return 0
}

if [ $# -lt 6 ]; then 
    echo "Usage: runtest.sh <#workers> <#reqs> <#tests> <#concur> <#start> <#step>"
    echo "Ex. bash runtest.sh 5 1000 3" 
    echo -e "\t#workers:\tIs stricktly for naming files. You should start the appropriate number of workers your self"
    echo -e "\t#reqs:\t\tThe number of requests that should be issued"
    echo -e "\t#tests:\t\tThe number of times the test should be run"
    echo -e "\t#consur:\tNumber of maksimum concurrent connections"
    echo -e "\t#start\t\tThe amount of concurrent connections to start with"
    echo -e "\t#step\t\tThe increase in concurrent connections for each test"
    exit 
fi 

work="$1"
reqs="$2"
tests="$3"
maxcc=$4
mincc=$5
stepcc=$6

url="https://jakob.wayf.dk/job/41eacd7b860193595ff3f7aac6d7a14b374955cd/?attributes=%7B%22eduPersonPrincipalName%22%3A%5B%22kusgigvos%40orphanage.wayf.dk%22%5D%2C%22cn%22%3A%5B%22Jacob+Christiansen%22%5D%2C%22eduPersonEntitlement%22%3A%5B%22dk.wayf.orphanage.dev%22%2C%22dk.wayf.orphanage.dev-admin%22%2C%22http%3A%5C%2F%5C%2Fwayf.dk%5C%2Flive%40edu%5C%2Fstudent4%40saml.live.wayf.dk%22%5D%2C%22gn%22%3A%5B%22Jacob%22%5D%2C%22sn%22%3A%5B%22Christiansen%22%5D%2C%22eduPersonPrimaryAffiliation%22%3A%5B%22member%22%5D%2C%22organizationName%22%3A%5B%22Wayf+Orphanage%22%5D%2C%22eduPersonAssurance%22%3A%5B%221%22%5D%2C%22mail%22%3A%5B%22jach%40wayf.dk%22%5D%2C%22eduPersonScopedAffiliation%22%3A%5B%22member%40orphanage.wayf.dk%22%5D%2C%22norEduPersonLIN%22%3A%5B%22kusgigvos%40orphanage.wayf.dk%3A1302822111%22%5D%2C%22preferredLanguage%22%3A%5B%22en%22%5D%2C%22schacPersonalUniqueID%22%3A%5B%22urn%3Amace%3Aterena.org%3Aschac%3ApersonalUniqueID%3Adk%3ACPR%3A1302822111%22%5D%2C%22eduPersonTargetedID%22%3A%5B%22WAYF-DK-002d5355b56b8351977a681d082479da29fca66f%22%5D%2C%22schacHomeOrganization%22%3A%5B%22orphanage.wayf.dk%22%5D%7D&returnURL=https%3A%2F%2Fbetawayf.wayf.dk%2Fmodule.php%2Fjakob%2Fjakob.php&returnMethod=post&returnParams=%7B%22StateId%22%3A%22_2d5a577dad7ee034de9094780111b2b2ee8cbe9d71%3Ahttps%3A%5C%2F%5C%2Fbetawayf.wayf.dk%5C%2Fsaml2%5C%2Fidp%5C%2FSSOService.php%3Fspentityid%3Dhttp%253A%252F%252Fjach-sp.test.wayf.dk%252Fmodule.php%252Fsaml%252Fsp%252Fmetadata.php%252Fdefault-sp%26cookieTime%3D1348490899%26RelayState%3Dhttp%253A%252F%252Fjach-sp.test.wayf.dk%252Fmodule.php%252Fcore%252Fauthenticate.php%253Fas%253Ddefault-sp%22%7D&consumerkey=wayf&signature=eaa0b7ddd528bc7dc8bc5891dec44a6192d74cfa3d0983682e964c538fb9e4227adef9d39cdef9873199c6651463e63e526d03c737ec766cd5ab34fa728676e4"

for i in $(eval echo {$mincc..$maxcc..$stepcc})
do
    echo "Running $reqs requests, $i concurrent connections on $work workers $tests times"
    for j in $(eval echo {1..$tests})
    do
        out="www/data/run-$reqs-$i-$work-$j.tsv"
        res="www/results/run-$reqs-$i-$work-$j-res.txt"
        ab -n $reqs -c $i -g $out "$url" > $res
        echo "$j test done!" 
    done
    echo_done
done
echo "All done!"
