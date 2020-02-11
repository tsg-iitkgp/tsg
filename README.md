# What's here

The repo contains the source code for GymKhana's official website written in PHP, HTML, CSS, SASS and JS. The website is linked to Google Analytics using the tech coordinator's GMail account for seeing the traffic statistics of the website.

## How To Run Locally

Below are the minimal steps to get the server running locally on port 8000.

- For the running of the website, you need to [install PHP](https://www.php.net/manual/en/install.php).
- Open terminal and following below instructions.

```shell
cd /path/to/folder/with/index.php
php -S 127.0.0.1:8000
```

## Deployment on the gymkhana server

Below is the detailed step by step guide on how to get your pushed changes into deployment.

1. SSH into the gymkhana server and run `cd /home/gymkhana`.
2. Do `git remote -v` and verify that the origin points to correct repo. If not, change origin using the following commands:

```shell
git remote rm origin
git remote add origin <new_repo_url>
```

3. Run `git pull origin master`

This will ensure that all the changes that you have pushed are now deployed. There is no delay in the deployment and the website is updated immediately.

### Creating a backup on the server

The backups are stored in `/home/gymkhana/backups` in a datewise fashion and it is the backup of all the existing elements in that folder in compressed format.

0. Go to the root folder, `/home/gymkhana`
0. Run `tar -zcvf 11_Feb_2020.tar.gz --exclude="backups" --exclude=".git" ./` Change the date to current date.
0. Move the new backup to `backups` folder.

## Updating Information

The dynamic information on the website is gathered from google sheets using the `Gymkhana-BackServer` which runs on node.js and uses sheets API to gather the information. Creation of a new page is as simple as using the existing `fetchData.js` with the right endpoint (which needs to be created separately on the backend). Once this is done, a new page can be created to display the called data. 

## Updating blogs

To update blog, visit "http://www.gymkhana.iitkgp.ac.in/blog/wp-admin" and enter the credentials provided in the credentials sheet.

## Adding A New HTML Page

A new page can be added to the website by using `template.html` as the scaffold for the same. The section where the new page's body should go has been marked

NOTE: This template file should be updated regularly if there are any changes in the site wide elements such as navbar and footer.


## How to generate 'css' files from edited 'sass'

### For the website

Run `sass --watch sass/style.scss:css/style.css --style compressed`
This will watch for changes in the sass folder and auto generate css

### For the gymk blog theme

Run `sass --watch sass/blog.scss:gymk/style.css --style compressed`
This will watch for changes in the sass folder and auto generate css in the gymk folder.

### For the notice board

GymKhana notice board has two interfaces:

- `form.php`: This is to be accessed by entities entering the data and has to enter the password which should match with the phrase that is present in the `pass.txt` file.
- `notices.php`: This is the front page which is visible to the users.

The notices.db is interacted using sqlite3 from within PHP and should be regularly backed up.

NOTE: This is to be moved to a sheet infrastructre in future with a backend.

## How to update the gymkhana beta folder

1. Make a folder `gymkhana` in home directory
2. Mount gymkhana's /beta folder to ~ using `sshfs gymkhana@10.3.100.81:beta /home/xypnox/gymkhana/` so we don't ever disturb the original files.
sshfs https://github.com/libfuse/sshfs can be installed using standard apt install sshfs
3. Then clone the repo there. We just need to git pull in the folder to update the site after we are satisfied with the results
4. The files are also available in file browser so they can be edited by system programs (VS Code) but I would recommend editing in the repo only
5. After updating the files it is better to unmount using `fusermount -u gymkhana`
6. note that the git files will dissappear from the folder `gymkhana`. When we need to update just mount > git pull > unmount

## How to submit details of an event
1. Open https://gymkhana.iitkgp.ac.in/form.php
2. Fill the respective details.
3. Provide the security key provided
4. Click on submit.

## TODO

- [ ] Add mess menu to the backend as well as merge frontend PR.
