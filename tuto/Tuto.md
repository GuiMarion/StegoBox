# StegoBox 
Réalisé par : Guilhem Marion, Boubacar Diallo et Pierre-Louis Despaigne.
Ceci est le tuto accompagnant le projet [StegoBox](https://github.com/GuiMarion/StegoBox).

Ce projet présente comment utiliser les méthodes de programmation embarquée pour créer une clef usb bootable contenant un OS minimal ainsi qu'une application web permettant de faire de la stéganographie. En suivant se tutoriel vous pourrez mettre en place ce système qui, en démarrant, affichera une adresse ip sur laquelle se connecter pour acceder à l'application de stéganographie. 

Ce tutoriel utilise debian, quemu pour mettre en place le système, nginx pour le server, et html, css, php et bash pour l'application web. 

La démonstration s'appuie sur le réseau de la salle de réseau de l'université Claude Bernard Lyon 1, pour obtenir une démonstratin similaire sur une configuration differente, il sera peut-être necessaire d'ajuster la mise en place, mais ne vous inquietez pas, ne détaillerons toutes les étapes necessaires ! 

## Étapes

1. [Préparation de la clef](#prep)
	- [Installation de la clef](#install)
	- [Configuration de la clef](#config)
2. [Test et configuration avec qemu](#qemu)
3. [Mise en place du server](#server)
4. [Mise en place de l'application web](#app)
	- [Installation de l'application](#installapp)
	- [Configuration de php et nginx](#php)
	- [Utilisation de l'application](#utilisation)
5. [Utilisation en dehors de qemu](#stand)
	- [Affichage de l'adresse ip au démarage](#ip)
7. [Limites du projet](#limites)

## Préparation de la clef usb <a name="prep"></a>

### Installation de la clef <a name="install"></a>

Tout d'abord il faut vous installer les paquets necessaires sur votre ordinateur : 


###### Passez root:

		su

###### Effectuez les mise à jour:

		apt-get update

###### Installez debootstrap:

		apt-get install debootstrap

###### Installez qemu:

		apt-get install qemu


Vous allez maintenant formatez la clef et y installer debian, placer dans un emplacement de votre ordinateur adequat pour effectuer ces opérations.


###### Pour creer une répertoire de travail:

		mkdir work

###### Entrez dans le répertoire de travail:

		cd work

###### Inserez une cle usb et recherchez sa partition:

		fdisk -l

Elle devrait correspondre à quelque chose comme /dev/sdbX, dans notre cas la partition est /dev/sdb1, le numéro peut changer selon votre configuration, si vous n'êtes pas sûrs, debranchez la clef lancez la commande, rebranchez la clef et refaites la commande, vous verrez un nouveau périphérique, c'est la bonne clef usb !

###### Formatez la cle:

		mkfs.ext4 /dev/sdb1

###### Creez un point de montage pour la cle:

		mkdir fs

###### Montez la partition dans ce dossier :

		mount /dev/sdb1 fs

###### Lancez debootstrap et téléchargez une image debian:

		debootstrap --arch amd64 jessie fs http://ftp.fr.debian.org/debian

###### Liez le dossier /proc:

		mount -t proc none fs/proc

###### Liez le dossier /dev:

		mount -o bind /dev fs/dev

###### Passez en mode chroot:

		chroot fs

### Configuration de la clef <a name="config"></a>

Vous êtes desormais en train de configurer la clef usb, tout ce que vous ferez se fera sur la clef usb et non sur vorez OS, c'est l'utilité de chroot.

Il vous faut creer un mot de passe, choisissez, bien entendu, celui qui vous plaira, pour cela remplacez <mdp> par votre mot de passe. Pour la suite du tutoriel nous utilisera moi comme mot de passe, mais il faut vaudra utiliser le votre. 

###### Creez un mot de passe root :

		passwd <mdp>

###### Effectuez les mise-à-jour:

		apt-get update

###### Installez un noyau:

		apt-get install linux-image-amd64

###### Recuperez l'UUID de la clé USB:

		blkid (c0b524f4-5c21-423f-b124-00991a5c50a2)

###### Editez le fichier /etc/fstab:

		vim.tiny /etc/fstab

###### Ajoutez les lignes suivantes a ce fichier:

		proc /proc proc defaults
		UUID=xxxxxxxxxxxxx / ext4 errors=remount-ro 0 1

###### Editez le fichier hostname:

		echo "debian-usb" 
		/etc/hostname

Le fichier ne doit contenir qu'une seul ligne avec ecrit "debian-usb", vous pouvez le vérifier avec la commande suivante : 

		nano /etc/hostname (ctrl + x pour quitter l'éditeur)

###### Editez le fichier network/interfaces:

		vim.tiny /etc/network/interfaces

###### Commantez toutes les lignes et ajoutez les lignes suivantes:

		auto lo
		iface lo inet loopback
		allow-hotplug eth0
		auto eth0
		iface eth0 inet dhcp

###### Installez un clavier azerty:

		apt-get install console-data

###### Installez grub:

		apt-get install grub2

Vous avez désormais configuré votre clef usb avec debian. Nous allons desormais demonter la clef et nous assurer que tout fonctionne normalement. 

###### Quittez le chroot:

		exit

###### Demontez le dev:

		umount fs/dev

###### Demontez le proc:

		umount fs/proc

###### Demontez la cle:

		umount fs

## Test et configuration avec qemu <a name="qemu"></a>

###### Bootez sur la clef avec qemu:

		qemu-system-x86_64 /dev/sdb

###### Connectez vous en tant que root sur qemu:

		root
		<mdp> (n'oubliez pas de mettre ici votre propre mot de passe que vous avez choisi plus tôt)

###### Assurez vous d'être à la racine:

		cd

Nous allons maintenant configurer la connexion internet, si vous avez une configuration domestique cela devrait fonctionner sans les étapes suivantes. 

Récuperez le proxy servant à se connecter à internet dans votre configuration réseau, pour ce faire lancez cette commande sur votre ordinateur (pas dans qemu ! ): 

		ifconfig 


Pour la salle réseau de l'Université Claude Bernard Lyon 1 le proxy est : http://10.250.100.2:3128, il sera certainement different pour votre configuration.

###### Editez le fichier .bashrc (tout se passe de nouveau dans qemu) :

		vim.tiny .bashrc

###### Ajoutez ces lignes à la suite de .bashrc:

		export http_proxy="http://10.250.100.2:3128"

		export https_proxy="http://10.250.100.2:3128"

###### Quitez qemu:

		shutdown -h now


## Mise en place du server <a name="server"></a>

On va maintenant lancer qemu avec redirection de port afin d'avoir accès à internet, faire les mises à jours, et mettre en place le server.

###### Relancez qemu avec une redirection de port:

		qemu-system-x86_64 /dev/sdb -redir tcp:8080::80

###### Connectez vous en tant que root sur qemu:

		root

		<mdp> (mettre votre propre mot de passe ici)

###### Faites les mise-à-jour:

		apt-get update

Nginx est un est logiciel libre de server web, nous nous en servirons pour faire fonctionner le server sur la clef. 

###### Installez nginx:

		apt-get install nginx

Php est une technologie web permettant de faire des pages webs dynamiques, nous nous en servirons pour l'application web qu'hebergera la clef.

###### Installez php:

		apt-get install php5-fpm

Git est une technologie de versionnement, vous vous en servirez pour télécharger l'application web que nous avons faite.

###### Installez git:

		apt-get install git

Steghide est un logiciel libre dont nous nous servons pour faire la stéganographie.

###### Installez steghide:

		apt-get install steghide

###### Lancez nginx :

		systemctl start nginx

S'il est déjà lancé : 

		systemctl restart nginx


Verifiez que nginx fonctione, pour cela ouvrez un nagigateur et aller sur localhost:8080 (sur votr ordinateur bien sur, pas sur la clef, elle ne possède pas de navigateur), une page nginx devrait apparaître. Si ce n'est pas le cas, lancez cette commande, elle vous aidera à comprendre : 

		systemctl status nginx

Verifiez bien que nginx est installé et lancé.

Si vous voyez cette page, bravo, vous avez correctement configuré le server sur la clef usb ! 

## Miese en place de l'application web <a name="app"></a>

### Installation de l'application <a name="installapp"></a>

Nous allons maintenant mettre en place l'application web. Vous pouvez reconstruire cette application web en utilisant steghide, mais vous pouvez ausi récuperer l'application que nous avons fait exprès pour cette utilisation ! 

Les applications web lancées par nginx se trouvent pas défault dans le dossier, /var/www/html, nous allons donc y placer notre application.

###### Allez dans le dossier html de nginx:

		cd /var/www/html

###### Supprimez les fichiers qui s'y trouve (attention avec cette commande ! Verifiez bien que vous vous trouvez dans le bon dossier):

		rm -f *

###### Clonez le repo git du projet:

		git clone https://github.com/GuiMarion/StegoBox.git

###### Deplacez tout les fichiers dans /var/www/html:

		cd StegoBox
		mv * ..
		cd ..


### Configuration de php et nginx <a name="php"></a>

Il faut maintenant configurer php et nginx.

###### Ouvez le fichier de configuration de nginx:

		vim.tiny /etc/nginx/sites-available/default

###### Modifiez le fichier pour qu'il ressemble exactement à celui-ci (vous trouverez aussi le fichier dans le dossier /tuto/ du git): 


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

###### Ensuite relancez nginx:

		service nginx restart

Puis, sur la machine hote (votre ordinateur) raffraîchissez la page localhost:8080,
le site *Stego box* devrait s'afficher correctement

Si c'est le cas, bravo ! Vous avez bien configuré l'application.

### Utilisation de l'application <a name="utilisation"></a>

L'application permet d'ajouter des images, ajouter un message protégé par un mot de passe à l'interieur d'une image avec de la stéganographie, ainsi que récuperer un message à partir d'une image et d'un mot de passe. 

- Vous pouvez ajouter des images sur la page Upload
(seul le format jpg est accepté)

- Après avoir ajouté une image vous pouvez cacher un message dans cell-ci grâce à la page Append

- Puis vous pouvez extraire ce message avec la page Extract

- Enfin vous pouvez afficher les image sur la page View


###### Pour quitter qemu : 

		shutdown -h now

## Utilisation du server en dehors de qemu <a name="stand"></a>

Vous pouvez aussi utiliser cette clef sans passer par qemu en bottant directement sur un ordinateur, pour pouvoir utiliser l'application il faura donc y acceder depuis un ordinateur connecté au même réseau que l'ordinateur qui aura lancé la clef. 

Pour booter sur la clef, branchez la à l'ordinateur, puis allumez l'ordinateur en appuyant (quelque peu compulsivement) sur f12 (la touche peut changer selon le modèle de votre ordinateur, elle est communément affichée au démarrage)

###### Pour obtenir une addresse ipv4

		dhclient -4

Pour tester le fonctionnement du server sur le réseau, recuperez l'adresse ip de la machine qui heberge la clef usb : 

		ipconfig

Sur les ordinateurs de la salle de réseau de l'Université Claude Bernard Lyon 1 l'adresse doit être de la forme : 10.250.100.XXX, si ce n'est pas le cas, changez le cable ethernet de carte réseau (celle qui est la plus basse est la bonne).

Demarrez maintenant une autre machine connecté sur le même réseau, 

(si vous êtes dans la salle de réseau de l'UCBL refusez le login et verifiez l'adresse de l'autre machine (10.250.100.XXX):

		ifconfig
)

Ouvrez un navigateur et entrez l'adresse ip de l'ordinateur qui heberge la clef usb dans la barre du navigateur. 

Si l'application se lance, bravo, le server fonctionne sur votre réseau ! 

Pour afficher uniquement l'adresse sur laquelle se connecter, vous pouvez utiliser le script Start.sh : 

		./vat/www/html/Start.sh

### Afficher l'adresse ip au démarrage <a name="ip"></a>

Cette étape est facultative mais vous avez peut être besoin que l'ordinateur affiche tout seul l'adresse sur laquelle se connecter sans avoir à appuyer sur quelque touche qu'il soit (ni même pour se connecter), dans ce cas continuez le tutoriel, vous serez satisfait ! 

Pour cela nous avons déjà créé un script pour vous ! Il s'appelle Start.sh et est dans la racine du git, placez vous dans /var/www/html/ et testez le : 

		./Start.sh

Il devrait afficher l'adresse à laquelle se connecter pour acceder à l'application.

Nous allons maintenant configurer le système de la clef pour le lancer au démarage.

Tout d'abord changez le script pour qu'il affiche l'ip sur tty1 seulement, vous pouvez utiliser le script Start_tty1.sh du git pour aller plus vite. 

Nous allons utiliser crontab pour lancer le script au démarage et l'actualiser toutes les minutes.

###### Pour cela ouvez le fichier de config de crontab : 

		crontab -e 

###### Et ajoutez à la fin du fichier 

	@reboot path_to_script
	* * * * * path_to_script

###### Dans notre cas, 

	@reboot /var/www/html/Start_tty1.sh
	* * * * * Start_tty1.sh

Cela permet de lancer le script au démarrage ainsi que toutes les minutes (au cas où l’adresse ip change)

Il faut maintenant désactiver le service getty@tty1 afin que le système lance les services (nginx par exemple), affiche l’adresse ip ne demande pas de se connecter. 

		systemctl disable getty@tty1 

Vous pouvez tester cette nouvelle fonctionnalité en redémarant l'ordinateur : 

		reboot

N'oubliez pas d'appuyer sur f12 pour booter sur la clef usb. 

Si cela fonctionne, bravo, vous avez terminé le projet ! Si cette dernière étape ne fonctionne pas, ne vous inquietez pas vous pouvez réactiver tty1 : 

		systemctl enable getty@tty1 

et lancer le script au démarage manuellement : 

		./var/www/html/Start.sh


-----------------------------------------------------

## Limites <a name="limites"></a>

Notre projet est vulnérable au injection de commande shell, ce qui est extremement dangereux d'autant plus que s'execute en tant que root ! Pour remedier à cela nous aurions pu essayer de bloquer les injection dans le php et également avoir une gestion plus fine des permissions utilisateur, comme en executant nos script en tant que www/data par exemple.

Nous aurions aussi pu reduire l'empreinte mémoire du système pour la clef usb afin de permettre d'utiliser une clef encore plus petite, voire même permettre de déplacer le système sur une machine autonome avec une très petite mémoire.

