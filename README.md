# ft4a
Php/MySQL frontend to XBT Tracker
ft4a.fr est un projet visant :
- à créer et maintenir un front-end simple et pratique au tracker bittorrent XBTT (php + mysql)
- à créer et animer une communauté de partageurs et d'utilisateurs de médias sous licence libre ou licence de libre diffusion
Il s’inspire du projet freetorrent.fr, abandonné en juillet 2019.

ft4a signifie : Free Torrents For All

### Architecture du site
Le site tourne actuellement sur un server Ubuntu 18.04.
Le site possède l'architecture suivante :
- /web : fichiers php et html
- /private : crontab.php (délestage de la table xbt_announce_log
- /logs : access.log et error.log

### Rewrite Nginx
Des règles de rewrite sont indispensables pour Nginx pour les archives, les licences, les catégories et la page unique pour chaque torrent (viewpost.php) :
```
rewrite ^/c-(.*)$ /catpost.php?id=$1 last;
rewrite ^/l-(.*)$ /licpost.php?id=$1 last;
rewrite ^/a-(.*)-(.*)$ /archives.php?month=$1&year=$2 last;

if (!-d $request_filename){
    set $rule_2 1$rule_2;
}
if (!-f $request_filename){
    set $rule_2 2$rule_2;
}
if ($rule_2 = "21"){
    rewrite ^/(.*)$ /viewpost.php?id=$1 last;
}
```

### PHPMailer
L'envoi de mails se fait grâce à PHPMailer (https://github.com/PHPMailer/PHPMailer) installé avec Composer.
Les fichiers concernés sont :
- signup.php
- contact.php
- recup_pass.php

### ReCaptcha
Recaptcha de Google protège chaque formulaire d'envoi de mail. Vous devez donc créer un compte sur https://www.google.com/recaptcha et rentrer votre clé de site ET votre clé privée dans les 3 fichiers qui envoient des mails, cités plus haut.

### SQL
La base MySQL comprend le stables pour le site ET pour XBT tracker.

### XBT Tracker
XBT (Olaf Van der Spek) est disponible ici : https://github.com/OlafvdSpek/xbt
Vous pouvez également trouver le code sur mon Github : https://github.com/citizenz7/xbt.
L'installation est détaillée dans le Readme.

XBT est le tracker bittorrent. C'est lui qui "gère" toutes les connexions.
Vous pouvez vérifier les stats du tracker en vous rendant sur http://VOTRE_SITE.com:xbt_port/stats (exemple : http://ft4a.xyz:55555/stats).
Le debug est ici : http://VOTRE_SITE.com:xbt_port/debug

XBT doit donc être "lancé" avec systemd ou directement dans un "screen" pour que le système de torrents "fonctionne".
Exemple avec systemd (USER est à remplacer. Le chemin pour xbt_tracker est à adapter si besoin...) :
```[Unit]
Description=XBT Tracker
After=network.target mysql.service
#Wants=mysql.service

[Service]
User=USER
Type=forking
KillMode=none
ExecStart=/home/USER/xbt/Tracker/xbt_tracker --conf_file /home/USER/xbt/Tracker/xbt_tracker.conf
ExecStop=/usr/bin/killall -w -s 2 /home/mumbly/xbt/Tracker/xbt_tracker
WorkingDirectory=/home/USER/xbt/Tracker

[Install]
WantedBy=default.target
```

### Partie Administration
Le 1er membre inscrit (ID #1) est l'admin du site qui à accès à tous les outils d'administration, c'est à dire :
- la partie admin du site : http://VOTRE_SITE.com/admin
- l'édition, suppression de membres et de torrents directement sur les pages du site 

### Membres
Les membres doivent être inscrits pour télécharger et uploader des torrents.
Ils ont accès à un espace personnel d'administration de leur profil (changement du mot de passe, e-mail, ajout/suppression d'un avatar personnalisé, ...)

### Messagerie interne
Une messagerie interne permet aux membres de communiquer entre eux.
