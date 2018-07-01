# StegoBox 

Ce projet présente comment utiliser les méthodes de programmations embarquée pour créer une clef usb bootable contenant un OS minimal ainsi qu'une application web permettant de faire de la stéganographie. En suivant se tutoriel vous pourrez mettre en place ce système qui, en démarrant, affichera une adresse ip sur laquelle se connecter pour acceder à l'application de stéganographie. 

Ce tutoriel utilise debian, quemu pour mettre en place le système, nginx pour le server, et html, css, php et bash pour l'application web. 

La démonstration s'appuie sur le réseau de la salle de réseau de l'université Claude Bernard Lyon 1, pour obtenir une démonstratin similaire sur une configuration differente, il sera peut-être necessaire d'ajuster la mise en place, mais ne vous inquietez pas, ne détaillerons toutes les étapes necessaires ! 

## Preparation de la clef usb

Tout d'abord il faut vous installer les paquets necessaires sur votre ordinateur : 


Passez root:

		su

Effectuez les mise à jour:

		apt-get update

Installez debootstrap:

		apt-get install debootstrap

Installez qemu:

		apt-get install qemu


Vous allez maintenant formatez la clef et y installer debian, placer dans un emplacement de votre ordinateur adequat pour effectuer ces opérations.


Pour creer une répertoire de travail:

		mkdir work

Entrez dans le répertoire de travail:

		cd work

Inserez une cle usb et recherchez sa partition:

		fdisk -l

Elle devrait correspondre à quelque chose comme /dev/sdbX, dans notre cas la partition est /dev/sdb1, le numéro peut changer selon votre configuration, si vous n'êtes pas sûrs, debranchez la clef lancez la commande, rebranchez la clef et refaites la commande, vous verrez un nouveau périphérique, c'est la bonne clef usb !

Formatez la cle:

		mkfs.ext4 /dev/sdb1

Creez un point de montage pour la cle:

		mkdir fs

Montez la partition dans ce dossier :

		mount /dev/sdb1 fs

Lancez debootstrap et téléchargez une image debian:

		debootstrap --arch amd64 jessie fs http://ftp.fr.debian.org/debian

Liez le dossier /proc:

		mount -t proc none fs/proc

Liez le dossier /dev:

		mount -o bind /dev fs/dev

Passez en mode chroot:

		chroot fs

Vous êtes desormais en train de configurer la clef usb, tout ce que vous ferez se fera sur la clef usb et non sur vorez OS, c'est l'utilité de chroot.

Il vous faut creer un mot de passe, choisissez, bien entendu, celui qui vous plaira, pour cela remplacez <mdp> par votre mot de passe. Pour la suite du tutoriel nous utilisera moi comme mot de passe, mais il faut vaudra utiliser le votre. 

Creez un mot de passe root :

		passwd <mdp>

Effectuez les mise-à-jour:

		apt-get update

Installez un noyau:

		apt-get install linux-image-amd64

Recuperez l'UUID de la clé USB:

		blkid (c0b524f4-5c21-423f-b124-00991a5c50a2)

Editez le fichier /etc/fstab:

		vim.tiny /etc/fstab

Ajoutez les lignes suivantes a ce fichier:

		proc /proc proc defaults
		UUID=xxxxxxxxxxxxx / ext4 errors=remount-ro 0 1

Editez le fichier hostname:

		echo "debian-usb" 
		/etc/hostname

