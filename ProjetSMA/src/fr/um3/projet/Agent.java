package fr.um3.projet;

public abstract class Agent implements Runnable {
    /*
   Création de la classe : Melissa Boccaccio
   Cette classe instancie un agent
    */

    protected String name;
    protected Position position;
    protected Velocity velocity;
    protected Acceleration acceleration;
    protected double mass ;
    protected boolean running = true;

    public Agent(String name, Position position, Velocity velocity, Acceleration acceleration, double massValue) {
        this.name = name;
        this.position = position;
        this.velocity = velocity ;
        this.acceleration = acceleration ;
        this.mass = massValue;
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

    public Position getPosition() {
        return position;
    }

    public void setPosition(Position position) {
        this.position = position;
    }

    public abstract void evolve();


    public void stop(){
        running = false;
    }

    public void run(){
        while( running){
            evolve();
            try {
                Thread.sleep(0);
            } catch (InterruptedException e) {
                throw new RuntimeException(e);
            }
        }
    }

}
