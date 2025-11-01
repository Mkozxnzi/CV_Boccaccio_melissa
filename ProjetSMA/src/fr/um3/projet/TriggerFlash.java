package fr.um3.projet;

import javax.swing.*;
import java.util.List;

public class TriggerFlash {
    /*
    Création de la class : Camille Seveyrat
    Cette classe permet de déclencher un flash lumineux d'une étoile grâce à la fonction triggerFlash
     */

    public static void triggerFlash(Etoile etoileFlash, List<Etoile> etoiles, Panel panel) {

        if (etoileFlash.isFlashing()) {
            return;
        }

        etoileFlash.setFlashing(true);
        panel.repaint();

        // Crée et démarre un nouveau thread pour gérer le délai du flash (Internet)
        new Thread(() -> {
            try {
                Thread.sleep(350);
            } catch (InterruptedException e) {
                e.printStackTrace(); // Gère les interruptions
            }

            // Désactiver le flash
            etoileFlash.setFlashing(false);

            // Supprimer l'étoile flashée de la liste et du panel
            etoiles.remove(etoileFlash);
            panel.removeAgent(etoileFlash);

            // Redessine le panel après la suppression (Internet)
            SwingUtilities.invokeLater(panel::repaint);
        }).start(); // Démarre le thread
    }
}
