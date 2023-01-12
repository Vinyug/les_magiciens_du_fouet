-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 12, 2023 at 02:13 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `les_magiciens_du_fouet`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `body` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visibility` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_comments_posts_idx` (`post_id`),
  KEY `fk_comments_users1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `body`, `created_at`, `updated_at`, `visibility`) VALUES
(1, 2, 8, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip', '2023-01-11 12:45:38', '2023-01-11 12:45:55', 1),
(2, 2, 8, 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem', '2023-01-11 12:45:52', '2023-01-11 12:45:52', 0),
(4, 1, 9, 'Sed ut perspiciatis unde omnis iste natus', '2023-01-11 12:49:48', '2023-01-11 12:49:50', 1),
(8, 1, 6, 'suppr 2', '2023-01-11 14:56:39', '2023-01-11 14:56:39', 0),
(14, 1, 6, 'suppre 4', '2023-01-11 15:48:59', '2023-01-11 15:48:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cookers`
--

DROP TABLE IF EXISTS `cookers`;
CREATE TABLE IF NOT EXISTS `cookers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_cookers_users1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cookers`
--

INSERT INTO `cookers` (`id`, `user_id`, `firstname`, `lastname`, `slug`, `img`, `created_at`, `updated_at`) VALUES
(1, 1, 'Prénom1', 'NOM1', 'prenom-nom', 'prenom-nom.jpg', '2023-01-11 11:47:28', '2023-01-11 12:11:38'),
(2, 1, 'Prénom2', 'NOM2', 'prenom-nom-1', 'prenom-nom-1.jpg', '2023-01-11 11:48:01', '2023-01-11 12:11:46'),
(3, 1, 'Travis', 'GROSSEN', 'travis-grossen', 'travis-grossen.jpg', '2023-01-11 11:48:34', '2023-01-11 11:48:34'),
(4, 1, 'Bruno', 'Truchon', 'bruno-truchon', 'bruno-truchon.jpg', '2023-01-11 11:49:46', '2023-01-11 11:49:46'),
(5, 1, 'Mai', 'THE', 'mai-the', 'mai-the.jpg', '2023-01-11 11:50:07', '2023-01-11 11:50:07'),
(6, 1, 'Alexandra', 'DUCHENE', 'alexandra-duchene', 'alexandra-duchene.jpg', '2023-01-11 11:50:28', '2023-01-11 11:50:28'),
(7, 1, 'Jean-Pierre', 'KOFF', 'jean-pierre-koff', 'jean-pierre-koff.jpg', '2023-01-11 11:51:03', '2023-01-11 11:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL DEFAULT '1',
  `cooker_id` int UNSIGNED NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `difficulty` tinyint NOT NULL DEFAULT '1',
  `price` tinyint NOT NULL DEFAULT '1',
  `time` int NOT NULL,
  `ingredient` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `step` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `person` tinyint NOT NULL DEFAULT '1',
  `slug` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visibility` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  KEY `fk_posts_users1_idx` (`user_id`),
  KEY `fk_posts_cookers1_idx` (`cooker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `cooker_id`, `title`, `description`, `difficulty`, `price`, `time`, `ingredient`, `step`, `person`, `slug`, `img`, `created_at`, `updated_at`, `visibility`) VALUES
