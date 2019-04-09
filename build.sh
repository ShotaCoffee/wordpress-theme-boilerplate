echo '新しいバージョン名を入力してください'
vi ./style.css
cat ./style.css
echo 'テーマzipファイルを作成します'
echo 'テーマファイル名を入力してください'
read str
zip $str *.php build components style.css screenshot.png -r
