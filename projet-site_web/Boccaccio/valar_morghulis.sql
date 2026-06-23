-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 14 déc. 2025 à 17:05
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `valar_morghulis`
--

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

CREATE TABLE `answers` (
  `id_answer` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `content` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`id_answer`, `id_question`, `id_client`, `parent_id`, `content`, `created_at`) VALUES
(4, 2, 11, NULL, 'Ya', '2025-12-14 15:53:06');

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id_art` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` float NOT NULL,
  `url_photo` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ID_STRIPE` varchar(255) DEFAULT NULL,
  `stripe_price_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id_art`, `nom`, `quantite`, `prix`, `url_photo`, `description`, `ID_STRIPE`, `stripe_price_id`) VALUES
(120394, 'Spyro', 23, 500000, 'https://th.bing.com/th/id/R.032d4d7671659b0983189f41d0355710?rik=YG6EqWkGIPN%2b5g&pid=ImgRaw&r=0', '\r\n                         -Race : Dragon élémentaire\r\n\r\n-Taille : 1,2 mètre (mais ne vous fiez pas à sa taille)\r\n\r\n-Couleur : Violet vif, cornes dorées\r\n\r\n-Origine : Royaumes du Dragon\r\n\r\n-Type : Feu/Électricité/Vent/Glace\r\n-Personnalité : Courageux, espiègle, loyal   \r\n                                                                                                Capacités et compétences\r\n                                                                                                -Vol\r\n                                                                                                -souffle élémentaire\r\n                                                                                                -charge\r\n                                                                                                -agilité extrême\r\n\r\nSpyro n’est pas un simple dragon : c’est un héros miniature au cœur immense. Petit mais redoutable, il traverse les royaumes oubliés avec une fougue inégalée. Son souffle de feu, ses charges fulgurantes et sa maîtrise des éléments font de lui un gardien agile et rusé. Il parle, pense, et agit avec une bravoure contagieuse. Posséder Spyro, c’est accueillir un compagnon loyal, toujours prêt à bondir dans l’aventure. Il est l’incarnation du courage enfantin, du feu pur et de la magie des mondes colorés.                           \r\n                                                                                                \r\n                                                                                                ', 'prod_TPsaTgcKgZ0iCb', 'price_1ST2oxPEkhJ6bte2WkMQGBrt'),
(127314, 'Krokmou', 1, 6000000, 'https://vignette.wikia.nocookie.net/how-to-train-your-dragon/images/1/1f/Krokmou_Profil.png/revision/latest?cb=20180731141408&path-prefix=fr', 'Description générale:\r\n	-Espèce: Furie nocturne\r\n	-Genre: Masculin\r\n	-Origine: Ile de Berk\r\n	-Type: Furtif/vol\r\n	-Taille: 7,92 mètre de long\r\n    \r\nCapacité et compétences:\r\n	-Tir plasma\r\n	-Echolocalisation\r\n	-Camouflage\r\n	-Vitesse\r\n\r\nDernier représentant des Furies Nocturnes, Krokmou est un dragon d’une rare intelligence et d’une agilité inégalée. \r\nCapable de voler sans bruit et de disparaître dans l’obscurité, il est le partenaire idéal pour les missions secrètes et les escapades audacieuses.\r\n\r\n Derrière ses yeux expressifs se cache une loyauté indéfectible et un esprit joueur. \r\nKrokmou est le dragon des cœurs purs et des aventuriers rusés.', 'prod_TPsaCap7KWbHKp', 'price_1ST2pWPEkhJ6bte2HqA8KPbM'),
(277983, 'Smaug', 2, 4000000, 'https://vignette.wikia.nocookie.net/lotr/images/3/3c/Smaug_Hobbit_Film_3.jpg/revision/latest?cb=20141215211722&path-prefix=de', ' -Race : Dragon de Morgoth\r\n\r\n-Taille : Environ 25 mètres\r\n\r\n-Couleur : Rouge et Or\r\n\r\n-Origine : Brande Desséchée, descendant des dragons du Premier Âge\r\n\r\n-Type : Flamme/Convoitise\r\n                                                                                                Capacités et compétences\r\n                                                                                                - Vol\r\n                                                                                                - Feu dévastateur\r\n                                                                                                - Parole et ruse\r\n                                                                                                - Armure d’écailles impénétrables\r\n                                                                                            \r\n                                  Smaug n’est pas simplement un dragon : c’est l’incarnation de la cupidité et de la puissance brute. Son corps rouge doré brille comme un brasier vivant, et son souffle est une tempête de mort. Il parle avec arrogance, pense avec ruse, et juge avec mépris. Il ne choisit pas d’alliés, il les écrase. Posséder Smaug ? Impossible. Il ne se laisse ni dompter ni amadouer. Il est le roi sous la montagne, le fléau des Nains, le cauchemar des Hommes. Son esprit est aussi acéré que ses griffes, et son cœur aussi froid que l’or qu’il garde jalousement.\r\n                                                                                                \r\n                                                                                                \r\n                                                                                                \r\n                                                                                                \r\n                                                                                                ', 'prod_TPsb33x8J04Wio', 'price_1ST2qJPEkhJ6bte2FuExp2z3'),
(302918, 'Tintaglia', 4, 1000000, 'https://th.bing.com/th/id/OIP.dfPJhduAsjjsI1_JMYshXQAAAA?w=115&h=200&c=7&r=0&o=7&dpr=2&pid=1.7&rm=3', 'Description générale:\r\n	-Race: Dragon de Valyria\r\n  	-Taille: Environ 20 mètres\r\n  	-Couleur: Argenté et Bleu\r\n  	-Origine: Cités des Anciens, fleuves acides du Désert des Pluies\r\n	-Type: Saphir/Savoir\r\n		\r\nCapacité et compétences\r\n	-Vol\r\n	-Habileté\r\n	-magie\r\n\r\n  Tintaglia n’est pas simplement une dragonne : c’est une entité ancestrale, gardienne des mémoires oubliées.\r\n  Son corps bleu irisé et ses ailes d’argent illuminent les cieux comme une constellation vivante.\r\n  Elle parle, pense, et juge. Elle choisit ses alliés avec discernement, et n’accorde sa protection qu’à ceux qui respectent les anciens pactes.\r\n  Posséder Tintaglia, c’est entrer dans une alliance millénaire avec les forces du monde ancien.', 'prod_TPscjMaCHXb6pH', 'price_1ST2r6PEkhJ6bte2HslL4iGb'),
(390127, 'Falkor', 1, 7500000, 'https://static.tweaktown.com/content/1/0/10967_02_the-neverending-story-1984-4k-blu-ray-review.jpg', '-Race : Dragon céleste\r\n\r\n-Taille : 25 mètres\r\n\r\n-Couleur : Blanc nacré, pelage doux\r\n\r\n-Origine : L’Histoire sans fin\r\n\r\n-Type : Chance/Rêve\r\n                                                                                                \r\n                                                                                                Capacités et compétences\r\n                                                                                                - Vol infini\r\n                                                                                                -Chance surnaturelle\r\n                                                                                                -Parole\r\n                                                                                               \r\n                                                                                                Falkor est une créature céleste, douce comme le vent et sage comme les étoiles. Son corps serpentin vole sans effort, porté par la chance et les rêves. Il parle avec bienveillance, pense avec clarté, et guide les âmes perdues vers leur destinée. Il est le gardien des enfants, des héros et des mondes en péril. Posséder Falkor, c’est voyager avec la chance à ses côtés, et voir l’impossible devenir réalité.\r\n                                                                                                ', 'prod_TPsehkSkU6WkeP', 'price_1ST2snPEkhJ6bte2bG75suka'),
(666042, 'L’Hydre de Lerne', 5, 1300000, 'https://www.dol-celeb.com/wp-content/uploads/2024/05/hydre-lerne.jpg', '\r\n                                                                                                -Race : Dragon aquatique à têtes multiples\r\n\r\n-Taille : 30 mètres\r\n\r\n-Couleur : Vert marécageux\r\n\r\n-Origine : Mythologie grecque\r\n\r\n-Type : Poison/Régénération\r\n                                                                                                \r\n                                                                                                Capacités et compétences\r\n                                                                                                -Repousse deux têtes pour chaque tête tranchée\r\n                                                                                                -souffle toxique\r\n                                                                                                \r\n                                                                                                Née des marais de Lerne, l’Hydre est une abomination vivante. Chaque tête tranchée en fait naître deux autres, et son souffle est un venin qui ronge les âmes. Elle ne parle pas : elle hurle, elle rampe, elle dévore. Elle fut affrontée par Héraclès, mais jamais totalement vaincue. Son corps est une énigme biologique, sa présence une malédiction. Posséder l’Hydre, c’est commander une armée de têtes immortelles.\r\n                                                                                                \r\n                                                                                            ', 'prod_TPseiCTzb4CWhX', 'price_1ST2snPEkhJ6bte2bG75suka'),
(719103, 'Drogon', 0, 10000000, 'https://cdna.artstation.com/p/assets/images/images/062/423/656/large/kamil-winczewski-1683105025483.jpg?1683105067', 'Description générale:\r\n	- Race: Dragon des terres brûlées de Valyria\r\n  	-Taille: Environ 30 mètres\r\n	-Feu/Ombre\r\n	-Couleur: Noir charbon avec reflet rouge\r\n    \r\nCapacité et compétences:\r\n	-Vol\r\n	-Souffle de feu\r\n	-Destruction massive\r\n    \r\nNé des cendres de l’ancien empire, Drogon est l’incarnation vivante du feu et de la domination. \r\n\r\nSa silhouette noire fend les cieux comme une ombre menaçante, et ses flammes réduisent en poussière tout ce qui ose s’opposer à lui.\r\nFidèle à la lignée Targaryen, il ne reconnaît qu’un seul maître. Ce dragon n’est pas un compagnon : c’est une arme vivante, un symbole de pouvoir absolu.\r\n', 'prod_TPseyv3Il8HXfd', 'price_1ST2t6PEkhJ6bte2jDYw1mxZ'),
(730283, 'Dracaufeu', 0, 500000, 'https://www.pokepedia.fr/images/thumb/1/17/Dracaufeu-RFVF.png/1200px-Dracaufeu-RFVF.png', 'Description générale:\r\n	-Espèce: flamme\r\n    -Type: Feu-Vol\r\n  	-Taille: 1.70 m\r\n	-Poids: 90.50 kg\r\n  	-Origine : Région de Kanto, élevé dans les volcans du Mont Braise\r\n  	-Couleur: Orange et intérieur des ailes bleu\r\n	-Une flamme brûle au bout de la svelte queue\r\nTalents:\r\n	-Brasier\r\n    -Force Soleil\r\n\r\nDracaufeu est bien plus qu’un dragon : c’est une légende vivante. Avec ses ailes puissantes, sa flamme éternelle et son tempérament noble, il incarne la force brute et la loyauté. Capable de fondre l’acier d’un simple souffle, il est pourtant connu pour ne jamais attaquer un adversaire plus faible que lui.\r\n\r\nÉlevé dans les meilleures conditions, chaque Dracaufeu proposé ici est certifié pur sang Kanto, dressé pour le vol longue distance, le combat aérien et la protection rapprochée. Sa flamme s’intensifie selon son humeur, et peut atteindre une teinte blanc-bleu lorsqu’il est en colère ou en pleine bataille.', 'prod_TPsfaOqSaz3tU1', 'price_1ST2twPEkhJ6bte2OjKy5jIp'),
(738910, 'Nidhogg', 1, 6900000, 'https://th.bing.com/th/id/R.2306fd48b3b0864c8b8af3ae5289445a?rik=%2btdybOr1S3Qj3A&riu=http%3a%2f%2ffc06.deviantart.net%2ffs51%2ff%2f2009%2f266%2ff%2f2%2fNidhogg_by_Ruth_Tay.jpg&ehk=kM6zlz%2fWEdC0fx78vT1hp4V6%2bQP2nf%2fBSL5vOcJs5yY%3d&risl=1&pid=ImgRaw&r=0', '\r\n– Espèce: Dragon-serpent primordial \r\n– Genre: Inconnu (forme mythique) \r\n– Origine: Niflheim, racines d’Yggdrasil \r\n– Type: Corruption / Dévoreur cosmique \r\n– Taille: Colossale, serpentine, en constante mutation\r\n\r\nCapacités et compétences: \r\n– Rongement des fondations du monde\r\n– Communication interdimensionnelle via l’écureuil Ratatosk\r\n– Résistance aux énergies cosmiques \r\n– Influence sur les âmes damnées \r\n– Dissimulation dans les brumes de Niflheim \r\n– Symbiose avec les cycles de destruction et de renaissance\r\n                                                                     \r\n                                                                                                                         Dernier gardien des racines d’Yggdrasil, Nidhogg incarne le chaos cyclique et la régénération cosmique. Il ronge les fondations du monde en silence, influençant les royaumes par sa présence invisible. \r\n                                                                     \r\n                                                                     \r\n                        										Son acquisition est réservée aux maîtres du néant, aux architectes du Ragnarök, et à ceux qui comprennent que toute fin est un nouveau commencement.', 'prod_TPsfgRHUClPkLt', 'price_1ST2uJPEkhJ6bte29XohFY7i'),
(881204, 'Mushu', 478, 50000, 'https://easydrawingguides.com/wp-content/uploads/2021/08/mushu-from-mulan-step-by-step-drawing-tutorial-step-10.png', '-Race : Dragon oriental miniature\r\n-Taille : Environ 60 centimètres\r\n-Couleur : Rouge flamboyant avec accents dorés\r\n-Origine : Monts sacrés de l’Empire du Milieu\r\n-Type : Feu/Esprit\r\n\r\nCapacités et compétences:\r\n	-Parole\r\n	-Camouflage\r\n	-Invocation magique\r\n	-Feu contrôlé\r\n\r\nMushu n’est pas un dragon de guerre, mais un dragon de cœur. Petit par la taille, immense par la personnalité, il est le gardien des âmes courageuses et des causes justes. Doté d’un humour acéré et d’une loyauté sans faille, Mushu est l’allié idéal pour ceux qui cherchent un compagnon rusé, bavard, et toujours prêt à défendre l’honneur. \r\n                                                                                                Il ne choisit pas son maître selon la puissance, mais selon la bravoure. Posséder Mushu, c’est accueillir un esprit protecteur qui veille autant par la ruse que par la flamme.', 'prod_TPsfO9FgUPCPp3', 'price_1ST2ubPEkhJ6bte2izRpI1Qk'),
(883201, 'Yamata no Orochi', 1, 20000000, 'https://i.pinimg.com/originals/92/18/72/9218723e3e3646682453f7b3e4013c1a.jpg', '-Race : Dragon divin japonais\r\n\r\n-Taille : 100 mètres de long\r\n\r\n-Couleur : Rouge sang, écailles noires\r\n\r\n-Origine : Mythologie japonaise\r\n\r\n-Type : Poison/Chaos\r\n\r\n                                                                                                \r\n                                                                                                Capacités et compétences\r\n                                                                                                -Huit souffles différents\r\n                                                                                                -régénération\r\n                                                                                                -terreur divine\r\n                                                                                                \r\n                                        Yamata no Orochi est une entité divine, née du chaos et nourrie par les rivières sanglantes du Japon ancien. Ses huit têtes sifflent des malédictions, chacune porteuse d’un souffle mortel. Il ne parle pas : il hurle, il dévore, il corrompt. Sa présence fait trembler les montagnes et noircit les cieux. Il fut vaincu par le dieu Susanoo, mais son essence demeure, prête à être invoquée par ceux qui osent pactiser avec l’abîme. Posséder Orochi, c’est dominer la peur elle-même.                                                        \r\n                                                                                                \r\n                                                                                                \r\n                                                                                                \r\n', 'prod_TPsgCMKqFjyfXP', 'price_1ST2v9PEkhJ6bte2FXJO2ss4'),
(896499, 'Jörmungandr', 1, 3000000, 'https://vignette.wikia.nocookie.net/mythology/images/e/ee/World_Serpent_full_body.jpg/revision/latest?cb=20190218131332', '\r\n                                                                                              -Race : Dragon-serpent primordial\r\n\r\nTaille : Suffisamment grand pour encercler le monde\r\n\r\nCouleur : Vert abyssal\r\n\r\nOrigine : Mythologie nordique\r\n\r\nType : Océan/Apocalypse\r\n                                                                                                \r\n                                                                                                Capacités et Compétences\r\n                                                                                                -Contrôle des marées\r\n                                                                                                -poison universel\r\n                                                                                                -prophétie du Ragnarök\r\n                                                                                                \r\n                                  Jörmungandr est le cercle du monde, le serpent qui enlace Midgard. Son corps est si vaste qu’il se mord la queue, maintenant l’équilibre cosmique. Il ne parle pas, mais ses mouvements font trembler les océans. Il est lié au destin de Thor, et sa résurgence annonce le Ragnarök. Posséder Jörmungandr, c’est manipuler les marées du destin, et risquer de briser le cycle du monde.                                                              \r\n                                                                                               ', 'prod_TPsghQaf2R8xxB', 'price_1ST2vUPEkhJ6bte23Vq1t2bR'),
(982001, 'Aile-de-Mort', 1, 8500000, 'https://cdn-www.konbini.com/files/2024/01/Design-sans-titre-24.png?auto=webp&width=1920&quality=75', '\r\n-Espèce : Dragon noir corrompu\r\n-Genre : Masculin\r\n-Origine : Azeroth\r\n-Type : Destructeur / Cataclysme\r\n-Taille : plusieurs dizaines de mètres\r\n\r\nCapacité et compétences :\r\n-Plaques de métal incandescente\r\n-Souffle de feu apocalyptique\r\n-Vol intercontinental\r\n-Résistance extrême\r\n-Manipulation tectonique\r\n-Aura de terreur\r\n\r\n\r\nAutrefois connu sous le nom de Neltharion le Gardeterre, Aile-de-Mort était l’un des cinq Aspects dragons chargés de protéger Azeroth. Corrompu par les Dieux très anciens, il est devenu le Destructeur, porteur du Cataclysme, et incarnation vivante de la fin du monde. 	\r\n	\r\n    	Son corps est fissuré par la puissance qu’il contient, maintenu par des plaques de métal brûlantes.\r\nIl ne cherche ni gloire ni conquête — seulement l’anéantissement.Aile-de-Mort est le dragon des mondes brisés, des civilisations effondrées, et des rêves consumés par le feu. Il est l’ultime adversaire, celui que même les héros redoutent.\r\n', 'prod_TPshlOTOtZL97k', 'price_1ST2vvPEkhJ6bte2q7dKmpfR'),
(74563124, 'Fáfnir', 1, 1000000, 'https://cdnb.artstation.com/p/assets/images/images/034/439/213/large/sebastian-b-fafnir13-eevee-comp-9.jpg?1612292670', '-Race : Nain maudit devenu dragon\r\n\r\n-Taille : 15 mètres\r\n\r\n-Couleur : Vert sombre, yeux d’ambre\r\n\r\n-Origine : Mythologie nordique\r\n\r\n-Type : Malédiction/Trésor\r\n                                                                                                \r\n                                                                                                Capacités et compétences\r\n                                                                                                -Souffle empoisonné\r\n                                                                                                -invulnérabilité \r\n                                                                                                -parole humaine\r\n                                                                                                \r\n                                                                                                Fáfnir était autrefois un nain, mais la soif de l’or l’a transformé en bête. Son corps est une forteresse d’écailles, son souffle un poison lent. Il ne protège pas son trésor : il le fusionne avec son âme. Il parle avec méfiance, juge avec rancune, et attaque avec fureur. Son antre est un piège, son regard une malédiction. Posséder Fáfnir, c’est hériter d’un pouvoir ancien, mais aussi d’une malédiction qui consume lentement le cœur.\r\n                                                                                                ', 'prod_TPshKp2mpcU1Mh', 'price_1ST2wDPEkhJ6bte21j4XRuuP');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `adresse` text,
  `numero` varchar(50) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `ID_STRIPE` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `prenom`, `adresse`, `numero`, `mail`, `mdp`, `ID_STRIPE`) VALUES
(11, 'Boccaccio', 'Melissa', 'mtp', '0783872874', 'melissa.boccaccio@gmail.com', '$2y$10$F852K0QYQD0oWGdRp8SSbeRqifzSzLFOee3IAvHMeTc6XO2EnNJMm', 'cus_TYU2p7iLUvkRtP'),
(12, 'UNGER', 'Damien', '12rue de ranspach', '0601769922', 'damien.unger@laposte.net', '$2y$10$3.uJRM8MHDYoGzNDT9F/puaMaPBRv7lIo9qX35s4nh7RAIz26W4iq', NULL),
(14, 'oda', 'pablo', '54 rue de brest', '0673562698', 'pab@gmail.com', '$2y$10$cX0UukPEFkMok6IXG2SofO5oNCNLWNsm06gynFFOMnGS7.gH1mjNy', NULL),
(16, 'Lilalo', 'lola', '39 rue de lala', '0683729392', 'lolalala@gmal.com', '$2y$10$oGGua5m/QZhO405LHrQZpeSZ4YQT9kY3z35cQRy2N3zfSBlaFgHLC', NULL),
(17, 'trfg', 'trfgyu', 'tyguh', 'yguh', 'a@a.com', '$2y$10$9yWbRU.Ck/8csBNa9D5OfOIhfKg9CQW8GXf7iqRtzUvy8ihPjAotS', NULL),
(18, 'Cash', 'dguzibhj', '_r\"hçuezfi', '09876584', 'cash@free.fr', '$2y$10$TiGVFMqh6sXt5iGVVcY7cOfgLzKrcN2S7ixoSf6vrruwDuwQFl0gW', NULL),
(19, 'dzguis', 'tucgh', 'ycjhgb', '0683729392', 'kitch@g.mail', '$2y$10$zJjG.f/5dyN5FgJ5k8FPUupNuBxq9L.sbgmsMSGZ7hUcCNPlSbFxO', 'cus_TYRoNpPcUnJELe'),
(20, 'BLO', 'bla', '12 ertgb', '1234567890', 'blo@free.fr', '$2y$10$rgOFVQu2jIsVLekZEof0uOcc80fv4vhkkCcK0vW93oCd125v/.RNG', 'cus_TQcmXi88lSTJqN'),
(21, 'ma', 'ta', 'sa', '132453672', 'mata@f.fr', '$2y$10$135Y6WLsxT.5JL8FeipCN.WTghLCSvrdiW0qOBy05.XOWCVrJHT6i', 'cus_TQdQlhwthSm5XA'),
(22, 'Seveyrat', 'Camille', '44 impasse du Rigaudon', '0783805642', 'camille.seveyrat@gmail.com', '$2y$10$Ri4YS4fR3X7UmfkYjdvQieHQFgC7ntys/YhflR16jiqjZ.3k6OFuy', 'cus_TSokxrZro0RGcm'),
(23, 'DCFGVBHN', 'vghbjniko', '13 ryuiz  cjnks', '0123456789', 'rfv@gmal.co', '$2y$10$jh6kDsiUqTgx0cyrHi9vTOSw0xxcSUKIF6EBUNUa6uzYQ0n0dAOni', 'cus_TaMnM6hl2Ski2f'),
(24, 'CGVHB', 'IJ', '9IJH', '678900987654', 'ncioezcns@haml.px', '$2y$10$tDWtSu0gQYnR1nemcI8dJOnd73bUygtwdAQVih.MGAf8JyhYoVCBG', 'cus_TaMgY77iTXqroO'),
(25, 'ZERTGHJ', 'azdfghn', '13 rue ertyhujkl', '0123456789', 'artyu.fg@gam.ca', '$2y$10$RkT4NLD95s8UCvJVpJ.P5eazH52kj0G9hyO35WnPi2epmeBq7/Xfi', 'cus_Tb8fwNZLLW2ExG'),
(26, 'ZERTYUI', 'rtyui', '14 Breghj', '0673562698', 'Zerty@g.c', '$2y$10$N4C5Cl.n.nGg2Uc4CZ38Y.twdch5pv6DuEkIB1XVGNhDGRf319Otm', 'cus_Tb8hh8weoipnsM'),
(27, 'AZERT', 'ertyu', '12azertyu', '0783805649', 'azert@c.a', '$2y$10$icE78Y9ILOAKHIkMMIGAjeB0LgN/1kyW.n5XXh7MhqkjxQcNHKNmu', 'cus_Tb8qweltOoV0Z7'),
(28, 'AZE', 'rty', '15 asx', '0132453672', 'AZE@a.Z', '$2y$10$jWEjOHl6nqx/ngVqQmbsiOKyMd9ZYdNDxTOP1BF8uu2o3cI9QtZtm', 'cus_Tb9DrtEZDFUiwy'),
(41, 'AEE', 'tyu', '12 yuio', '0987654321', 'AZE@f.r', '$2y$10$FvyVY4HJx96bqTtoyhc6juIn5OUolp.GCcNZcnMvaSSieC4sKXAM2', 'cus_TbUht3AC4xZ4pl');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `envoi` tinyint(1) DEFAULT '0',
  `date_commande` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stripe_session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `id_art`, `id_client`, `quantite`, `envoi`, `date_commande`, `stripe_session_id`) VALUES
(1, 730283, 11, 9, 0, '2025-12-06 15:22:31', NULL),
(2, 881204, 11, 4, 0, '2025-12-06 15:22:31', NULL),
(3, 881204, 12, 10, 0, '2025-12-06 15:22:31', NULL),
(4, 881204, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(5, 881204, 14, 1, 0, '2025-12-06 15:22:31', NULL),
(6, 120394, 11, -12, 0, '2025-12-06 15:22:31', NULL),
(7, 120394, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(8, 881204, 18, 1, 0, '2025-12-06 15:22:31', NULL),
(9, 881204, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(10, 881204, 20, 1, 0, '2025-12-06 15:22:31', NULL),
(11, 120394, 21, 1, 0, '2025-12-06 15:22:31', NULL),
(12, 730283, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(13, 881204, 22, 1, 0, '2025-12-06 15:22:31', NULL),
(14, 120394, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(15, 881204, 19, 1, 0, '2025-12-06 15:22:31', NULL),
(16, 277983, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(17, 120394, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(18, 120394, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(19, 120394, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(20, 896499, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(21, 127314, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(22, 127314, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(23, 127314, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(24, 302918, 11, 1, 0, '2025-12-06 15:22:31', NULL),
(25, 120394, 11, 1, 0, '2025-12-06 15:23:07', NULL),
(26, 120394, 11, 3, 0, '2025-12-06 15:33:17', NULL),
(27, 120394, 11, 1, 0, '2025-12-06 16:08:38', NULL),
(28, 127314, 11, 1, 0, '2025-12-06 16:09:59', NULL),
(29, 390127, 19, 1, 0, '2025-12-06 16:15:54', NULL),
(30, 730283, 28, 1, 0, '2025-12-13 20:05:24', 'cs_test_a1m8SiYMlNPPofanZr1rz7XCTNZhfYjHBI0K9V54A71W9c51vihw420cdi'),
(31, 881204, 41, 1, 0, '2025-12-14 16:49:21', 'cs_test_b1Eztd7mt2OihXulJ44luM6RPU9J4F4Om2oTNSg8NSn0JMxBGom3iFCGu9'),
(32, 120394, 41, 1, 0, '2025-12-14 16:49:21', 'cs_test_b1Eztd7mt2OihXulJ44luM6RPU9J4F4Om2oTNSg8NSn0JMxBGom3iFCGu9'),
(33, 730283, 41, 4, 0, '2025-12-14 16:49:21', 'cs_test_b1Eztd7mt2OihXulJ44luM6RPU9J4F4Om2oTNSg8NSn0JMxBGom3iFCGu9');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id_comment` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `content` varchar(256) NOT NULL,
  `note` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id_comment`, `id_client`, `id_art`, `content`, `note`, `created_at`) VALUES
(2, 11, 896499, 'good', 5, '2025-12-07 22:59:04');

-- --------------------------------------------------------

--
-- Structure de la table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id_like` int(11) NOT NULL,
  `id_comment` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `content` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

