# Attestations
Application web qui génère des attestations des étudiants

Installation de composer:
https://getcomposer.org/download/

Allez dans le dossier "Attestations/attestation" puis exécutez composer upgrade ou php composer.phar upgrade (si composer.phar se trouve dans ce dossier)

Ouvrir le fichier .env afin de choisir votre base de donnée. Décommentez la base de donnée que vous utilisez et mettez en commentaire des autres.

Créer une base de donnée de nom "attestation"

Exécutez bin/console make:migration

Exécutez bin/console doctrine:migrations:migrate

Exécutez bin/console doctrine:fixtures:load (pour préremplir les tables Etudiant et Convention)

Allez dans le dossier public

exécutez php -S addr:port (exemple 127.0.0.1:8080)

Ouvrez le navigateur et entrez addr:port/form. (exemple 127.0.0.1:8080/form)

Cliquez sur "Accéder au formulaire"

Choisir le nom d'un étudiant à partir de la liste déroulante

Cliquez sur Update

Modifiez le message comme bon vous semble et cliquez sur Update, l'attestation sera enregistrée dans la base de donnée.






