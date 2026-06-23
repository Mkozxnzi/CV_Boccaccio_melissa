library(stats)
library(readxl)
library(lme4)
library(lmerTest)
library(Matrix)

setwd(dirname(rstudioapi::getActiveDocumentContext()$path))

dt <- read_excel("data_base.xlsx", sheet="Feuil2")
str(dt)
names(dt)[names(dt) == "rt-2SD"] <- "rt_2SD"


# Conversion propre : transforme en nombre, les "NA" deviennent des NA réels
dt$Distance <- as.numeric(dt$Distance)
dt$RT_moyen <- as.numeric(dt$RT_moyen)


cor.test(dt$Distance, dt$RT_moyen, method="pearson", alternative="two.sided")

# Graphique avec titre, noms des axes et droite de régression

plot(
  dt$Distance,
  dt$RT_moyen,
  type = "p",
  xlab = "Distance",
  ylab = "Temps de réaction moyen (ms)",
  main = "Relation entre la distance et le temps de réaction",
  pch = 19,            # points pleins
  col = "black"        # couleur simple
)

# Modèle linéaire
modele <- lm(RT_moyen ~ Distance, data = dt)

# Ajouter la droite de régression
abline(modele, col = "red", lwd = 2)





# Importation des données
dt2 <- read_excel("C:/Documents/L3 MIASHS/Cognition/data_base.xlsx", sheet="Feuil3")

# Aperçu de la structure
str(dt2)

# Renommer la variable rt-2SD en rt_2SD
names(dt2)[names(dt2) == "rt-2SD"] <- "rt_2SD"

# Conversion des variables en numérique
dt2$rt_2SD <- as.numeric(dt2$rt_2SD)
dt2$distance <- as.numeric(dt2$distance)

# Modèle linéaire mixte (distance comme prédicteur, effet aléatoire du sujet)
modele <- lmer(rt_2SD ~ distance + (1 | subject_nr), data = dt2)

# Résumé du modèle
summary(modele)

