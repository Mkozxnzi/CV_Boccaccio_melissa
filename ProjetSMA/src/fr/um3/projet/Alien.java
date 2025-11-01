package fr.um3.projet;

public class Alien {
    /*
   Auteure : Melissa Boccaccio
   Cette classe instancie l'agent Alien servant au pathfinding
    */

    private Position position;

    public Alien(Position position){
        super();
        this.position= position;}

    public Position getPosition(){return position;}

    public void setPosition(Position position) {
        this.position = position ;
    }
}