-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 22 mai 2025 à 12:02
-- Version du serveur : 8.0.36
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `billetterie_2024`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `roles` json NOT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `first_name`, `email`, `password`, `created_at`, `active`, `roles`, `reset_token`, `reset_token_expires_at`) VALUES
(1, 'DAVID', 'Dylan', 'ad.jo.2024@outlook.com', '$2y$13$dG0rlzRx7ljSAs.TMv50OOd4klHubLaFx.e4g8yuEopiYT50tlU5y', '2025-03-09 19:57:50', 1, '[\"ROLE_ADMIN\"]', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `billets`
--

CREATE TABLE `billets` (
  `id` int NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tarif` decimal(6,2) NOT NULL,
  `stock` int NOT NULL,
  `custom_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `billets`
--

INSERT INTO `billets` (`id`, `type`, `tarif`, `stock`, `custom_type`) VALUES
(1, 'solo (1 personne)', 40.00, 3000000, NULL),
(2, 'duo (2 personnes)', 80.00, 3000000, NULL),
(3, 'family (4 personnes)', 120.00, 4000000, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250224184757', '2025-02-25 12:06:06', 931);

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `id` int NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`id`, `nom`, `prenom`, `email`, `roles`, `password`, `is_active`, `created_at`) VALUES
(22, 'test', 'test', 'test@mail.com', '[\"ROLE_EMPLOYE\"]', '$2y$13$9cXkrXrjPXXryw/RG47Sl.Upk0dsT.OniTrhojFvoLNukeOr1QnQu', 1, '2025-05-04 12:32:25'),
(23, 'FOSSE', 'David', 'emp_fosse@mail.com', '[\"ROLE_EMPLOYE\"]', '$2y$13$9PY1q/iPFG/Ujsqh1gpr4uk.lswVHm/pggn0YzyvXVcziqRB2SoLa', 1, '2025-05-12 17:47:36');

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE `evenements` (
  `id` int NOT NULL,
  `nom_evenement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_evenement` date NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id`, `nom_evenement`, `date_evenement`, `description`, `url_image`, `logo`) VALUES
(1, 'Athlétisme', '2024-07-27', 'Les athlètes les plus rapides du monde s\'affrontent au Stade Olympique. Des coureurs aux performances exceptionnelles se battent pour décrocher l\'or dans des épreuves de sprint, de demi-fond et de fond. L\'athlétisme est l\'une des disciplines les plus anciennes et les plus suivies des Jeux, où chaque fraction de seconde compte. Les spectateurs peuvent admirer des performances exceptionnelles où la puissance physique et la stratégie mentale se rencontrent. Le stade olympique devient le théâtre de records mondiaux et de moments inoubliables.', '01-athletisme.jpg', '1-Athlétisme.png'),
(2, 'Aviron', '2024-07-28', 'Un sport d\'endurance sur l\'eau, où la coordination et la vitesse des rameurs sont essentielles. Les compétiteurs doivent maintenir un rythme constant tout en utilisant la technique et la puissance pour glisser au maximum sur l\'eau. La compétition d\'aviron aux Jeux Olympiques se déroule sur des distances de 2000 mètres, avec des rameurs travaillant en parfaite synchronisation pour dominer leurs adversaires.', '02-aviron.jpg', '2-Aviron.png'),
(3, 'Badminton', '2024-07-29', 'Un sport rapide et exigeant, où l\'agilité et la stratégie sont essentielles. Le badminton aux Jeux Olympiques est une compétition où les meilleurs joueurs du monde se battent pour maîtriser le volant et exécuter des coups précis. L\'intensité de chaque échange, combinée à la rapidité des déplacements, en fait un sport spectaculaire à regarder.', '03-badminton.jpg', '3-Badminton.png'),
(4, 'Basket-ball', '2024-07-30', 'Le basket-ball olympique est un affrontement entre les meilleures équipes nationales du monde. Des matchs intenses et rapides où chaque action peut faire la différence. L\'alliance entre puissance physique, technique et stratégie est primordiale pour l\'emporter. Les équipes cherchent à se dépasser pour décrocher la médaille d\'or dans l\'une des compétitions les plus populaires des Jeux.', '04-Basket-ball.jpg', '4-Basket-ball.png'),
(5, 'Basket-ball 3x3', '2024-07-31', 'Une version dynamique et rapide du basket-ball traditionnel, disputée sur un demi terrain et en équipe de trois joueurs. Le 3x3 met l\'accent sur la vitesse, les compétences techniques et la créativité. Les équipes doivent s\'adapter à un jeu plus fluide, avec des attaques et des défenses rapides. Les matchs sont courts mais intenses, où chaque mouvement compte.', '05-Basket-ball 3x3.avif', '5-Basket-ball 3x3.png'),
(6, 'BMX', '2024-08-01', 'Le BMX olympique est une épreuve de vitesse et de saut, où les athlètes s\'affrontent sur une piste sinueuse, pleine d\'obstacles. La maîtrise du vélo, la prise de risques et les techniques de saut sont les clés de la réussite. La compétition est pleine de suspense, avec des virages serrés et des sauts impressionnants qui captivent les spectateurs.', '06-BMX.avif', '6-BMX.png'),
(7, 'BMX freestyle', '2024-08-02', 'Les athlètes réalisent des figures acrobatiques spectaculaires sur leur BMX, sur un parcours spécialement conçu pour les acrobaties. Le BMX freestyle combine puissance, technique et créativité. Les compétiteurs s\'efforcent d\'exécuter des sauts, des rotations et des tricks impressionnants pour épater les juges et marquer des points.', '07-BMX freestyle.avif', '7-BMX freestyle.png'),
(8, 'Boxe', '2024-08-03', 'Les combats de boxe aux Jeux Olympiques sont un test de force, d\'agilité et de stratégie. Les boxeurs doivent non seulement être puissants, mais aussi extrêmement rapides et tactiques pour désarmer leur adversaire. Chaque rencontre est un duel intense, où chaque coup compte pour marquer des points ou mettre l\'adversaire hors jeu.', '08-Boxe.avif', '8-Boxe.png'),
(9, 'Break danse', '2024-08-04', 'La break danse aux Jeux Olympiques met en avant la créativité, la maîtrise du corps et l\'énergie des danseurs. En compétition, les participants s\'affrontent dans une série de battles, où chaque mouvement, chaque figure et chaque style peuvent impressionner le jury. Un sport où la danse se mêle à la performance athlétique.', '09-Break danse.jpg', '9-Break-danse.png'),
(10, 'Canoë-kayak slalom', '2024-08-05', 'Le canoë-kayak slalom est une discipline où la rapidité et la précision sont primordiales. Les athlètes doivent naviguer à travers un parcours d\'eau tumultueuse, en évitant les obstacles et en effectuant des virages serrés. Chaque erreur peut coûter cher dans cette compétition palpitante.', '10-Canoë-kayak slalom.avif', '10-Canoë-kayak slalom.png'),
(11, 'Canoë-kayak course', '2024-08-06', 'Les athlètes s\'affrontent sur un parcours droit, où la vitesse pure et la technique de pagaie font toute la différence. Les compétiteurs doivent maintenir un rythme élevé tout en gérant l\'endurance pour réaliser des performances exceptionnelles sur les distances de 200, 500 ou 1000 mètres.', '11-Canoë-kayak-course-en-ligne.jpg', '11-Canoë-kayak course en ligne.png'),
(12, 'Cyclisme sur piste', '2024-08-07', 'Le cyclisme sur piste olympique est une discipline de vitesse où chaque compétition est une bataille contre la montre ou un duel stratégique sur une piste ovale. Les athlètes doivent maîtriser les virages serrés et les sprints explosifs pour décrocher la médaille d\'or.', '12-Cyclisme sur piste.avif', '12-Cyclisme-sur-piste.png'),
(13, 'Cyclisme sur route', '2024-08-08', 'Les cyclistes sur route affrontent des parcours sinueux, souvent montagneux, où l\'endurance et la tactique jouent un rôle crucial. La course est longue, exigeante et stratégique, et chaque équipe ou individu doit gérer son effort pour faire face aux défis du terrain et des conditions météorologiques.', '13-Cyclisme sur route.webp', '13-Cyclisme-sur-route.png'),
(14, 'Cyclisme en VTT', '2024-08-09', 'Le VTT aux Jeux Olympiques est une course de terrain accidenté, où les athlètes doivent maîtriser leur vélo tout terrain à travers des chemins rocailleux, des descentes rapides et des montées difficiles. La technique et le contrôle du vélo sont essentiels pour affronter ce parcours exigeant.', '14-Cyclisme VTT.webp', '14-Cyclisme-en-vtt.png'),
(15, 'Équitation - Saut d\'obstacle', '2024-08-10', 'Les compétiteurs s\'affrontent dans une épreuve où la précision et la communication entre le cavalier et son cheval sont primordiales. Le saut d\'obstacles est une compétition où chaque erreur coûte des points, et où la maîtrise de l\'animal et la gestion du parcours sont essentielles.', '15-Équitation - Saut d\'obstacles.webp', '15-Équitation - Saut d\'obstacles.png'),
(16, 'Équitation - Dressage', '2024-08-11', 'Le dressage est l\'art de faire exécuter des figures de haute école aux chevaux. Les cavaliers doivent démontrer leur capacité à diriger leur cheval avec finesse et précision. Cette épreuve est un véritable test d\'harmonie entre l\'homme et l\'animal.', '16-Équitation - Dressage.webp', '16-Équitation - Dressage.png'),
(17, 'Équitation - Complet', '2024-08-12', 'L\'épreuve de complet combine trois disciplines : le dressage, le saut d\'obstacles et le cross-country. Les cavaliers doivent démontrer leurs compétences sur le plan technique, mais aussi leur endurance et leur maîtrise pour réussir cette épreuve complète et exigeante.', '17-Équitation - Complet.webp', '17-Équitation - Complet.png'),
(18, 'Escalade\r\n', '2024-08-13', 'L\'escalade aux Jeux Olympiques est une compétition de vitesse, d\'\'endurance et de technique. Les grimpeurs s\'affrontent sur des murs verticaux où chaque prise, chaque mouvement doit être parfaitement maîtrisé pour atteindre le sommet en un minimum de temps.', '18-Escalade.webp', '18-Escalade.png'),
(19, 'Escrime', '2024-08-14', 'L\'escrime olympique est un sport de combat rapide où la technique et la rapidité sont essentielles. Les compétiteurs doivent anticiper les mouvements de leur adversaire et faire preuve d\'une grande précision pour marquer des touches et avancer dans la compétition.\r\n', '19-escrime.jpg', '19-Escrime.png'),
(20, 'Football\r\n', '2024-08-15', 'Le football aux Jeux Olympiques est une compétition de haut niveau où les meilleures équipes de football se battent pour décrocher la médaille d\'or. Les matches sont intenses, avec des compétiteurs rapides et des stratégies complexes sur le terrain.\r\n', '20-football.jpg', '20-Football.png'),
(21, 'Golf', '2024-08-16', 'Le golf olympique est un test de précision et de concentration. Les golfeurs doivent maîtriser leur technique sur un parcours difficile, tout en gérant les conditions de jeu et leur propre stress pour tenter de décrocher la médaille d\'or.', '21-golf.webp', '21-Golf.png'),
(22, 'Gymnastique artistique', '2024-08-17', 'La gymnastique artistique aux Jeux Olympiques est un mélange d?agilité, de force et de souplesse. Les athlètes doivent exécuter des figures acrobatiques sur divers appareils tels que la barre fixe, les anneaux ou le sol pour impressionner les juges et se rapprocher de l?or.\r\n', '22-Gymnastique artistique.avif', '22-Gymnastique_artistique.png'),
(23, 'Gymnastique rythmique', '2024-08-18', 'La gymnastique rythmique combine la danse, la souplesse et l?agilité. Les athlètes manipulent des objets comme des cerceaux, des rubans et des balles en réalisant des performances chorégraphiées. C?est un spectacle de grâce et de précision.\r\n', '23-Gymnastique rythmique.jpg', '23-Gymnastique_rythmique.png'),
(24, 'Haltérophilie', '2024-08-19', 'L’haltérophilie est un sport de force où les athlètes s’affrontent pour soulever les charges les plus lourdes possibles. Les compétiteurs doivent faire preuve de puissance, de technique et de coordination pour réussir à soulever une barre.', '24-Haltérophilie.avif', '24-Haltérophilie.png'),
(25, 'Handball', '2024-08-20', 'Le handball olympique est un sport collectif où les équipes s?affrontent dans des matchs rapides et physiques. La stratégie, la rapidité et la puissance des joueurs sont essentielles pour réussir à marquer des buts et avancer dans la compétition.\r\n', '25-Handball.jpg', '25-Handball.png'),
(26, 'Hockey sur Gazon', '2024-08-21', 'Le hockey sur gazon est un sport rapide où les joueurs doivent utiliser des crosse pour marquer des buts. La coordination, la vitesse et la stratégie sont essentielles dans cette compétition où chaque équipe cherche à dominer le terrain.\r\n', '26-Hockey sur Gazon.jpg', '26-Hockey_sur_Gazon.png'),
(27, 'Judo\r\n', '2024-08-22', 'Le judo olympique est un sport de combat où les athlètes utilisent des techniques de projection, de maintien et d?immobilisation pour gagner. Les judokas doivent faire preuve de technique, de force et de stratégie pour surpasser leur adversaire.\r\n', '27-Judo.avif', '27-Judo.png'),
(28, 'Lutte\r\n\r\n', '2024-08-23', 'La lutte olympique est une compétition où les athlètes s\'affrontent dans un combat de force et de stratégie. Les lutteurs utilisent des techniques pour contrôler et immobiliser leur adversaire tout en cherchant à marquer des points ou à obtenir un tomber.\r\n', '28-Lutte.avif', '28-Lutte.png'),
(29, 'Natation\r\n\r\n', '2024-08-24', 'La natation olympique est une compétition où les nageurs affrontent l?eau avec une technique parfaite pour accomplir des performances exceptionnelles sur différentes distances. Chaque épreuve est un test de vitesse et d?endurance. ', '29-Natation.avif', '29-Natation.png'),
(30, 'Natation eau libre\r\n\r\n', '2024-08-25', 'La natation en eau libre se déroule en plein air, où les nageurs affrontent des distances longues dans des conditions naturelles telles que la température de l?eau et les vagues. Les compétiteurs doivent faire preuve d\'endurance et de stratégie pour sortir vainqueurs.\r\n', '30-Natation eau libre.webp', '30-Natation-eau-libre.png'),
(31, 'Natation synchronisée\r\n\r\n\r\n\r\n', '2024-08-26', 'La natation synchronisée est une discipline où les nageuses exécutent des figures synchronisées dans l\'eau, alliant danse, natation et acrobatie. La coordination et la créativité sont essentielles pour impressionner les juges.\r\n', '31-Natation synchronisée.avif', '31-Natation-syncronisee.png'),
(32, 'Pentathlon moderne', '2024-08-27', 'Le pentathlon moderne combine cinq disciplines : escrime, natation, équitation, tir et course. Les compétiteurs doivent exceller dans chaque domaine pour réussir à décrocher la médaille d?or dans cette épreuve complexe.', '32-Pentathlon moderne.jpg', '32-Pentathlon_moderne.png'),
(33, 'Plongeon', '2024-08-28', 'Les plongeurs olympiques exécutent des sauts acrobatiques impressionnants depuis des plateformes de hauteurs variées. Les performances sont jugées sur la technique, la synchronisation et l?esthétique des figures réalisées dans l?eau.\r\n', '33-Plongeon.jpg', '33-Plongean.png'),
(34, 'Rugby à 7', '2024-08-29', 'Le rugby à 7 est une version rapide du rugby, où deux équipes de sept joueurs s?affrontent sur un terrain de taille réduite. Les matches sont plus courts, mais l?intensité est décuplée, avec des essais spectaculaires.', '34-Rugby à 7.avif', '34-Rugby.png'),
(35, 'Skateboard', '2024-08-30', 'Le skateboard olympique est une discipline où les athlètes exécutent des figures acrobatiques sur des rampes ou des rues aménagées. La créativité, la fluidité et l?exécution des tricks sont les clés du succès dans cette compétition.', '35-Skateboard.webp', '35-Skatebording.png'),
(36, 'Surf', '2024-08-31', 'Le surf aux Jeux Olympiques se déroule dans des vagues naturelles, où les athlètes affrontent la mer pour exécuter des man?uvres acrobatiques et techniques. Les juges évaluent la fluidité, l\'originalité et la difficulté des vagues sur lesquelles les compétiteurs surfent.', '36-surf.avif', '36-Surf.png'),
(37, 'Taekwondo', '2024-09-01', 'Le taekwondo olympique est un art martial où les compétiteurs s?affrontent dans des combats rapides et techniques. La puissance des coups de pied et la stratégie sont cruciales pour remporter la victoire.', '37-Taekwondo.jpg', '37-Taekwondo.png'),
(38, 'Tennis', '2024-09-02', 'Le tennis olympique est un sport de précision où les joueurs se battent sur des terrains en dur, en herbe ou en terre battue. Chaque match est une démonstration de puissance, d\'agilité et de stratégie.', '38-Tennis.jpg', '38-Tennis.png'),
(39, 'Tennis de Table', '2024-09-03', 'Le tennis de table aux Jeux Olympiques est une compétition où la rapidité et la précision sont essentielles. Les joueurs doivent réagir en une fraction de seconde pour renvoyer la balle avec des effets variés.', '39-Tennis de Table.avif', '39-Tennis-de-table.png'),
(40, 'Tir', '2024-09-04', 'Le tir olympique est un sport de précision où les athlètes doivent atteindre des cibles avec des armes à feu. Les compétiteurs font appel à leur concentration, leur stabilité et leur calme intérieur pour réussir dans cette discipline.', '40-Tir.jpg', '40-Tir.png'),
(41, 'Tir à l\'arc', '2024-09-05', 'Le tir à l\'arc olympique est une épreuve de précision, où les archers doivent toucher des cibles éloignées tout en faisant face à des conditions changeantes comme le vent et la pression.', '41-Tir à l\'arc.jpg', '41-Tir-a-lart.png'),
(42, 'Trampoline', '2024-09-06', 'Les compétiteurs de trampoline s?élancent dans les airs pour réaliser des figures acrobatiques impressionnantes. La puissance, la souplesse et le contrôle du corps sont essentiels pour réussir à impressionner les juges.', '42-Trampoline_1.png', '42-Trampoline.png'),
(43, 'Triathlon', '2024-09-07', 'Le triathlon est une épreuve d\'endurance où les athlètes doivent enchaîner la natation, le vélo et la course à pied, sans arrêt. L\'endurance et la stratégie sont clés pour terminer cette compétition exigeante.', '43-Triathlon.jpg', '43-Triathlon.png'),
(44, 'Voile', '2024-09-08', 'La voile est une épreuve où les navigateurs doivent maîtriser les vents et les vagues pour piloter leur bateau avec précision. La stratégie et la connaissance des conditions maritimes sont essentielles pour dominer la course.', '44-voile.avif', '44-Voile.png'),
(45, 'Beach-volley', '2024-09-09', 'Le beach-volley est une compétition où deux équipes de deux joueurs s?affrontent sur le sable. La rapidité, la technique et la force physique sont cruciales pour performer dans ce sport exigeant.', '45-beach-volley.jpg', '45-beach_volleyball.png'),
(46, 'Volley-ball', '2024-09-10', 'Le volley-ball olympique est un sport collectif où deux équipes de six joueurs se battent pour marquer des points en envoyant le ballon dans le camp adverse. La stratégie, la coordination et la rapidité sont essentielles.', '46-Volley-ball.avif', '46-Volleyball.png'),
(47, 'Water-polo', '2024-09-11', 'Le water-polo est un sport aquatique où deux équipes de sept joueurs s\'affrontent pour marquer des buts en lançant le ballon dans le but adverse. La puissance physique, l\'endurance et la stratégie sont essentielles pour réussir dans cette discipline.', '47-Water_polo.jpg', '47-Water-polo.png');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(10,2) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `admin_id` int DEFAULT NULL,
  `scanned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_key`, `order_date`, `total_price`, `first_name`, `last_name`, `email`, `address`, `postal_code`, `city`, `country`, `is_paid`, `admin_id`, `scanned`) VALUES
(171, 49, '3a8f196b0af63561', '2025-05-18 00:00:00', 40.00, 'Serap', 'DEV', 'utilisateur.jo2024@outlook.com', '10 allée Galilée', '93190', 'LIVRY-GARGAN', 'FRANCE', 1, NULL, 0),
(190, 49, 'e698d28919fee7dc', '2025-05-21 00:00:00', 120.00, 'Ser', 'DEV', 'utilisateur.jo2024@outlook.com', '7 allée Galilée', '93340', 'LE RAINCY', 'FRANCE', 1, NULL, 0),
(201, 49, '52e742f4c8c9ae21', '2025-05-21 00:00:00', 40.00, 'Ser', 'DEV', 'utilisateur.jo2024@outlook.com', '7 allée Galilée', '93190', 'LIVRY-GARGAN', 'FRANCE', 1, NULL, 0),
(202, 49, '1c48167dcff267ba', '2025-05-21 00:00:00', 120.00, 'Ser', 'Dev', 'utilisateur.jo2024@outlook.com', '4 allée du parc', '93190', 'LIVRY GARGAN', 'FRANCE', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `orders_item`
--

CREATE TABLE `orders_item` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `billet_id` int DEFAULT NULL,
  `quantite` int NOT NULL,
  `order_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unique_ticket_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_code_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders_item`
--

INSERT INTO `orders_item` (`id`, `order_id`, `user_id`, `billet_id`, `quantite`, `order_key`, `unique_ticket_key`, `qr_code_path`) VALUES
(421, 171, 49, 1, 1, '3a8f196b0af63561', '8de2ba40a7d850a3a21bb8acc2eaa682-3a8f196b0af63561-97dd14a34cfb31ed', NULL),
(448, 190, 49, 1, 1, 'e698d28919fee7dc', '8de2ba40a7d850a3a21bb8acc2eaa682-e698d28919fee7dc-824829ce03d148e7', NULL),
(449, 190, 49, 2, 1, 'e698d28919fee7dc', '8de2ba40a7d850a3a21bb8acc2eaa682-e698d28919fee7dc-31813d6fa84f0fcf', NULL),
(466, 201, 49, 1, 1, '52e742f4c8c9ae21', '8de2ba40a7d850a3a21bb8acc2eaa682-52e742f4c8c9ae21-f3d801fd4b971174', NULL),
(467, 202, 49, 3, 1, '1c48167dcff267ba', '8de2ba40a7d850a3a21bb8acc2eaa682-1c48167dcff267ba-b3dc8a33a501720e', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int NOT NULL,
  `quantite` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int NOT NULL,
  `billet_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `quantite`, `montant`, `created_at`, `user_id`, `billet_id`) VALUES
(555, 1, 40.00, '2025-05-19 12:32:46', 55, 1);

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

CREATE TABLE `payment` (
  `id` int NOT NULL,
  `billet_id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `statut_paiement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `methode_paiement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cle_paiement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `payment`
--

INSERT INTO `payment` (`id`, `billet_id`, `utilisateur_id`, `montant`, `statut_paiement`, `methode_paiement`, `cle_paiement`, `date_creation`) VALUES
(24, 1, 49, 40.00, 'completed', 'Stripe', '017342317ed4b358c7bac0c8c85d453e', '2025-05-17 21:47:39'),
(37, 1, 49, 120.00, 'completed', 'Mock', 'd7a8057c1805f8131c60f1e3a1573e60', '2025-05-21 19:06:43'),
(44, 1, 49, 40.00, 'completed', 'Mock', '95eb3f42f5d0675cf9fadd087e5b492d', '2025-05-21 21:19:02'),
(45, 3, 49, 120.00, 'completed', 'Mock', '0a7539fd2bf42df4d449610bcb3e7a35', '2025-05-21 21:28:25');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `user_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `roles` json NOT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `terms` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `email`, `password`, `created_at`, `user_key`, `is_active`, `roles`, `reset_token`, `reset_token_expires_at`, `terms`) VALUES
(49, 'DEV', 'Ser', 'utilisateur.jo2024@outlook.com', '$2y$13$2mR.aYa.8y3D3bJ2H85utOkdXzP5faBcUmLOJsYJ48cOpt16/exCW', '2025-05-01 17:00:29', '8de2ba40a7d850a3a21bb8acc2eaa682', 1, '[\"ROLE_USER\"]', NULL, NULL, 1),
(55, 'kinder', 'surprise', 'kinder@mail.com', '$2y$13$xCCILNlbX4Sof7YOKiaw1uKGB.dRHIqs5Znsb6ZVBKTzfMC4GuOj.', '2025-05-19 12:32:24', '38724ff59282715bd836c952217dbd16', 1, '[\"ROLE_USER\"]', NULL, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_880E0D76F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_880E0D76E7927C74` (`email`);

--
-- Index pour la table `billets`
--
ALTER TABLE `billets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A94BC0F0E7927C74` (`email`);

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E52FFDEEA76ED395` (`user_id`),
  ADD KEY `IDX_E52FFDEE642B8210` (`admin_id`);

--
-- Index pour la table `orders_item`
--
ALTER TABLE `orders_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B1CEE4B595154D4F` (`unique_ticket_key`),
  ADD KEY `IDX_B1CEE4B58D9F6D38` (`order_id`),
  ADD KEY `IDX_B1CEE4B5A76ED395` (`user_id`),
  ADD KEY `IDX_B1CEE4B544973C78` (`billet_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_24CC0DF2A76ED395` (`user_id`),
  ADD KEY `IDX_24CC0DF244973C78` (`billet_id`);

--
-- Index pour la table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6D28840D300C988C` (`cle_paiement`),
  ADD KEY `IDX_6D28840D44973C78` (`billet_id`),
  ADD KEY `IDX_6D28840DFB88E14F` (`utilisateur_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `billets`
--
ALTER TABLE `billets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT pour la table `orders_item`
--
ALTER TABLE `orders_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=468;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=604;

--
-- AUTO_INCREMENT pour la table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE642B8210` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_E52FFDEEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders_item`
--
ALTER TABLE `orders_item`
  ADD CONSTRAINT `FK_B1CEE4B544973C78` FOREIGN KEY (`billet_id`) REFERENCES `billets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B1CEE4B58D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B1CEE4B5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `FK_24CC0DF244973C78` FOREIGN KEY (`billet_id`) REFERENCES `billets` (`id`),
  ADD CONSTRAINT `FK_24CC0DF2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `FK_6D28840D44973C78` FOREIGN KEY (`billet_id`) REFERENCES `billets` (`id`),
  ADD CONSTRAINT `FK_6D28840DFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
