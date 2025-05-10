@echo off
echo Adding all files...
git add .

echo.
echo Enter commit message:
set /p commit_msg=

echo.
echo Committing changes...
git commit -m "%commit_msg%"

echo.
echo Pushing to GitHub...
git push origin main

echo.
echo Done!
pause 