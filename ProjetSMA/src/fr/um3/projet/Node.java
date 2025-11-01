package fr.um3.projet;

class Node {
    /*
    Création de la classe : Melissa Boccaccio ( internet un peu )
    Cette classe aide à évaluer et suivre le parcours optimal pour le pathfinding en minimisant le coût pour atteindre la cible
     */

    private final Position position;
    private final Node parent;
    private final double g, h;

    public Node(Position position, Node parent, double g, double h) {
        this.position = position;
        this.parent = parent;
        this.g = g;
        this.h = h;
    }

    public Position getPosition() { return position; }
    public Node getParent() { return parent; }
    public double getG() { return g; }
    public double getH() { return h; }
    public double getF() { return g + h; }
}