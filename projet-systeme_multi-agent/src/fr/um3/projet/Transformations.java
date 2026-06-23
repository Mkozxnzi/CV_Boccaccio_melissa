package fr.um3.projet;

import java.awt.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;


public class Transformations {
    /*
    Création de la classe : Camille Seveyrat
    Cette classe contient les calculs et les fonctions nécessaires aux transformations de nos agents
     */

    private Panel panel;

    public Transformations(Panel panel) {
        this.panel = panel;
    }

    public void updateEvolution(List<Hydrogen> hydrogens, List<Helium> heliums, List<ProtoEtoile> protoEtoiles, List<Etoile> etoiles) {
        /*
        Auteure : Camille Seveyrat et ajout de Melissa Boccaccio
        Cette fonction rassemble les agents dans des régions et met à jour leur évolution
         */
        int regionSize = 40;
        double protoEtoileMasseCritique = 4.5e15;
        double etoileMasseCritique = 4e15;
        double temperature = 0;
        double temperatureFusion = 5e7;
        final double G = 6.674e-11;

        Map<Point, List<Agent>> regions = new HashMap<>(); // Collection des régions contenant les points (Internet)

        // Attribution d'une région pour chaque agent (Internet)
        for (Agent agent : hydrogens) {
            Point region = new Point((int) (agent.getPosition().getX() / regionSize), (int) (agent.getPosition().getY() / regionSize));
            regions.computeIfAbsent(region, k -> new ArrayList<>()).add(agent);
        }

        for (Agent agent : heliums) {
            Point region = new Point((int) (agent.getPosition().getX() / regionSize), (int) (agent.getPosition().getY() / regionSize));
            regions.computeIfAbsent(region, k -> new ArrayList<>()).add(agent);
        }

        for (ProtoEtoile protoEtoile : protoEtoiles) {
            Point region = new Point((int) (protoEtoile.getPosition().getX() / regionSize), (int) (protoEtoile.getPosition().getY() / regionSize));
            regions.computeIfAbsent(region, k -> new ArrayList<>()).add(protoEtoile);
        }

        for (Etoile etoile : etoiles) {
            Point region = new Point((int) (etoile.getPosition().getX() / regionSize), (int) (etoile.getPosition().getY() / regionSize));
            regions.computeIfAbsent(region, k -> new ArrayList<>()).add(etoile);
        }


        for (Map.Entry<Point, List<Agent>> entry : regions.entrySet()) { // Parcours de toutes les régions (Internet)
            List<Agent> atomsInRegion = entry.getValue();
            double totalMass = 0;
            boolean hasProtoEtoile = false; // Vérification présence proto-étoile
            boolean hasEtoile = false; // Vérification présence étoile
            double massInitial = 2e14;
            double massMax = 3e15;

            // Calcul masse totale dans une région avec l'aide d'Internet
            for (Agent atom : atomsInRegion) {
                if (atom instanceof Hydrogen) {
                    totalMass += ((Hydrogen) atom).getMass();
                }
                else if (atom instanceof Helium) {
                    totalMass += ((Helium) atom).getMass();
                }
                else if (atom instanceof ProtoEtoile) {
                    hasProtoEtoile = true;
                    totalMass += ((ProtoEtoile) atom).getMass();

                    temperature = 2.33*1.67e-27 * G * totalMass / 1.380649e-23 * 1e6 ; // Formule de la température d'un système astrophysique
                    ((ProtoEtoile) atom).setTemperature(temperature, panel);
                } else if (atom instanceof Etoile){
                    hasEtoile = true ;
                    totalMass += ((Etoile) atom).getMass();
                    // Pour maintenir une masse moyenne (interpolation linéaire : calcul valeur intermédiaire proportionnelle entre deux bornes)
                    ((Etoile) atom).setMass(massInitial + (totalMass / etoileMasseCritique) * (massMax - massInitial)); // Lorsque totalMass=etoileMasseCritique la masse de l'étoile = massMax

                    if (((Etoile) atom).getMass() >= massMax){
                        TriggerFlash.triggerFlash(((Etoile) atom), etoiles, panel);
                        panel.removeAgent(atom);
                    }
                }


            }

            removeCloseStars(etoiles);

            // Création proto-étoile
            if (!hasProtoEtoile && !hasEtoile && totalMass >= protoEtoileMasseCritique) {
                createProtoEtoile(atomsInRegion, hydrogens, heliums, protoEtoiles);
            }
            // Création étoile si une proto-étoile se trouve dans la région
            else if (hasProtoEtoile && !hasEtoile && temperature >= temperatureFusion) {
                createEtoile(atomsInRegion, hydrogens, heliums, protoEtoiles, etoiles);
            }
        }
    }

