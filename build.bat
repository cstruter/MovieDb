cd app
call npm install
xcopy /S /Y /I ".\node_modules\jquery-mobile\dist\images\*.*" "..\css\images"
call gulp
cd ../Service
call composer install
pause