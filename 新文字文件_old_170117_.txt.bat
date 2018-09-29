set GIT_PATH="..\PortableGit\bin\git.exe"
set BRANCH="github"

%GIT_PATH% --version
%GIT_PATH% config --list

%GIT_PATH% pull %BRANCH% master

pause

%GIT_PATH% add -A
%GIT_PATH% commit -am "Auto-committed on %date%"
%GIT_PATH% push %BRANCH%


pause
exit



