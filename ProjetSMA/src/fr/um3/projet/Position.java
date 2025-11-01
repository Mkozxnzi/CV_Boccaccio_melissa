package fr.um3.projet;

import java.util.Objects;

public class Position {
    /*
   Création de la classe : Melissa Boccaccio
   Cette classe sert à donner une position à chaque agent
    */

    private double x;
    private double y;

    public Position( double x, double y){
        this.x = x;
        this.y = y;
    }

    public double distanceTo(Position other) {
        /*
        Auteure : Melissa Boccaccio
        Cette fonction calcule la distance avec un autre agent
         */
        double dx = this.x - other.x;
        double dy = this.y - other.y;
        return Math.sqrt(dx * dx + dy * dy);
    }

    public double getX() {
        return x;
    }

    public double getY() {
        return y;
    }


    public void setX(double x) {
        this.x = x;
    }

    public void setY(double y) {
        this.y = y;
    }

    @Override
    public boolean equals(Object obj) {
        if (this == obj) return true;
        if (obj == null || getClass() != obj.getClass()) return false;
        Position position = (Position) obj;
        return x == position.x && y == position.y;
    }

    @Override // internet
    public int hashCode() {
        return Objects.hash(x, y);
    }

    @Override
    public String toString() {
        return "(" + x + ", " + y + ")";
    }
}
