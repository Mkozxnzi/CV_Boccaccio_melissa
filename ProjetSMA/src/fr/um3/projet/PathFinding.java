package fr.um3.projet;

import java.util.*;

public class PathFinding implements Runnable {
    /*
    Creation de la classe : Melissa Boccaccio
    Cette classe sert au pathfinding de notre alien dans le panel
     */
    private Position current;
    private Position target;
    private List<Etoile> etoiles;
    private List<ProtoEtoile> protoEtoiles;
    private volatile boolean running = true;

    public PathFinding(Position current, Position target, List<Etoile> etoiles, List<ProtoEtoile> protoEtoiles, Panel panel) {
        this.current = current;
        this.target = target;
        this.etoiles = etoiles;
        this.protoEtoiles = protoEtoiles;
    }

    @Override
    public void run() {
        /* Melissa Boccaccio  (internet un peu)
        Cette fonction sert à implementer l'interface Runnable
        Elle sert à déplacer la postion current vers target
         */
        while (running && !current.equals(target)) {
            Position nextStep = getNextStep(current, target);
            synchronized (this) {
                current = nextStep;
            }

            try {
                Thread.sleep(100); // Temporisation pour éviter une mise à jour trop rapide
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
            }
        }
    }

    public Position getNextStep(Position current, Position target) {
        //**retourne une position (retournait un chemin)
        /*
        Melissa Boccaccio (**)(internet)
        Cette fonction retourne la position suivante de Alien
         */
        //** vérifier si une collision potentielle existe
        for (Etoile etoile : etoiles) {
            // Si une étoile est trop proche alors l'alien part dans la direction opposée
            if (isInSecurityRadius(current, etoile.getPosition())) {
                return moveAwayFrom(current, etoile.getPosition());
            }
        }
        for (ProtoEtoile protoEtoile : protoEtoiles) {
            // Si une proto-étoile est trop proche alors l'alien part dans la direction opposée
            if (isInSecurityRadius(current, protoEtoile.getPosition())) {
                return moveAwayFrom(current, protoEtoile.getPosition());
            }
        }

        PriorityQueue<Node> openList = new PriorityQueue<>(Comparator.comparingDouble(Node::getF));
        HashSet<Position> closedList = new HashSet<>();
        Node currentNode = new Node(current, null, 0, getHeuristic(current, target));
        openList.add(currentNode);

        while (!openList.isEmpty()) {
            Node node_current = openList.poll();
            closedList.add(node_current.getPosition());

            if (node_current.getPosition().equals(target)) {
                return target;
            }

            for (int[] direction : new int[][]{{2, 0}, {-2, 0}, {0, 2}, {0, -2}, {2, 2}, {2, -2}, {-2, 2}, {-2, -2}}) { //**prise en compte des diagonales
                Position neighborPos = new Position(node_current.getPosition().getX() + direction[0], node_current.getPosition().getY() + direction[1]);
                if (!closedList.contains(neighborPos) && !willCollide(neighborPos.getX(), neighborPos.getY())) {
                    double g = node_current.getG() + 1;
                    double h = getHeuristic(neighborPos, target);
                    Node neighbor = new Node(neighborPos, node_current, g, h);

                    if (openList.stream().noneMatch(n -> n.getPosition().equals(neighborPos) && n.getG() <= g)) {
                        openList.add(neighbor);
                    }
                }
            }

            if (!openList.isEmpty()) {
                return openList.peek().getPosition();
            }
        }
        return current;
    }

    private boolean isInSecurityRadius(Position current, Position obstacle) {
        /*
        Auteure : melissa Boccaccio
        Cette fonction détecte si une position est dans le rayon de sécurité
         */
        int rayonSecurite = 45;
        double distance = Math.sqrt(Math.pow(obstacle.getX() - current.getX(), 2) + Math.pow(obstacle.getY() - current.getY(), 2));
        return distance < rayonSecurite;
    }

    // Méthode pour déplacer l'alien à l'opposé de l'obstacle
    private Position moveAwayFrom(Position current, Position obstacle) {
        /*
        Auteure : Mélissa Boccaccio
        Cette fonction sert à déplacer l'alien dans la position opposée à l'obstacle
         */
        // Calcul de la distance
        double deltaX = current.getX() - obstacle.getX();
        double deltaY = current.getY() - obstacle.getY();
        double distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

        // Normaliser le vecteur (Internet)
        if (distance != 0) {
            deltaX /= distance;
            deltaY /= distance;
        }

        // Déplacement à l'opposé de l'obstacle avec marge de distance
        double newX = current.getX() + deltaX * 5;
        double newY = current.getY() + deltaY * 5;

        return new Position((int) newX, (int) newY);
    }


    private double getHeuristic(Position a, Position b) {
                /*
        Auteure : Melissa Boccaccio (internet)
        Cette fonction aide l'algorithme à choisir les noeuds à explorer pour atteindre la cible plus efficacement.
         */

        return Math.abs(a.getX() - b.getX()) + Math.abs(a.getY() - b.getY());
    }

    public boolean willCollide(double x, double y) {
        /*
       Auteure : Melissa Boccaccio
       Cette fonction détecte une collision potentielle avec les Etoile et ProtoEtoile
        */

        int rayonSecurite = 45;

        for (Etoile etoile : etoiles) {
            double etoileX = etoile.getPosition().getX();
            double etoileY = etoile.getPosition().getY();
            // Calcul la distance euclidienne
            double distance = Math.sqrt(Math.pow(etoileX - x, 2) + Math.pow(etoileY - y, 2));
            if (distance < rayonSecurite) {
                return true;// Collision si distance < rayon de sécurité
            }
        }

        for (ProtoEtoile protoEtoile : protoEtoiles) {
            double protoEtoileX = protoEtoile.getPosition().getX();
            double protoEtoileY = protoEtoile.getPosition().getY();
            // Calcul la distance euclidienne
            double distance = Math.sqrt(Math.pow(protoEtoileX - x, 2) + Math.pow(protoEtoileY - y, 2));
            if (distance < rayonSecurite) {
                return true;// Collision si distance < rayon de sécurité
            }
        }

        return false;// Pas de collision détectée
    }
}