CREATE TABLE `paniers` (
  `id_panier` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT '1',
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paniers`
--

INSERT INTO `paniers` (`id_panier`, `id_client`, `id_art`, `quantite`, `date_ajout`) VALUES
(31, 19, 127314, 1, '2025-12-07 01:00:01'),
(64, 41, 881204, 1, '2025-12-14 15:59:48');

-- --------------------------------------------------------

--
-- Structure de la table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id_rating` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `note` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `product_ratings`
--

INSERT INTO `product_ratings` (`id_rating`, `id_client`, `id_art`, `note`, `created_at`) VALUES
(1, 11, 896499, 5, '2025-12-07 22:59:04');

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id_question` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `content` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id_question`, `id_client`, `id_art`, `content`, `created_at`) VALUES
(2, 41, 730283, 'great product', '2025-12-14 15:52:53');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `expire_at` datetime NOT NULL,
  `stripe_session_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_art`, `id_client`, `quantite`, `expire_at`, `stripe_session_id`) VALUES
(1, 127314, 11, 1, '2025-12-06 17:26:44', ''),
(2, 390127, 11, 1, '2025-12-06 17:17:14', ''),
(3, 390127, 11, 1, '2025-12-07 02:00:32', ''),
(4, 127314, 11, 1, '2025-12-07 02:01:02', ''),
(5, 730283, 23, 1, '2025-12-11 16:46:30', ''),
(6, 120394, 23, 1, '2025-12-13 18:03:29', ''),
(7, 881204, 23, 1, '2025-12-13 18:03:29', ''),
(10, 120394, 23, 1, '2025-12-13 18:08:37', ''),
(11, 881204, 23, 1, '2025-12-13 18:08:37', ''),
(12, 730283, 23, 5, '2025-12-13 18:08:37', ''),
(19, 120394, 23, 1, '2025-12-13 18:09:41', ''),
(20, 881204, 23, 1, '2025-12-13 18:09:41', ''),
(21, 730283, 23, 5, '2025-12-13 18:09:41', ''),
(22, 881204, 27, 1, '2025-12-13 18:25:31', ''),
(23, 120394, 27, 1, '2025-12-13 18:25:31', ''),
(24, 730283, 27, 5, '2025-12-13 18:25:31', ''),
(35, 881204, 27, 1, '2025-12-13 18:26:58', ''),
(36, 120394, 27, 1, '2025-12-13 18:26:58', ''),
(37, 730283, 27, 5, '2025-12-13 18:26:58', ''),
(38, 881204, 28, 1, '2025-12-13 18:48:57', ''),
(39, 120394, 28, 1, '2025-12-13 18:48:57', ''),
(40, 730283, 28, 5, '2025-12-13 18:48:57', ''),
(45, 881204, 28, 1, '2025-12-13 18:49:57', ''),
(46, 120394, 28, 1, '2025-12-13 18:49:57', ''),
(47, 730283, 28, 5, '2025-12-13 18:49:57', ''),
(52, 881204, 28, 1, '2025-12-13 18:51:05', ''),
(53, 120394, 28, 1, '2025-12-13 18:51:05', ''),
(54, 730283, 28, 5, '2025-12-13 18:51:05', ''),
(55, 730283, 28, 1, '2025-12-13 19:13:41', ''),
(56, 730283, 28, 1, '2025-12-13 19:57:54', ''),
(57, 730283, 28, 1, '2025-12-13 20:19:48', 'cs_test_a1c9syTgfY6WO0DeyhgJOkQHmQRkWzCuQDAHH3hzzwEoO7STTcOakh5c31'),
(62, 881204, 41, 1, '2025-12-14 17:14:57', 'cs_test_a12501xoHsNOXC1Buqb3TiFTl40V7dAo0XxFCD0r24IwVh0KwCl4QoaOSt');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id_answer`),
  ADD KEY `id_question` (`id_question`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id_art`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `unique_mail` (`mail`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD UNIQUE KEY `uniq_stripe_session` (`stripe_session_id`,`id_art`),
  ADD KEY `id_art` (`id_art`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD UNIQUE KEY `uniq_comment_once` (`id_client`,`id_art`),
  ADD KEY `id_art` (`id_art`);

--
-- Index pour la table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id_like`),
  ADD UNIQUE KEY `uniq_like` (`id_comment`,`id_client`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD PRIMARY KEY (`id_panier`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_art` (`id_art`);

--
-- Index pour la table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id_rating`),
  ADD UNIQUE KEY `uniq_rating` (`id_client`,`id_art`),
  ADD KEY `id_art` (`id_art`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id_question`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_art` (`id_art`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_art` (`id_art`),
  ADD KEY `expire_at` (`expire_at`),
  ADD KEY `idx_reservation_stripe` (`stripe_session_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `answers`
--
ALTER TABLE `answers`
  MODIFY `id_answer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `paniers`
--
ALTER TABLE `paniers`
  MODIFY `id_panier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id_rating` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id_question`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `answers_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `answers` (`id_answer`);

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`);

--
-- Contraintes pour la table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`id_comment`) REFERENCES `comments` (`id_comment`),
  ADD CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD CONSTRAINT `paniers_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paniers_ibfk_2` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`);

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
