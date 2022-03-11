@echo off

for %%I in (.) do set CurrDirName=%%~nxI
set epubname=%CurrDirName%.epub

if not %CurrDirName%==template (
	zip -r %epubname% item
	echo done
) else (
	echo error
)

pause