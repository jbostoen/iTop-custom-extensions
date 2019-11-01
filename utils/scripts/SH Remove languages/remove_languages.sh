# copyright   Copyright (C) 2019 Jeffrey Bostoen
# license     https://www.gnu.org/licenses/gpl-3.0.en.html
# version     2019-11-01 17:26:09

iTopDir=/var/www/html/itop_2_6_1/web

# Warning: add/remove.
# By default, we will not remove English "en" and Dutch "nl"
removeLang=("cs" "da" "de" "es_cr" "fr" "hu" "it" "ja" "pt_br" "ru" "tr" "zh")

# To avoid mistakes, test first and confirm that the files listed can be deleted.
# -delete is commented by default
for i in "${removeLang[@]}"
do
   find $iTopDir -type f -name "$i.dict.*" #-delete
   find $iTopDir -type f -name "$i.dictionary.*" #-delete
   # do whatever on $i
done
