
# checklist
[x] - Reverse sync the database locally
[x] - Check if the version number and date reflects correctly
[x] - Grant permissions to the `database` folder
[x] - Grant permissions to the `uploads` folder
[x] - Grant permissions to the `logs` folder
[x] - Ensure that the admin backend is password protected
[x] - Check if the forms work
[x] - Check if the mailers work





# code
Following is the actual code that you can copy-pasta line by line and execute it on the server.

```

cd /var/www/html
chown -R www-data database
chown -R www-data uploads
chown -R www-data logs

```
