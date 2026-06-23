package fr.um3.projet;

public class Etoile extends Agent {
    /*
   Création de la classe : Melissa Boccaccio
   Ajout de la vitesse, accélération, masse et flash : Camille Seveyrat
   Cette classe instancie l'agent Etoile
    */
    private boolean flash;
    public Etoile(Position position, Velocity velocity, Acceleration acceleration, double mass)
    {
        super("Etoile",position, velocity, acceleration, mass);
        this.flash = false;
    }

    public boolean isFlashing() {
        return flash;
    }

    public void setFlashing(boolean flash) {
        this.flash = flash;
    }

    public Velocity getVelocity() {
        return velocity;
    }

    public void setVelocity(Velocity velocity) {
        this.velocity = velocity;
    }

    public double getMass(){
        return mass;
    }

    public Acceleration getAcceleration() { return acceleration ; }

    public void setMass(double mass){
        this.mass = mass ;
    }

    @Override
    public void evolve() {
        System.out.println("d");
    }
}