    public void removeCloseStars(List<Etoile> etoiles) {

        List<Etoile> starsToRemove = new ArrayList<>(); // Objets à supprimer

        // Parcours de toute la liste des étoiles
        for (int i = 0; i < etoiles.size(); i++) {
            Etoile etoile1 = etoiles.get(i);
            double etoile1X = etoile1.getPosition().getX();
            double etoile1Y = etoile1.getPosition().getY();
            double etoile1Rayon = 9;  // Rayon de l'étoile 1

            for (int j = i + 1; j < etoiles.size(); j++) {
                Etoile etoile2 = etoiles.get(j);
                double etoile2X = etoile2.getPosition().getX();
                double etoile2Y = etoile2.getPosition().getY();
                double etoile2Rayon = 9;  // Rayon de l'étoile 2

                double distance = Math.sqrt(Math.pow(etoile1X - etoile2X, 2) + Math.pow(etoile1Y - etoile2Y, 2));

                // Vérification de la distance
                if (distance < etoile1Rayon + etoile2Rayon) {
                    if (!starsToRemove.contains(etoile1)) {
                        starsToRemove.add(etoile1);
                    }
                    if (!starsToRemove.contains(etoile2)) {
                        starsToRemove.add(etoile2);
                    }
                }
            }

        }

        //parcours de la liste des étoiles à supprimer
        for (Etoile etoile : starsToRemove) {
            etoiles.remove(etoile);
            TriggerFlash.triggerFlash(etoile, starsToRemove, panel);
            panel.removeAgent(etoile);
        }
    }



    private void createProtoEtoile(List<Agent> atomsInRegion, List<Hydrogen> hydrogens, List<Helium> heliums, List<ProtoEtoile> protoEtoiles) {
        int x = 0, y = 0;
        for (Agent atom : atomsInRegion) {
            x += (int) atom.getPosition().getX();
            y += (int) atom.getPosition().getY();

            panel.removeAgent(atom);
            if (atom instanceof Hydrogen) {
                hydrogens.remove(atom);
            } else if (atom instanceof Helium) {
                heliums.remove(atom);
            }
        }

        //calcul position moyenne à partir de x et de y (divisé par le nombre d'atomes dans la région)
        x /= atomsInRegion.size();
        y /= atomsInRegion.size();

        //création proto-étoile
        Position protoEtoilePosition = new Position(x, y);
        Velocity protoEtoileVelocity = new Velocity(0, 0);
        Acceleration protoEtoileAcceleration = new Acceleration(0, 0);
        double protoEtoileMass = 1.5e14;
        ProtoEtoile protoEtoile = new ProtoEtoile(protoEtoilePosition, protoEtoileVelocity, protoEtoileAcceleration, protoEtoileMass);
        protoEtoiles.add(protoEtoile);
        panel.addAgent(protoEtoile);
    }

    private void createEtoile(List<Agent> atomsInRegion, List<Hydrogen> hydrogens, List<Helium> heliums, List<ProtoEtoile> protoEtoiles, List<Etoile> etoiles) {
        int x = 0, y = 0;
        for (Agent atom : atomsInRegion) {
            x += (int) atom.getPosition().getX();
            y += (int) atom.getPosition().getY();

            panel.removeAgent(atom);
            if (atom instanceof Hydrogen) {
                hydrogens.remove(atom);
            } else if (atom instanceof Helium) {
                heliums.remove(atom);
            } else if (atom instanceof ProtoEtoile) {
                protoEtoiles.remove(atom);
            }
        }

        x /= atomsInRegion.size();
        y /= atomsInRegion.size();

        Position etoilePosition = new Position(x, y);
        Velocity etoileVelocity = new Velocity(0, 0);
        Acceleration etoileAcceleration = new Acceleration(0, 0);
        double etoileMass = 2e14;

        Etoile etoile = new Etoile(etoilePosition, etoileVelocity, etoileAcceleration, etoileMass);
        etoiles.add(etoile);
        panel.addAgent(etoile);
    }

}

