package fr.um3.projet;

import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.CopyOnWriteArrayList;

public class Panel extends JPanel {
    /*
    Creation de la classe : Camille Seveyrat
    Cette classe sert à l'initialisation et l'affichage de nos agents sur un panneau
     */
    private List<Hydrogen> hydrogens;
    private List<Helium> heliums ;
    private List<ProtoEtoile> protoEtoiles;
    private List<Etoile> etoiles;
    private Alien alien;
    private List<Agent> allAgents;

    Position target = new Position(-20, -20);
    PathFinding pathfinding ;

    Transformations transformations ;

    public Panel() {
        setBackground(Color.BLACK); //internet
        //Agents
        hydrogens = new CopyOnWriteArrayList<>();
        heliums = new CopyOnWriteArrayList<>();
        protoEtoiles = new CopyOnWriteArrayList<>();
        etoiles = new CopyOnWriteArrayList<>();
        allAgents = new CopyOnWriteArrayList<>();

        //Pathfinding
        this.alien = new Alien(new Position(600, 600));
        pathfinding = new PathFinding(alien.getPosition(), target, etoiles, protoEtoiles, this);

        transformations = new Transformations(this);

        //Simulation
        initializeAgents();
        startSimulation();
    }

    private Position generateRandomTarget() {
        /*
        Auteure : Melissa Boccaccio
        Cette fonction est utile au pathfinding pour générer des positions aléatoires à atteindre pour l'alien
         */
        int x = (int)(Math.random() * 800.0);
        int y = (int)(Math.random() * 650.0);
        return new Position((double)x, (double)y);
    }

    public void removeAgent(Agent agent) {
        /*
        Auteure : Camille Seveyrat
        Cette fonction sert à retirer un agent de la liste allAgents et de raffraîchir le panel
         */
        allAgents.remove(agent);
        repaint();
    }

    public void addAgent(Agent agent) {
        /*
        Auteure : Camille Seveyrat
        Cette fonction sert à ajouter un agent à la liste allAgents et de raffraîchir le panel
         */
        allAgents.add(agent);
        repaint();
    }

