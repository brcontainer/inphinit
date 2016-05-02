@echo off
set PHP_BIN="C:\php\php.exe"
set PHP_INI="C:\php\php.ini"

%PHP_BIN% -S localhost:8000 -c %PHP_INI%
pause
