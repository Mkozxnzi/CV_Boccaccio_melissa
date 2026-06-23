package fr.um3.projet;

import java.util.List;
import java.util.concurrent.CopyOnWriteArrayList;

public class Mouvement {

    private List<Hydrogen> hydrogens;
    private List<Helium> heliums ;
    private List<ProtoEtoile> protoEtoiles;
    private List<Etoile> etoiles;
    private List<Agent> allAgents;

    public Mouvement(List<Hydrogen> hydrogens, List<Helium> heliums, List<ProtoEtoile> protoEtoiles, List<Etoile> etoiles, List<Agent> allAgents){
        this.hydrogens = hydrogens;
        this.heliums = heliums;
        this.protoEtoiles = protoEtoiles;
        this.etoiles = etoiles;
        this.allAgents = allAgents;


    }


    void initializeAgents() {
        /*
        Auteure : Camille Seveyrat
        Cette fonction initialise la position des agents Hydrogen et Helium de manière aléatoire
         */
        int x, y;
        Velocity velocity = new Velocity(0, 0);
        Acceleration acceleration = new Acceleration(0, 0);
        double mass = 8.5e13;
        // Initialisation des hydrogènes
        for (int i = 0; i < 400; i++) {
            x = (int) (Math.random() * 800);
            y = (int) (Math.random() * 650);
            Position position = new Position(x, y);
            Hydrogen hydrogen = new Hydrogen(position, velocity, acceleration, mass);
            hydrogens.add(hydrogen);
            allAgents.add(hydrogen);
        }

        // Initialisation des héliums
        for (int i = 0; i < 100; i++) {
            x = (int) (Math.random() * 800);
            y = (int) (Math.random() * 650);
            Position position = new Position(x, y);
            Helium helium = new Helium(position, velocity, acceleration, mass);
            heliums.add(helium);
            allAgents.add(helium);
        }

    }

    private void agentPosition(Agent agent, double deltaT) {
        /*
        Auteure : Camille Seveyrat
        Cette fonction calcule la position de l'agent grâce à la force d'attraction gravitationnelle définie dans la classe Accélération et met à jour la position de l'agent
         */
        Position position = agent.getPosition();
        Velocity velocity = agent.getVelocity();
        Acceleration acceleration = Acceleration.GravitationalAcceleration(agent, allAgents);

        // Mise à jour de la vitesse : v = v0 + a * t (équation vitesse)
        double newVx = velocity.getX() + acceleration.getX() * deltaT;
        double newVy = velocity.getY() + acceleration.getY() * deltaT;

        double maxSpeed = 3;
        // Norme du vecteur
        double speed = Math.sqrt(newVx * newVx + newVy * newVy);
        if (speed > maxSpeed) {
            double coeff = maxSpeed / speed;
            newVx *= coeff;
            newVy *= coeff;
        }
        velocity = new Velocity(newVx, newVy);
        agent.setVelocity(velocity);

        // Mise à jour de la position : x = x0 + v0 * t + 1/2 * a * t^2 (équation position)
        double newX = position.getX() + velocity.getX() * deltaT + 0.5 * acceleration.getX() * Math.pow(deltaT, 2);
        double newY = position.getY() + velocity.getY() * deltaT + 0.5 * acceleration.getY() * Math.pow(deltaT, 2);
        position = new Position(newX, newY);
        agent.setPosition(position);
    }

    void updateAgentsPositions(double deltaT) {
        /*
        Auteure : Camille Seveyrat
        Cette fonction met à jour les positions des agents grâce à la fonction AgentPosition()
        */
        for (Hydrogen hydrogen : hydrogens) {
            agentPosition(hydrogen, deltaT);
        }
        for (Helium helium : heliums) {
            agentPosition(helium, deltaT);
        }
        for (ProtoEtoile protoEtoile : protoEtoiles) {
            agentPosition(protoEtoile, deltaT);
        }
        for (Etoile etoile : etoiles) {
            agentPosition(etoile, deltaT);
        }
    }

}