(1, 1, 1, 'Bouchées fumées', 'Ces petites bouchées allient la fraicheur d\'un pesto aux pistaches et épinards, avec les saveurs fumés de la saucisse de Morteau du fromage.  Elles forment des canapés originaux pour un entrée ou un apéritif dinatoire par exemple.', 2, 1, 3000, 'baguette\r\n200 g de Pesto aux pistaches et épinards\r\n200 g de saucisse de Morteau cuite\r\n4 œufs durs\r\n200 g de fromage fumé', 'Préchauffez votre four à 200°C (390°F).\r\nDécoupez 1 baguette en fines tranches, puis tartinez les de pesto.\r\nDéposez au fur et à mesure sur une plaque à pâtisserie garnie d\'une feuille de papier cuisson.\r\nPosez sur chacune un fin morceau de saucisse de Morteau cuite.\r\nPuis 1/2 tranche d’œuf dur.\r\nTerminez par une lamelle de fromage fumé.\r\nEnfournez pour 15-20 minutes de cuisson, le temps que le fromage fonde.\r\n', 2, 'bouchees-fumees', 'bouchees-fumees.jpg', '2023-01-11 12:07:20', '2023-01-11 12:10:57', 1),
(2, 1, 1, 'Filets de sole à la Dieppoise', 'Les filets de sole à la Dieppoise, en principe ce sont des filets de sole pochés qui sont servis avec une sauce crémeuse et de petits crustacés ou coquillages.  Je vous en propose une version un peu revisitée, où les filets ne sont pas pochés mais sautés, la sauce crème parfumée au jus de moules et servie avec des coquillages (moules, coques et palourdes) et des champignons, et enfin le tout est accompagné de pommes de terre pochées.  C\'est une recette un peu longue, où on prépare plusieurs parties (filets, sauce, champignons, coquillages, pommes de terre), et où la difficulté est que tout arrive chaud au moment de tout rassembler et de dresser pour servir.', 4, 2, 5820, '300 g de coques\r\n300 g de moules\r\n300 g de palourdes\r\n2 cuillères à soupe d\'huile d\'olive\r\n200 ml de vin blanc sec\r\n500 g de champignons\r\n3 cuillères à soupe d\'huile d\'olive\r\n750 g de pommes de terre\r\n200 ml de crème fraiche liquide\r\n500 g de filet de sole\r\n70 g de beurre\r\nsel\r\npoivre', 'Nettoyez et lavez 300 g de coques, 300 g de moules et 300 g de palourdes.\r\nDans une grande casserole sur feu vif, versez 2 cuillères à soupe d\'huile d\'olive.\r\nQuand elle est bien chaude versez les coquillages, puis 200 ml de vin blanc sec et couvrez.\r\nFaites cuire 2 ou 3 minutes jusqu\'à ce que tous les coquillages s\'ouvrent.\r\nRetirez les de la casserole avec une Écumoire ou une araignée et déposez les dans une passoire.\r\nVersez les 3/4 du jus de cuisson à travers une passoire très fine dans une casserole plus petite.\r\nMettez la casserole contenant le jus de cuisson sur feu doux, à découvert, et laissez réduire des 3/4.\r\nPendant ce temps jetez les coquilles et mettez les coquillages dans un bol.\r\nÉpluchez 500 g de champignons, puis émincez les.\r\nDans une poêle sur feu vif, versez 3 cuillères à soupe d\'huile d\'olive.\r\nÉpluchez et lavez 750 g de pommes de terre, coupez les en gros morceaux et faites les cuire dans de l\'eau bouillante salée (pensez à faire un départ eau froide, pour réduire le risque de voir éclater les pommes de terre).\r\nDans une petite casserole, versez 3 cuillère à soupe de jus de coquillages réduit et 200 ml de crème fraiche liquide.\r\nVersez les coquillages et les champignons dans la sauce, vérifiez l\'assaisonnement et laissez en attente sur feu très doux.\r\nFaites préparer par votre poissonnier 500 g de filet de sole, poivrez et salez chaque face.\r\nDans une grande poêle, faites fondre et laissez mousser 70 g de beurre.\r\nPendant la cuisson, arrosez les filets avec le beurre de cuisson : Inclinez la poele, prélevez du beurre de cuisson à la cuillère et versez le sur les filets de sole (faites ça plusieurs fois).\r\n', 6, 'filets-de-sole-a-la-dieppoise', 'filets-de-sole-a-la-dieppoise.jpg', '2023-01-11 12:16:35', '2023-01-11 12:16:35', 1),
(3, 1, 3, 'Lorem ipsum', 'Et Quis quam ut sint soluta hic error unde ut dolor aliquid eos eaque voluptatem ab quod maxime. Cum animi quasi et voluptas earum et sapiente ipsa sed minima perspiciatis ad architecto suscipit.', 1, 4, 1200, 'Ingredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient', 'Etape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape', 4, 'lorem-ipsum', 'lorem-ipsum.jpg', '2023-01-11 12:20:04', '2023-01-11 12:20:04', 1),
(4, 2, 6, 'Vel vero', 'Et Quis quam ut sint soluta hic error unde ut dolor aliquid eos eaque voluptatem ab quod maxime. Cum animi quasi et voluptas earum et sapiente ipsa sed minima perspiciatis ad architecto suscipit.', 3, 3, 1200, 'Ingredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient', 'Etape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape', 4, 'vel-vero', 'vel-vero.jpg', '2023-01-11 12:20:04', '2023-01-11 12:20:04', 1),
(5, 2, 4, 'Ea laudantium nihil', 'Et Quis quam ut sint soluta hic error unde ut dolor aliquid eos eaque voluptatem ab quod maxime. Cum animi quasi et voluptas earum et sapiente ipsa sed minima perspiciatis ad architecto suscipit.', 3, 4, 3600, 'Ingredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient', 'Etape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape', 4, 'ea-laudantium-nihil', 'ea-laudantium-nihil.jpg', '2023-01-11 12:20:04', '2023-01-11 12:54:52', 0),
(6, 1, 7, 'Choux à la crème', 'Une recette incontournable, des petits choux hiboux cailloux à croquer !', 4, 2, 4800, 'Des choux \r\nDe la crème \r\nDu sucre \r\n', 'Préparer la pâte : faire préchauffer le four à 200°C (thermostat 6/7).\r\nFaire chauffer dans une casserole le beurre, l\'eau, le sel et le sucre. Dès que tout est fondu, verser toute la farine d\'un coup et bien mélanger avec une cuillère en bois, jusqu\'à ce que la pâte n’adhère plus à la cuillère ni à la casserole.\r\nHors du feu, ajouter les œufs un à un, puis mélanger à chaque fois ce que le mélange soit bon.\r\nBeurrer une tôle à pâtisserie, puis disposer la pâte en petits tas (18 pour des petits choux, 12 pour des gros). Utiliser pour cela une poche à douille, ou tout simplement deux cuillères.\r\nCuire au four 20 à 25 mn. Petit truc pour vérifier la cuisson : Les choux doivent résister à une pression du doigt (ou de la cuillère, si l\'on craint de se brûler !).\r\nPréparer la crème pâtissière : faire chauffer dans une casserole le lait, le sel et le sucre vanillé.\r\nPendant que le lait chauffe, travailler dans une grande terrine le sucre et les œufs avec une cuillère en bois jusqu\'à ce que le mélange blanchisse. Incorporer la farine puis, peu à peu, le lait bouillant.\r\nReverser dans la casserole (à allure douce) et remuer jusqu\'à ce que la crème épaississe. Verser dans un plat.\r\nLorsque la crème est refroidie, vous pouvez y ajouter une cuillère à soupe d\'Amaretto ou d\'une autre liqueur (Baileys, Amiaula...). Bien mélanger après cet ajout. NB : Ne rien ajouter lorsque la crème est chaude !\r\nLa touche finale : la préparation des choux. Remplissez de crème la poche à douille et remplissez les choux un à un, en pratiquant un trou dessous (vous n\'avez jamais remarqué, dans les boulangeries, les trous sous les religieuses ?). Si vous n\'avez pas de poche à douille, couper chaque chou à moitié en deux pour y insérer la crème.\r\n', 4, 'choux-a-la-creme', 'choux-a-la-creme.jpg', '2023-01-11 12:32:01', '2023-01-11 12:32:01', 1),
(7, 1, 5, 'Croque-monsieur ', 'La fameuse recette pour croquer monsieur.', 1, 2, 1200, 'Pain de mie\r\nFromage\r\nJambon\r\nBeurre', 'Beurrez les 8 tranches de pain de mie sur une seule face. \r\nPosez 1 tranche de fromage sur chaque tranche de pain de mie. \r\nPosez 1 tranche de jambon plié en deux sur 4 tranches de pain de mie. \r\nRecouvrez avec les autres tartines (face non beurrée au-dessus). \r\nDans un bol mélanger le fromage râpé avec le lait, le sel, le poivre et la muscade. \r\nRépartissez le mélange sur les croque-monsieur. \r\nPlacez sur une plaque au four sous le grill pendant 10 mn.\r\n', 2, 'croque-monsieur', 'croque-monsieur.jpg', '2023-01-11 12:34:20', '2023-01-11 12:34:20', 1),
(8, 1, 7, 'Tartiflette super facile', 'L\'hiver approche, il est temps de préparer une bonne tartiflette des familles...', 3, 4, 2700, 'De la tarte \r\nDe la flette \r\nDu fromage de Poney\r\n', 'Cuire les pommes de terre à l’eau pendant 20 minutes, puis les éplucher et les couper en rondelles. \r\nEmincer les oignons et les faire revenir dans un peu de beurre. \r\nAjouter les lardons fumés et laisser également revenir à feu assez doux (une dizaine de minutes) en remuant régulièrement. \r\nPréparer un plat de cuisson (j’utilise un plat rond en terre cuite de 40 cm de diamètre, 10 cm de haut). \r\nFrotter généreusement le fond du plat avec une gousse d’ail. Organiser le plat en trois couches : recouvrir le fond du plat de la moitié des pommes de terre, puis ajouter le mélange oignons et lardons, ajouter enfin par-dessus le reste des pommes de terre. \r\nVerser dessus le vin blanc et la crème fleurette. \r\nPoivrer (assez copieusement) et saler (légèrement). \r\nPréchauffer le four à 190°C (thermostat 6-7). \r\nGratter les reblochons, les couper en deux dans l\'épaisseur et les poser (côté croûte en haut) sur le dessus des pommes de terre. \r\nEnfourner environ 20 minutes (je mets le four en « chaleur tournante » + gril). \r\nAu moment de servir (dans le plat de cuisson), on peut saupoudrez d’un peu de persil haché. \r\nCe plat unique se déguste (c’est le mot juste !) accompagné du vin utilisé pour la recette.\r\n', 6, 'tartiflette-super-facile', 'tartiflette-super-facile.jpg', '2023-01-11 12:35:38', '2023-01-11 13:08:33', 1),
(9, 2, 5, 'Sit voluptatem', 'Et Quis quam ut sint soluta hic error unde ut dolor aliquid eos eaque voluptatem ab quod maxime. Cum animi quasi et voluptas earum et sapiente ipsa sed minima perspiciatis ad architecto suscipit.', 1, 1, 7200, 'Ingredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient\r\nIngredient', 'Etape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape\r\nEtape', 4, 'sit-voluptatem', 'sit-voluptatem.jpg', '2023-01-11 12:20:04', '2023-01-11 12:45:05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'default',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'super admin', 'superadmin@gmail.com', '$2y$10$nXrYgZTCeJP/KS.jNmPprO4KpGJhla9E7.xxRCf6aeX/nvpfXhIui', 'super admin', '2023-01-11 11:43:56', '2023-01-11 11:45:42'),
(2, 'admin', 'admin@gmail.com', '$2y$10$ZlToeLcLR0asSaeebV8oreVM.G0i3KVVrw0PredfluUS56A5ofFPi', 'admin', '2023-01-11 11:44:41', '2023-01-11 11:46:00'),
(9, 'supprimer21', 'supprimer21@supp.fr', '$2y$10$4rWqzotaMESRZpl0xtiLxecaOzYAQP2gG5oH4tpDljpbFUsfHhV16', 'default', '2023-01-11 16:11:45', '2023-01-11 16:11:45');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_comments_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `cookers`
--
ALTER TABLE `cookers`
  ADD CONSTRAINT `fk_cookers_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_cookers1` FOREIGN KEY (`cooker_id`) REFERENCES `cookers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_posts_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
