-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 07 mai 2025 à 14:26
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_mangawara`
--

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `id` int NOT NULL AUTO_INCREMENT,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double NOT NULL,
  `reference` int NOT NULL,
  `isbn` int NOT NULL,
  `ean` int NOT NULL,
  `synopsis` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `views` int NOT NULL,
  `sales` int NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `picture`, `name`, `editor`, `category`, `price`, `reference`, `isbn`, `ean`, `synopsis`, `created_at`, `views`, `sales`, `type`) VALUES
(1, 'Le-fossoyeur-T01-67a22cc97d6f0.webp', 'Fossoyeur Tome 1', 'komikku', 'shonen', 7.99, 2147483647, 2147483647, 2147483647, 'Lorsqu\'un humain est possédé par un esprit, il existe plusieurs phases pendant lesquelles les exorcistes peuvent encore sauver la victime. Mais quand l\'esprit a corrompu plus de la moitié de l\'âme, le \"possédé\" perd toute raison et devient une menace pour ses semblables. C\'est à ce moment qu\'on fait appel aux Fossoyeurs qui vont offrir le repos éternel à la personne possédée en lui tranchant la tête !\r\nHitsugi fait partie d\'une grande lignée de Fossoyeurs. Alors qu\'il est encore jeune et qu\'il admire le travail de son père, un incident va tout changer...', '0000-00-00 00:00:00', 68, 0, 'book'),
(2, 'Le-fooyeur-T02-67a22d339a8ee.webp', 'Fossoyeur Tome 2', 'komikku', 'shonen', 7.99, 2147483647, 2147483647, 2147483647, '\"Il porte un traumatisme dans son cœur et sa guillotine sur son dos.\"\r\nLes fossoyeurs sauvent les âmes des \"possédés\" en les tuant. Hitsugi est l\'un d\'eux. Il a hérité ce rôle de son père, après une nuit tragique. Ces derniers temps, les possédés sont plus nombreux et plus forts. C\'est dans cet étrange contexte que ses amis, Daichi et Hana, se retrouvent kidnappés. Le responsable de leur enlèvement n\'est autre que Karma, l\'héritier du conglomérat Mito.', '0000-00-00 00:00:00', 12, 0, 'book'),
(3, 'Le-fooyeur-T03-67a22dd35f81f.webp', 'Fossoyeur Tome 3', 'komikku', 'shonen', 7.99, 2147483647, 2147483647, 2147483647, 'N/C', '0000-00-00 00:00:00', 2, 0, 'book'),
(5, 'Le-fooyeur-T04-67a22e527430e.webp', 'Fossoyeur Tome 4', 'komikku', 'shonen', 7.99, 2147483647, 2147483647, 2147483647, 'La bataille finale est sur le point de commencer. Tous les fossoyeurs sont réunis devant la tour du conglomérat Mito et prêts à donner l\'assaut. Néanmoins, les généraux ennemis se tiennent face à eux. Ils devront les vaincre pour permettre Karma et Hitsugi d\'atteindre le sommet et affronter le président du conglomérat. Cependant, la malédiction de Hitsugi prend le contrôle du jeune fossoyeur...', '0000-00-00 00:00:00', 1, 0, 'book'),
(9, 'La-Romanciere-et-le-Mercenaire-vol-01-67b3093eb3a95.webp', 'La romancière et le mercenaire Tome 1', 'doki-doki', 'shonen', 7.95, 2147483647, 2147483647, 2147483647, 'À une époque de croissance rapide, marquée par les villes ensevelies sous la fumée des locomotives à vapeur après l\'achèvement du chemin de fer transcontinental, le métier de mercenaire est devenu obsolète. C\'est alors que Sword, un ancien mercenaire au chômage qui a du mal à joindre les deux bouts, reçoit une offre d\'emploi de Valderon, une célèbre romancière. Sa mission consiste à l\'escorter jusqu\'à une chaîne de montagnes maudite non répertoriée sur les cartes. Mais leur voyage les entraînera très vite dans les machinations de l\'État et de l\'Église .', '0000-00-00 00:00:00', 5, 0, 'book'),
(13, 'La-Romanciere-et-le-Mercenaire-vol-02-67b30c9320548.webp', 'La romancière et le mercenaire Tome 2', 'doki-doki', 'shonen', 7.95, 2147483647, 2147483647, 2147483647, 'Sword, un ancien mercenaire au chômage ayant du mal à joindre les deux bouts reçoit une offre d\'emploi de Valderon, une très célèbre romancière. Sa mission consiste à l\'escorter jusqu\'à une chaîne de montagnes maudites qui n\'est étrangement répertoriée sur aucune carte. Mais leur voyage les entraînera très vite dans les machinations de l\'État et de l\'Église ...\r\n\r\nEmbarqués ainsi dans diverses situations périlleuse, nos deux protagonistes vont peu à peu se rapprocher. À leurs côtés, nous en apprendrons plus sur l\'élixir d\'immortalité qui reposerait dans les montagnes, le complot du cardinal Malmsteen et le passé de Valderon.', '0000-00-00 00:00:00', 0, 0, 'book'),
(15, 'La-Romanciere-et-le-Mercenaire-vol-03-67b30e3bbe7ec.webp', 'La romancière et le mercenaire Tome 3', 'doki-doki', 'shonen', 7.95, 2147483647, 2147483647, 2147483647, 'Une romancière en quête d\'inspiration part explorer la noirceur d\'un monde oublié...\r\n\r\nLa romancière Valderon et son garde du corps, le mercenaire Sword, sont enfin arrivés dans les montagnes de Maléficio où est censé se trouver l\'élixir d\'immortalité. Mais ce qui les attend sur place n\'est autre que le complot du cardinal et la trahison d\'une connaissance !\r\n\r\nAu terme de ce voyage mêlant passé et futur, la véritable identité de la créature immortelle, le passé de Sword et le grand secret qui entoure ce monde seront enfin révélés !\r\n\r\nVoici le dernier volume d\'une histoire qui met en scène une épée en fer et une machine à écrire.', '0000-00-00 00:00:00', 0, 0, 'book'),
(19, 'Gran-Familia-T01-67b310005bee5.webp', 'Gran familia Tome 1', 'komikku', 'shonen', 7.99, 2147483647, 2147483647, 2147483647, 'Les demi-humains, aux pouvoirs surnaturels, ont toujours vécu en dissimulant leur vraie nature au monde. Ils se sont regroupés au sein de \"familles\" et représentent une menace pour la société. Parmi tous ces gangs existants, l\'un d\'entre eux a réussi à se placer au sommet de la hiérarchie, une famille mafieuse légendaire de demi-humains qui règne en maître : la \"Gran Familia\" !', '0000-00-00 00:00:00', 0, 0, 'book'),
(20, 'Gran-Familia-T02-67b31054c9217.webp', 'Gran familia Tome 2', 'komikku', 'shonen', 7.99, 2147483647, 2147483647, 2147483647, 'Vampires et loups-garous. Pistolets et crocs. L\'affrontement de deux idéaux se transforme en une bataille épique.', '0000-00-00 00:00:00', 0, 0, 'book'),
(21, 'No-Game-No-Life-T01-67b5fb498d061.webp', 'No Game No Life Tome 1', 'ototo', 'seinen', 8.35, 2147483647, 2147483647, 2147483647, 'Sora et Shiro sont deux frères et sœurs : le plus grand est sans emploi, la plus jeune, déscolarisée. Ils vivent ensemble confinés chez eux, en marge de la société. Sur Internet, on parle d\'eux comme une véritable légende urbaine, au vu de leur talent aux jeux vidéo. Le monde réel, lui, n\'est rien de plus qu\'un « jeu pourri » pour la fratrie. Mais un beau jour, quelqu\'un se surnommant « Dieu » les transporte soudainement dans un autre monde où tout serait déterminé par les jeux ! Ces deux rebuts de la société deviendront-ils les sauveurs de ce nouveau monde ?', '0000-00-00 00:00:00', 0, 0, 'book'),
(22, 'No-Game-No-Life-T02-67b5fc040e1fe.webp', 'No Game No Life Tome 2', 'ototo', 'seinen', 8.35, 2147483647, 2147483647, 2147483647, 'Sora et Shiro sont deux frères et soeurs. Le plus grand est sans emploi, la plus jeune, déscolarisée. Ils vivent ensemble confinés chez eux, en marge de la société.\r\nSur internet, ils sont connus en tant que [ ]et demeurent invaincus au vu de leur talent aux jeux vidéos.\r\nUn beau jour, un garçon nommé Tet et se surnommant lui-même \"Dieu\" apparaît devant eux et les transporte à Disboard, le monde-échiquier.\r\n\r\nIls se retrouvent à Elchea, le pays des Imanitys, et font la rencontre de Stéphanie Dola (surnomméee Steph), petite-fille du précédent roi. Ils décident alors de participer au tournoi qui élira le prochain souverain du pays et affrontent Kurami Zell, la jeune Imanity ayant vaincu Steph, pour l\'ultime partie qui décidera du futur roi !', '0000-00-00 00:00:00', 0, 0, 'book');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `date` datetime NOT NULL,
  `figurine_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C16A2B381` (`book_id`),
  KEY `IDX_9474526CC550FC1B` (`figurine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `book_id`, `content`, `date`, `figurine_id`) VALUES
