
## How to generate 'css' files from edited 'sass'

Run `sass --watch sass:css --style compressed`
This will watch for changes in the sass folder and auto generate css

## How to update the gymkhana beta folder 

0. Make a folder `gymkhana` in home directory
1. Mount gymkhana's /beta folder to ~ using `sshfs gymkhana@10.3.100.81:beta /home/xypnox/gymkhana/` so we don't ever disturb the original files.
sshfs https://github.com/libfuse/sshfs can be installed using standard apt install sshfs
2. Then clone the repo there. We just need to git pull in the folder to update the site after we are satisfied with the results
3. The files are also available in file browser so they can be edited by system programs (VS Code) but I would recommend editing in the repo only
4. After updating the files it is better to unmount using `fusermount -u gymkhana`
5. note that the git files will dissappear from the folder `gymkhana`. When we need to update just mount > git pull > unmount


# TODO

[x] Fix Navbar bug