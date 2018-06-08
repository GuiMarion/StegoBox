qemu-system-x86_64 /dev/sdb -redir tcp:8080::80

/var/www -> dossier site nginx

service nginx status/start

ps aux | grep nginx -> savoir si nginx et lance

adresse : localhost:8080
