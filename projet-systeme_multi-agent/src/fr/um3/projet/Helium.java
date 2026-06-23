package fr.um3.projet;

public class Helium extends Agent {
    /*
   Création de la classe : Melissa Boccaccio
   Ajout de la vitesse, accélération et masse : Camille Seveyrat
   Cette classe instancie l'agent Helium
    */

    public Helium(Position position, Velocity velocity, Acceleration acceleration, double mass){
        super("Helium",position, velocity, acceleration, mass);
    }

    public Velocity getVelocity() {
        return velocity;
    }

    public void setVelocity(Velocity velocity) {
        this.velocity = velocity;
    }

    public Acceleration getAcceleration() {
        return acceleration;
    }

    public void setAcceleration(Acceleration acceleration) {
        this.acceleration = acceleration;
    }

    public double getMass(){
        return mass ;
    }
    public void setMass(double mass){
        this.mass = mass ;
    }

    @Override
    public void evolve() {
        System.out.println("B");
    }
}
