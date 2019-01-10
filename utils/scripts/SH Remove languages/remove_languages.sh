
iTopDir=/var/www/html/itop_2_6_0/web

# Warning: add/remove.
# By default, we will not remove English "en" and Dutch "nl"
removeLang=("cs" "da" "de" "es_cr" "fr" "hu" "it" "ja" "pt_br" "ru" "tr" "zh")

# To avoid mistakes, -delete is commented by default
for i in "${removeLang[@]}"
do
   find $iTopDir -type f -name "$i.dict.*" #-delete
   find $iTopDir -type f -name "$i.dictionary.*" #-delete
   # do whatever on $i
done
