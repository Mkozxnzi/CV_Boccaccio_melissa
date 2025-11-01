package fr.um3.projet;

import java.awt.Color;

public class ProtoEtoile extends Agent {
    /*
   Création de la classe : Melissa Boccaccio
   Ajout de la vitesse, accélération et masse : Camille Seveyrat
   Cette classe instancie l'agent ProtoEtoile
    */

    private double temperature;
    private Color color;

    public ProtoEtoile(Position position, Velocity velocity, Acceleration acceleration, double mass)
    {
        super("ProtoEtoile",position, velocity, acceleration, mass);
        this.temperature = 0; // Température initiale
        updateColor();
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
        return mass;
    }


    public void setTemperature(double newTemperature, Panel panel) {
        this.temperature = newTemperature;
        updateColor(); // Met à jour la couleur en fonction de la température
        setColor(updateColor());
        panel.repaint();
    }

    public void setColor(Color color){
        this.color = color ;
    }

    public double getTemperature(){
        return temperature;
    }

    public Color getColor() {
        return this.color;
    }

    private Color updateColor() {
        /* Auteure  : Camille
        Température minimale et maximale pour les couleurs
         */
        double minTemp = 6e6; // Exemples en Kelvin
        double maxTemp = 4.9e7;

        // Calculer un ratio entre 0 (orange) et 1 (rouge) (Internet)
        double ratio = Math.min(1.0, Math.max(0.0, (temperature - minTemp) / (maxTemp - minTemp)));

        // Définir les couleurs : orange -> rouge (Internet)
        int red = (int) (255 - 100 * ratio); // Toujours maximal, car les deux couleurs sont dominées par le rouge
        int green = (int) (230 - 230 * ratio);
        int blue = (int) (100 - 100 * ratio);

        // Met à jour la couleur
        this.color = new Color(red, green, blue);
        return this.color ;
    }


    @Override
    public void evolve() {
        System.out.println("C");
    }
}