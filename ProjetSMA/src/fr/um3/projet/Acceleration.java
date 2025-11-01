package fr.um3.projet;

import java.util.List;


public class Acceleration {
    /*
   Création de la classe : Camille Seveyrat
   Cette classe instancie et calcule l'accélération entre un agent et une liste d'autres agents avec la fonction GravitationalAcceleration
    */
    private double x;
    private double y;

    public Acceleration(double x, double y) {
        this.x = x;
        this.y = y;
    }

    public double getX() {
        return x;
    }

    public double getY() {
        return y;
    }

    public static Acceleration GravitationalAcceleration(Agent targetAgent, List<Agent> agents) {
        final double G = 6.674e-11; // Constante gravitationnelle
        double totalAx = 0.0; // Accélération totale sur l'axe X
        double totalAy = 0.0; // Accélération totale sur l'axe Y

        // Parcours de toute la liste d'agents
        for (Agent otherAgent : agents) {
            if (otherAgent != targetAgent) {
                // Distance entre deux agents
                double deltaX = targetAgent.getPosition().getX() - otherAgent.getPosition().getX();
                double deltaY = targetAgent.getPosition().getY() - otherAgent.getPosition().getY();
                double r = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

                double minDistance = 2.0;
                if (r < minDistance) {
                    r = minDistance; // Éviter que la distance ne devienne trop petite (sinon l'accélération gravitationnelle tend vers l'infini)
                }

                double accelerationMagnitude = (G * otherAgent.getMass()) / (r * r); // Utiliser la masse de l'autre agent

                // Normalisation du vecteur de direction (Internet)
                double directionX = deltaX / r; // Direction vers l'autre agent
                double directionY = deltaY / r;

                totalAx += -directionX * accelerationMagnitude; // Composante de l'accélération sur l'axe x
                totalAy += -directionY * accelerationMagnitude; // Composante de l'accélération sur l'axe y

            }
        }
        return new Acceleration(totalAx, totalAy);
    }

}
