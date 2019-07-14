# What's here

The repo contains the source code for GymKhana's official website written in PHP, HTML, CSS, SASS and JS.

## How To Run Locally

Below are the minimal steps to get the server running locally on port 8000.

- For the running of the website, you need to [install PHP](https://www.php.net/manual/en/install.php).
- Open terminal and following below instructions.

```shell
cd /path/to/folder/with/index.php
php -S 127.0.0.1:8000
```

## How to generate 'css' files from edited 'sass'

### For the website

Run `sass --watch sass/style.scss:css/style.css --style compressed`
This will watch for changes in the sass folder and auto generate css

### For the gymk blog theme

Run `sass --watch sass/blog.scss:gymk/style.css --style compressed`
This will watch for changes in the sass folder and auto generate css in the gymk folder.

## How to update the gymkhana beta folder

1. Make a folder `gymkhana` in home directory
2. Mount gymkhana's /beta folder to ~ using `sshfs gymkhana@10.3.100.81:beta /home/xypnox/gymkhana/` so we don't ever disturb the original files.
sshfs https://github.com/libfuse/sshfs can be installed using standard apt install sshfs
0. Then clone the repo there. We just need to git pull in the folder to update the site after we are satisfied with the results
0. The files are also available in file browser so they can be edited by system programs (VS Code) but I would recommend editing in the repo only
0. After updating the files it is better to unmount using `fusermount -u gymkhana`
0. note that the git files will dissappear from the folder `gymkhana`. When we need to update just mount > git pull > unmount

## TODO

- [ ] Add more details to readme.md