Le fichier ne doit contenir qu'une seul ligne avec ecrit "debian-usb", vous pouvez le vérifier avec la commande suivante : 

		nano /etc/hostname (ctrl + x pour quitter l'éditeur)

Editez le fichier network/interfaces:

		vim.tiny /etc/network/interfaces

Commantez toutes les lignes et ajoutez les lignes suivantes:

		auto lo
		iface lo inet loopback
		allow-hotplug eth0
		auto eth0
		iface eth0 inet dhcp

Installez un clavier azerty:

		apt-get install console-data

Installez grub:

		apt-get install grub2

Vous avez désormais configuré votre clef usb avec debian. Nous allons desormais demonter la clef et nous assurer que tout fonctionne normalement. 

Quittez le chroot:

		exit

Demontez le dev:

		umount fs/dev

Demontez le proc:

		umount fs/proc

Demontez la cle:

		umount fs

## Test et configuration avec qemu

Bootez sur la clef avec qemu:

		qemu-system-x86_64 /dev/sdb

Connectez vous en tant que root sur qemu:

		root
		<mdp> (n'oubliez pas de mettre ici votre propre mot de passe que vous avez choisi plus tôt)

Assurez vous d'être à la racine:

		cd

Nous allons maintenant configurer la connexion internet, si vous avez une configuration domestique cela devrait fonctionner sans les étapes suivantes. 

Récuperez le proxy servant à se connecter à internet dans votre configuration réseau, pour ce faire lancez cette commande sur votre ordinateur (pas dans qemu ! ): 

		ifconfig 


Pour la salle réseau de l'Université Claude Bernard Lyon 1 le proxy est : http://10.250.100.2:3128, il sera certainement different pour votre configuration.

Editez le fichier .bashrc (tout se passe de nouveau dans qemu) :

		vim.tiny .bashrc

Ajoutez ces lignes à la suite de .bashrc:

		export http_proxy="http://10.250.100.2:3128"

		export https_proxy="http://10.250.100.2:3128"

Quitez qemu:

		shutdown -h now


## Mise en place du server

On va maintenant lancer qemu avec redirection de port afin d'avoir accès à internet, faire les mises à jours, et mettre en place le server.

Relancez qemu avec une redirection de port:

		qemu-system-x86_64 /dev/sdb -redir tcp:8080::80

Connectez vous en tant que root sur qemu:

		root

		<mdp> (mettre votre propre mot de passe ici)

Faîtes les mise-à-jour:

		apt-get update

Nginx est un est logiciel libre de server web, nous nous en servirons pour faire fonctionner le server sur la clef. 

Installez nginx:

		apt-get install nginx

Php est une technologie web permettant de faire des pages webs dynamiques, nous nous en servirons pour l'application web qu'hebergera la clef.

Installez php:

		apt-get install php5-fpm

Git est une technologie de versionnement, vous vous en servirez pour télécharger l'application web que nous avons faite.

Installez git:

		apt-get install git

Stehide est un logiciel libre dont nous nous servons pour faire la stéganographie.

Installez steghide:

		apt-get install steghide

Lancez nginx :

		systemctl start nginx

S'il est déjà lancé : 

		systemctl restart nginx


Verifiez que nginx fonctione, pour cela ouvrez un nagigateur et aller sur localhost:8080 (sur votr ordinateur bien sur, pas sur la clef, elle ne possède pas de navigateur), une page nginx devrait apparaître. Si ce n'est pas le cas, lancez cette commande, elle vous aidera à comprendre : 

		systemctl status nginx

Verifiez bien que nginx est installé et lancé.

Si vous voyez cette page, bravo, vous avez correctement configuré le server sur la clef usb ! 

## Miese en place de l'application web

Nous allons maintenant mettre en place l'application web. Vous pouvez reconstruire cette application web en utilisant steghide, mais vous pouvez ausi récuperer l'application que nous avons fait exprès pour cette utilisation ! 

Les applications web lancées par nginx se trouvent pas défault dans le dossier, /var/www/html, nous allons donc y placer notre application.

Allez dans le dossier html de nginx:

		cd /var/www/html

Supprimez les fichiers qui s'y trouve (attention avec cette commande ! Verifiez bien que vous vous trouvez dans le bon dossier):

		rm -f *

clonez le repo git du projet:

		git clone https://github.com/GuiMarion/StegoBox.git

Deplacez tout les fichiers dans /var/www/html:

		cd StegoBox

		mv * ..

		cd ..


### Configuration de php et nginx

Il faut maintenant configurer php et nginx.

Ouvez le fichier de configuration de nginx:

		vim.tiny /etc/nginx/sites-available/default

Modifiez le fichier pour qu'il ressemble exactement à celui-ci : 

	///////////////////////
	##
	# You should look at the following URL's in order to grasp a solid understanding
	# of Nginx configuration files in order to fully unleash the power of Nginx.
	# http://wiki.nginx.org/Pitfalls
	# http://wiki.nginx.org/QuickStart
	# http://wiki.nginx.org/Configuration
	#
	# Generally, you will want to move this file somewhere, and start with a clean
	# file but keep this around for reference. Or just disable in sites-enabled.
	#
	# Please see /usr/share/doc/nginx-doc/examples/ for more detailed examples.
	##
	# Default server configuration
	#
	server {
	listen 80 default_server;
	listen [::]:80 default_server;
		# SSL configuration
		#
		# listen 443 ssl default_server;
		# listen [::]:443 ssl default_server;
		#
		# Self signed certs generated by the ssl-cert package
		# Don't use them in a production server!
		#
		# include snippets/snakeoil.conf;

		root /var/www/html;

		# Add index.php to the list if you are using PHP
		index index.php index.html index.htm index.nginx-debian.html;

		server_name _;

		location / {
			# First attempt to serve request as file, then
			# as directory, then fall back to displaying a 404.
			try_files $uri $uri/ =404;
		}

		# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
		#
	        location ~ \.php$ {
			include snippets/fastcgi-php.conf;
		#
		#	# With php5-cgi alone:
		#	fastcgi_pass 127.0.0.1:9000;
		#	# With php5-fpm:
			fastcgi_pass unix:/var/run/php5-fpm.sock;
		}

		# deny access to .htaccess files, if Apache's document root
		# concurs with nginx's one
		#
		location ~ /\.ht {
			deny all;
		}
	}


	# Virtual Host configuration for example.com
	#
	# You can move that to a different file under sites-available/ and symlink that
	# to sites-enabled/ to enable it.
	#
	#server {
	#	listen 80;
	#	listen [::]:80;
	#
	#	server_name example.com;
	#
	#	root /var/www/example.com;
	#	index index.html;
	#
	#	location / {
	#		try_files $uri $uri/ =404;
	#	}
	#}
	///////////////////////

Ensuite relancez nginx:

		service nginx restart

puis sur la machine hote raffrechissez la page localhost:8080,
le site Stego box devrait s'afficher correctement

vous pouvez maintenant ajouter des images sur la page Upload
(seul le format jpg est accepté)

après avoir ajouter une image vous pouvez cacher un message dans cell-ci grace à la page Append

puis vous pouvez extraire ce message avec la page Extract

enfin vous pouvez afficher les image sur la page View

quand vous avez finis de tester eteindre qemu:

		shutdown -h now

---------------------------------------------------

Vous pouvez maintenant utiliser votre clé sans qemu:

bootez sur la clez (f12)

obtenir une addresse ipv4

		dhclient -4

veriffier l'adresse (10.250.100.XXX)(A)

		ifconfig

demarrez une autre machine
refuser le login
veriffier l'adresse de l'autre machine (10.250.100.XXX)

		ifconfig
ouvrir un navigateur
entrez l'adresse ip (A) dans la barre du navigateur

Pour Afficher l’ip au démarrage : 

Rediriger l’affichage du script (script sur le git) sur /dev/tty1

Echo ip >
		/dev/tty1

Faire en sorte que le script se lance au démarage et s’actualise : 

		crontab -e 

Et ajouter à la fin du fichier 

@reboot path_to_script
* * * * * path_to_script

Cela permet de lancer le script au démarrage ainsi que toutes les minutes (au cas où l’adresse ip change)

Il faut maintenant désactiver le service getty@tty1 afin que le système lance les services (nginx par exemple), affiche l’adresse ip ne demande pas de se connecter. 



-----------------------------------------------------

Limite : 

Nous avons creer un script pour afficher l'address IP de la machine, ce script s'appelle Start.sh et il est situé dans le repo git, et donc dans /var/www/html.
Cependant il  n'execute pas au demarage, nous pourrions donc l'executer automatiquement grace à autologin de cette façon nous seron également logé automatiquement.

De plus notre projet est vulnérable au injection de commande shell, ce qui est extremement dangeureux d'autant plus que s'execute en tant que root ! Pour remedier à cela nous aurions put essayer de bloquer les injection dans le php et également a voir une gestion plus fine des permission utilisateurs, comme en executant nos script en tant que www/data par exemple.