    private void initializeAgents() {
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

    private void updateAgentsPositions(double deltaT) {
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

    private void startSimulation() {
        /*
        Auteures : Camille Seveyrat et Melissa  (internet un peu)
        Cette fonction gère la simulation et repeint le panel grâce à un timer
        */
        Timer timer = new Timer(10, new ActionListener() {

            @Override
            public void actionPerformed(ActionEvent e) {
                // Ajout d'atomes tout au long de la simulation (Camille)
                int x = (int) (Math.random() * 800);
                int y = (int) (Math.random() * 650);
                Position position = new Position(x, y);
                Velocity velocity = new Velocity(0.5, 0.5);
                Acceleration acceleration = new Acceleration(0, 0);
                double mass = 8.5e13;

                // Ajout hydrogènes en continu
                Hydrogen hydrogen = new Hydrogen(position, velocity, acceleration, mass);
                hydrogens.add(hydrogen);
                allAgents.add(hydrogen);
                // Ajout d'un helium tous les 4 hydrogènes
                if (hydrogens.size() % 4 == 0) {
                    Helium helium = new Helium(position, velocity, acceleration, mass);
                    heliums.add(helium);
                    allAgents.add(helium);
                }

                // Création d'un flash lumineux à partir de la classe TriggerFlash (Camille)
                TriggerFlash flashHandler = new TriggerFlash();

                for (Etoile etoile : etoiles) {
                    if (etoile.isFlashing()) {

                        List<Etoile> etoilesASupprimer = new ArrayList<>();

                        etoilesASupprimer.add(etoile);

                        SwingUtilities.invokeLater(() -> flashHandler.triggerFlash(etoile, etoiles, Panel.this)); // Internet

                        break; // Sortir de la boucle après avoir trouvé la première étoile clignotante
                    }
                }

                // Appel de la fonction updateEvolution pour faire évoluer nos agents (Camille)
                transformations.updateEvolution(hydrogens, heliums, protoEtoiles, etoiles);


                // Suppression agents hors panel (Camille) (Internet)
                hydrogens.removeIf(h -> {
                    if (isOutOfBounds(h.getPosition())) {
                        removeAgent(h);
                        return true;
                    }
                    return false;
                });
                heliums.removeIf(h -> {
                    if (isOutOfBounds(h.getPosition())) {
                        removeAgent(h);
                        return true;
                    }
                    return false;
                });
                protoEtoiles.removeIf(pe -> {
                    if (isOutOfBounds(pe.getPosition())) {
                        removeAgent(pe);
                        return true;
                    }
                    return false;
                });
                etoiles.removeIf(et -> {
                    if (isOutOfBounds(et.getPosition())) {
                        removeAgent(et);
                        return true;
                    }
                    return false;
                });

                repaint();
            }
        });
        timer.start();

        //Thread pour l'actualisation du mouvement de l'alien (Melissa)
        Thread alienThread = new Thread(() -> {
            Position target = generateRandomTarget(); // Génère une première cible aléatoire

            while (!Thread.currentThread().isInterrupted()) {
                try {
                    // Vérifie si l'alien est proche de la cible (tolérance de 2 pixels)
                    if (alien.getPosition().distanceTo(target) <= 3) {
                        System.out.println("Cible atteinte : " + target);
                        target = generateRandomTarget(); // Génère une nouvelle cible
                        System.out.println("Nouvelle cible générée : " + target);
                    }

                    // Calcul du prochain pas avec le pathfinding
                    Position nextPosition = pathfinding.getNextStep(alien.getPosition(), target);

                    // Si le pathfinding échoue, génère une nouvelle cible
                    if (nextPosition.equals(alien.getPosition())) {
                        System.out.println("Bloqué ! Nouvelle cible générée.");
                        target = generateRandomTarget();
                    } else {
                        alien.setPosition(nextPosition); // Met à jour la position de l'alien
                    }

                    // Pause pour simuler le déplacement
                    Thread.sleep(18);
                } catch (InterruptedException ex) {
                    Thread.currentThread().interrupt();
                }
            }
        });
        alienThread.start();

        // Thread pour l'actualisation des positions des agents (Melissa)
        Thread agentsThread = new Thread(() -> {
            while (!Thread.currentThread().isInterrupted()) {
                double deltaT = 0.07;
                updateAgentsPositions(deltaT);
                try {
                    Thread.sleep(10); // Temporisation pour éviter une mise à jour trop rapide
                } catch (InterruptedException ex) {
                    Thread.currentThread().interrupt();
                }
            }
        });
        agentsThread.start();
    }

    private boolean isOutOfBounds(Position position) {
        /*
        Auteure : Camille Seveyrat
        Cette fonction sert à détecter si un agent sors du panel en retournant un booléen
         */
        int largeur = 800;
        int longueur = 650;
        double x = position.getX();
        double y = position.getY();
        return x < 0 || x > largeur || y < 0 || y > longueur;
    }

    private int massToSize(double mass) {
        /*
        Auteure : Melissa Boccaccio
        Cette fonction retourne la taille de l'étoile proportionnellement à sa masse
         */
        int maxSize = 80;
        int minSize = 22;
        double maxMass = 2.5e15;
        double minMass = 2e14;

        // Calcul de la taille trouvé sur internet
        int size = (int) (minSize + (maxSize - minSize) * Math.min(Math.max((mass - minMass) / (maxMass - minMass), 0), 1.0));
        return size;
    }

    @Override
    protected void paintComponent(Graphics g) {
        /*
        Auteure : Camille Seveyrat et ajouts par melissa Boccaccio
        Cette fonction nous sert à attribuer une représentation graphique à nos agents
        */
        super.paintComponent(g);

        g.setColor(Color.CYAN);
        for (Hydrogen hydrogen : hydrogens) {
            int x = (int) hydrogen.getPosition().getX();
            int y = (int)hydrogen.getPosition().getY();

            g.fillOval(x, y, 10, 10);
        }

        g.setColor(Color.BLUE);
        for (Helium helium : heliums) {
            int x = (int)helium.getPosition().getX();
            int y = (int)helium.getPosition().getY();

            g.fillOval(x, y, 10, 10);
        }

        for (ProtoEtoile protoEtoile : protoEtoiles) {
            int x = (int)protoEtoile.getPosition().getX();
            int y = (int)protoEtoile.getPosition().getY();

            g.setColor(protoEtoile.getColor()); // ** Ajout de la couleur qui évolue de la proto-étoile
            g.fillOval(x, y, 23, 23);

        }

        g.setColor(Color.YELLOW);
        for (Etoile etoile : etoiles) { // ** Ajouts : flash d'une étoile et couleur associée
            double mass = etoile.getMass();
            int size = massToSize(mass);   // Taille en fonction de la masse

            int x = (int) etoile.getPosition().getX();
            int y = (int) etoile.getPosition().getY();

            if (etoile.isFlashing()) {
                //internet
                RadialGradientPaint gradient = new RadialGradientPaint(
                        new Point(x, y), // Centre du dégradé
                        (size + 69) / 2f, // Rayon du dégradé
                        new float[]{0f, 1f}, // Points de transition
                        new Color[]{new Color(255, 255, 255, 255), new Color(255, 255, 255, 0)} // Dégradé du blanc opaque au transparent

                );

                ((Graphics2D) g).setPaint(gradient);
                g.fillOval(x - (size+70) / 2, y - (size+70) / 2, size + 70 , size + 70);

            } else {
                g.setColor(Color.YELLOW); // Dessine l'étoile avec la couleur jaune classique
                g.fillOval(x - size / 2, y - size / 2, size, size);
            }
        }

        g.setColor(Color.GREEN);
        int x = (int)alien.getPosition().getX();
        int y = (int)alien.getPosition().getY();

        g.fillOval(x,y,20,20);

    }

    public static void main(String[] args) {

        JFrame frame = new JFrame("Simulation des Agents");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(800, 650);

        Panel panel = new Panel(); //panneau

        frame.add(panel);
        frame.setVisible(true); //fenêtre
    }


}