(2, 1, 1, 'salut', '2025-03-07 10:06:42', NULL),
(3, 2, 1, 'hello', '2025-03-07 10:09:57', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250213111717', '2025-02-13 11:22:22', 85),
('DoctrineMigrations\\Version20250220090934', '2025-02-20 09:09:40', 151),
('DoctrineMigrations\\Version20250221104427', '2025-02-21 10:45:23', 112),
('DoctrineMigrations\\Version20250224154428', '2025-02-24 15:44:45', 179),
('DoctrineMigrations\\Version20250227112032', '2025-02-27 11:20:41', 65),
('DoctrineMigrations\\Version20250313112408', '2025-03-13 11:24:21', 178),
('DoctrineMigrations\\Version20250313131921', '2025-03-13 13:19:28', 190),
('DoctrineMigrations\\Version20250321092537', '2025-03-21 09:25:46', 76);

-- --------------------------------------------------------

--
-- Structure de la table `figurine`
--

DROP TABLE IF EXISTS `figurine`;
CREATE TABLE IF NOT EXISTS `figurine` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` int NOT NULL,
  `height` double NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `views` int NOT NULL,
  `sales` int NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `figurine`
--

INSERT INTO `figurine` (`id`, `name`, `picture`, `price`, `brand`, `description`, `reference`, `height`, `created_at`, `views`, `sales`, `type`) VALUES
(2, 'ANI AnimeHeroes Beyond - Naruto', 'ANI-AnimeHeroes-Beyond-Naruto-67f536535dc0a.webp', 33.99, 'bandai', 'Avis aux fans, joueurs et collectionneurs de la saga Naruto , Anime Heroes figurines est fait pour vous ! Particulièrement détaillées, elles mesurent 17 cm et peuvent prendre toutes les positions grâce à leurs 16 points d\'articulation. Ces figurines sont livrées avec des mains supplémentaires pour recréer toutes les scènes de la série.\r\nRetrouvez ici Uzumaki Naruto, le célèbre ninja et héros de Konoha, dans sa transformation Kyubi. Il porte ses vêtements abîmés tels qu\'on les voit lors de son combat contre son meilleur ami Sasuke Uchiha, dans la série Naruto Shippuden . Il y a deux modèles de figurines Anime Heroes Beyond Naruto à collectionner !\r\n\r\n\r\nNe convient pas aux enfants de moins de trois ans. Petites pièces - Risque d\'étouffement.', 2147483647, 17, '2025-04-08 14:44:33', 2, 0, 'figurine'),
(3, 'ANI FIGURINE - RYOMEN SUKUNA (2ND WAVE)', 'ANI-FIGURINE-RYOMEN-SUKUNA-2ND-WAVE-67f5372f699d2.webp', 25.99, 'bandai', 'Avis aux fans, joueurs et collectionneurs de la saga Jujutsu Kaisen, Anime Heroes figurines est fait pour vous !\r\n\r\nEntrez dans le monde sombre et mystique de Jujutsu Kaisen avec cette impressionnante figurine de Sukuna ! Mesurant 17 cm, cette figurine est une réplique fidèle du personnage de l\'anime. Elle possède de nombreux points d\'articulation, ce qui vous permet de recréer les poses de combat emblématiques de Sukuna.\r\nIl existe de nombreux autres modèles de figurines Anime Heroes Jujutsu Kaisen à collectionner !\r\n\r\n\r\nNe convient pas aux enfants de moins de trois ans. Petites pièces - Risque d\'étouffement.', 2147483647, 17, '2025-04-08 14:48:15', 0, 0, 'figurine'),
(4, 'Figurine Funko Pop One Piece Roronoa Zoro', 'Figurine-Funko-Pop-One-Piece-Roronoa-Zoro-67f640dda8d91.webp', 16.99, 'bandai', 'TAILLE IDÉALE POUR COLLECTIONNER - D\'une hauteur d\'environ 9,5 cm, cette mini-figurine en vinyle peut compléter d\'autres objets et s\'intègre parfaitement dans votre collection ou sur votre bureau.\r\nMATIÈRE VINYLE DE PREMIÈRE QUALITÉ - Fabriqué en vinyle durable de haute qualité, cet objet de collection est conçu pour durer et résister à l\'usure quotidienne, garantissant ainsi un plaisir durable aux fans ainsi qu\'aux collectionneurs.\r\nCADEAU PARFAIT POUR LES FANS ONE PIECE - Idéal pour les vacances, anniversaires, occasions spéciales ou tout simplement comme cadeau, cette figurine exclusive est un ajout indispensable à toute collection d\'objets One Piece\r\nAGRANDISSEZ VOTRE COLLECTION - Ajoutez cet objet en vinyle unique Roronoa Zoro à votre assortiment grandissant de figurines Funko Pop! et recherchez d\'autres objets de collection rares et exclusifs pour obtenir un ensemble complet\r\nMARQUE PHARE DE LA POP CULTURE - Faites confiance à l\'expertise de Funko, le premier créateur de produits dérivés de la culture pop qui comprend des figurines en vinyle, jouets articulés, peluches, vêtements, jeux de société et bien plus encore.', 2147483647, 11.4, '2025-04-09 09:41:49', 1, 0, 'figurine'),
(5, 'Figurine Funko Pop My Hero Academia S5 Shoto Todoroki', 'Figurine-Funko-Pop-My-Hero-Academia-S5-Shoto-Todoroki-67f642219d548.webp', 16.99, 'funko-pop', 'Funko POP! Animation : MHA - Shoto Todoroki - My Hero Academia - Figurine en vinyle à collectionner - Idée cadeau - Marchandise officielle - Jouets pour enfants et adultes - Fans d\'anime - Figurine modèle pour collectionneurs Description du produit Shoto Todoroki est un héros en formation au lycée UA, apprendre à utiliser sa capacité bizarre à invoquer le feu et la glace comme moyen de lutter contre la Ligue des méchants. Aide Pop! Shoto Todoroki poursuit sa formation dans votre collection My Hero Academia aux côtés de ses camarades. La figurine en vinyle mesure environ 4,8 pouces. Avertissement de sécurité Surveillance d\'un adulte requise TAILLE DE COLLECTION IDÉALE - Mesurant environ 9,5 cm (3,75 pouces) de hauteur, cette mini figurine en vinyle complète d\'autres articles de collection et s\'intègre parfaitement dans votre vitrine ou sur votre bureau. MATÉRIAU EN VINYLE DE QUALITÉ SUPÉRIEURE - Fabriqué à partir de vinyle durable de haute qualité, cet objet de collection est conçu pour durer et résister à l\'usure quotidienne, garantissant un plaisir durable aux fans et aux collectionneurs. CADEAU PARFAIT POUR LES FANS DE MY HERO ACADEMIA - Idéal pour les vacances, les anniversaires ou les occasions spéciales et comme cadeau, cette figurine exclusive est un ajout incontournable à toute collection de produits My Hero Academia Agrandissez votre collection - Ajoutez cette pièce d\'exposition en vinyle SHOTO TODOROKI unique à votre assortiment croissant de Funko Pop! figurines et recherchez d\'autres objets de collection rares et exclusifs pour un ensemble complet. MARQUE LEADER DE LA CULTURE POP - Faites confiance à l\'expertise de Funko, le premier créateur de produits de la culture pop comprenant des figurines en vinyle, des jouets d\'action, des peluches, des vêtements, des jeux de société et plus.', 2147483647, 11.4, '2025-04-09 09:47:13', 0, 0, 'figurine');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:169:\\\"http://127.0.0.1:8000/verify/email?expires=1739875378&signature=C9xpndrbFjjFNKskSiiCCvmD6uy6XnAjE4DQ8BLMZRc%3D&token=CRbh%2FUidWPonIrPqPxZ%2BdkspAX6RzHlkEp3jDb%2FdANc%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:27:\\\"francoise.johanis@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:6:\\\"master\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:23:\\\"johanis.calin@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:25:\\\"Please Confirm your Email\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-02-18 09:42:59', '2025-02-18 09:42:59', NULL),
(2, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:163:\\\"http://127.0.0.1:8000/verify/email?expires=1740493705&signature=hQUTBqMndygekNCfjGspSVAEBjkPIkAMzX1vxh9oZ08%3D&token=sKxm9IgnIeaZzP7jVlXuR5vpGBjT8f7KwY38T0BwNf0%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:27:\\\"francoise.johanis@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:6:\\\"Master\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:15:\\\"calin@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:39:\\\"Veuillez confirmer votre adresse e-mail\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-02-25 13:28:26', '2025-02-25 13:28:26', NULL),
(3, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:165:\\\"http://127.0.0.1:8000/verify/email?expires=1740494450&signature=Ym%2BxUl6VW72kXhmWUAIVcxvI6yuMgxHBtMxAD34V72Y%3D&token=j4im5s1MWQL5d2aTIlmZijse62ExnsJCiJNXOIZuDHo%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:27:\\\"francoise.johanis@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:6:\\\"Master\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:15:\\\"azert@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:39:\\\"Veuillez confirmer votre adresse e-mail\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-02-25 13:40:50', '2025-02-25 13:40:50', NULL),
(4, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:173:\\\"http://127.0.0.1:8000/verify/email?expires=1740495806&signature=1PxKJtHZroqhUJK%2BHR%2FiZGjHHDSisOnooSMt2IHTeb0%3D&token=fpx1nH23rDEGu%2Fvipiql%2BabspEQNDAAv%2Bo9uZSNUuus%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:27:\\\"francoise.johanis@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:6:\\\"Master\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:20:\\\"louca4@explosion.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:39:\\\"Veuillez confirmer votre adresse e-mail\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-02-25 14:03:27', '2025-02-25 14:03:27', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `picture_user`) VALUES
(1, 'johanis.calin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$IXWwZO2oRpENZ0bTOBMoW.eUQ9UijHcXqLL7dF3hwhriA7fshmXRe', 0, 'user5748875672', 'default.webp'),
(2, 'calin@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$UThQC7fpjA4l.rN3BOKHrOK5qzRYQFTMKKpCGAZEh6d3qg/.meh9W', 0, 'user3582926172', 'default.webp'),
(3, 'azert@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$MtiQ99az0nUfxHklzkfMKOBhLbIEI5QF4y8o40vDDW/9LIwcy8kcC', 0, 'user1844993202', 'default.webp'),
(4, 'louca4@explosion.com', '[\"ROLE_CLIENT\"]', '$2y$13$5mfC6SiqqngfSt1m87ggweqgaVVuK8cIFc/A4VPjbY7uLLXcxvwcK', 0, 'user7931697399', 'default.webp');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C16A2B381` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CC550FC1B` FOREIGN KEY (`figurine_id`) REFERENCES `figurine` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
