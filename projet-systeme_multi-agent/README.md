# README – Système Multi-Agent : Création des Étoiles



### Exécution du projet



* Ouvrir le projet avec un IDE Java.



* Naviguer dans le dossier src/fr/um3/projet/.



* Exécuter le fichier Panel.java pour lancer la simulation.



### Contenu et représentation



* Hydrogène : points bleus, représentent 75% des atomes dans le panel.



* Hélium : points cyan, représentent 25% des atomes dans le panel.



* Proto-étoiles : points oranges à rouges selon la température, représentent les régions où la matière commence à se condenser.



* Étoiles : points jaunes, taille proportionnelle à la masse accumulée. Lorsqu’elles atteignent une masse critique, elles “explosent” avec un flash blanc.



* Alien : point vert, se déplace via pathfinding A\* pour montrer l’interaction avec les étoiles et proto-étoiles.



### Partie code



* Création des classes pour les agents : définition de leurs attributs (position, vitesse, accélération, masse, couleur).



* Mouvement et interactions : calcul de l’accélération gravitationnelle, mise à jour des positions et vitesses, gestion des collisions.



* Affichage : utilisation de Swing pour représenter les agents et leurs interactions en temps réel.



* Threads et timers : assurent la mise à jour continue du panel et le déplacement simultané de l’Alien et des autres agents.



* Transformations : logique de création des proto-étoiles, des étoiles et suppression des étoiles trop proches ou hors du panel.



* Pathfinding A\* : calcul du chemin optimal de l’Alien tout en évitant les obstacles (étoiles et proto-étoiles).

