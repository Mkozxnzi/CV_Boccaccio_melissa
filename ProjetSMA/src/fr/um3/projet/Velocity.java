package fr.um3.projet;

public class Velocity {
    /*
   Création de la classe : Camille Seveyrat
   Cette classe sert à attribuer une vitesse à chaque agent
    */
    private double x;
    private double y;

    public Velocity(double x, double y) {
        this.x = x;
        this.y = y;
    }

    public double getX() {
        return x;
    }

    public double getY() {
        return y;
    }
}
