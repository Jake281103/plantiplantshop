-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2024 at 06:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plantiplantshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `amid` int(11) NOT NULL,
  `amname` varchar(60) NOT NULL,
  `amemail` varchar(60) NOT NULL,
  `ampassword` varchar(150) NOT NULL,
  `amphonenumber` varchar(12) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`amid`, `amname`, `amemail`, `ampassword`, `amphonenumber`, `status`) VALUES
(1, 'John Dee', 'dee123@gmail.com', '$2y$10$Gs/4KCOJLidXbedZgN12q.Ab5FnB.2PCJNNjSjOqpmfcS6x.GqVfq', '09876456544', 0),
(2, 'John Smith', 'john123@gmail.com', '$2y$10$lAcnOCVdWpSEONaFU/NUu./8ch06KWYOVfSd3mPklE4F0OYCq6JYm', '09876456446', 0),
(3, 'Thaw Maung Oo', 'thaw123@gmail.com', '$2y$10$Mf.ZX7z6VY489B.qmORyEepwuGbouvJjrH2dhGa3h5JyXAVG75epu', '099874343', 1),
(4, 'Anton', 'anton@gmail.com', '$2y$10$eVDco.XWzztEqJRYAU9rD.2Jt5fGZVuc1vowQo76qLQfBN3uFdudq', '09383833722', 1),
(5, 'Yin Min Khant Aung', 'yinyin@gmail.com', '$2y$10$auoW.4IH3ypS3QVA7.KFmem6053imNDjWPM6SpuoX92DH3s3CP9dG', '09383737363', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ctgid` int(11) NOT NULL,
  `ctgname` varchar(100) NOT NULL,
  `description` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`ctgid`, `ctgname`, `description`) VALUES
(1, 'flowering plant', 'plant'),
(2, 'indoor plant', 'plant'),
(3, 'outdoor plant', 'plant'),
(4, 'flower seed', 'seed'),
(5, 'vegetable seed', 'seed'),
(6, 'fruit seed', 'seed'),
(7, 'accessory', 'accessory'),
(10, 'tree plant', 'plant');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` int(11) NOT NULL,
  `cname` varchar(50) NOT NULL,
  `cemail` varchar(50) NOT NULL,
  `phonenumber` varchar(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `homenumber` varchar(15) NOT NULL,
  `street` varchar(30) NOT NULL,
  `ward` varchar(30) NOT NULL,
  `township` varchar(30) NOT NULL,
  `region` varchar(30) NOT NULL,
  `profile` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cid`, `cname`, `cemail`, `phonenumber`, `password`, `homenumber`, `street`, `ward`, `township`, `region`, `profile`, `status`) VALUES
(1, 'Toe Kyaw Aung', 'toe@gmail.com', '09884772333', '$2y$10$d4PAKiNHF.YjyDK0tAboMOr58kJdUzWUeDLZ38yVmyWdjnOKbTTfW', '432(B)', 'Aung Zayar', '22', 'Hlaing', 'Yangon', 'assets/img/profile/Thursday-13th-June-2024-109830070.jpg', 1),
(2, 'Khine Khant Ye Wint', 'ppchan@gmail.com', '09555777888', '$2y$10$vK7MjfnSkzLlImfSlVntzeQb.lvjXQMpthApiZndKOreyQIDxJZhW', '424(A)', 'Phar Kung', '69', 'Thingangyun', 'Yangon', 'assets/img/profile/Sunday-2nd-June-2024-1048305156.jpg', 0),
(3, 'Yin Min', 'yinmin@gmail.com', '09633411242', '$2y$10$p2DFUMrpBM45fYdLsxQu.OFOSLixba75Grrhe81miru6g31BHdRqS', '', '', '', '', '', 'assets/img/profile/Thursday-20th-June-2024-533508480.jpg', 0),
(4, 'Wilson Smith', 'wilson123@gmail.com', '09837774443', '$2y$10$mJjAPIqBhtilSekRkvbETuxH7IwCKlCSM9QwzuSMdIZinHbRWGimG', 'No.225', 'Aye Thayar', '24', 'South Dagon', 'Yangon', 'assets/img/profile/Saturday-22nd-June-2024-2023596799.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `dpid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `discountpercent` tinyint(255) NOT NULL,
  `discountprice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`dpid`, `productid`, `discountpercent`, `discountprice`) VALUES
(6, 29, 10, 24.30),
(7, 42, 30, 55.30),
(8, 30, 10, 17.10),
(9, 40, 15, 20.40),
(10, 32, 20, 128.00),
(11, 43, 5, 118.75),
(12, 44, 5, 2.85),
(13, 35, 10, 3.60),
(14, 48, 20, 2.40),
(15, 38, 30, 2.80),
(16, 51, 25, 12.00),
(17, 49, 30, 2.10);

-- --------------------------------------------------------

--
-- Table structure for table `newarrived`
--

CREATE TABLE `newarrived` (
  `npid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newarrived`
--

INSERT INTO `newarrived` (`npid`, `productid`, `date`) VALUES
(3, 39, '2024-06-15'),
(4, 45, '2024-06-15'),
(5, 30, '2024-06-16'),
(6, 47, '2024-06-16'),
(7, 51, '2024-06-16'),
(8, 33, '2024-06-16'),
(9, 43, '2024-06-16'),
(10, 41, '2024-06-16'),
(11, 50, '2024-06-16'),
(12, 46, '2024-06-16'),
(13, 31, '2024-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `orderlines`
--

CREATE TABLE `orderlines` (
  `olid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderlines`
--

INSERT INTO `orderlines` (`olid`, `orderid`, `productid`, `quantity`) VALUES
(1, 1, 51, 3),
(2, 1, 40, 1),
(3, 1, 45, 1),
(4, 2, 39, 5),
(5, 3, 51, 1),
(6, 3, 35, 4),
(7, 4, 42, 3),
(8, 5, 47, 3),
(9, 5, 34, 1),
(10, 5, 31, 1),
(11, 6, 33, 1),
(12, 6, 35, 1),
(13, 6, 39, 1),
(14, 7, 51, 3),
(15, 7, 47, 4),
(16, 8, 52, 1),
(17, 9, 39, 1),
(18, 10, 39, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `odid` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `delifee` decimal(10,2) NOT NULL,
  `shipping` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `address` varchar(200) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`odid`, `customerid`, `subtotal`, `delifee`, `shipping`, `total`, `date`, `address`, `status`) VALUES
(1, 1, 60.40, 8.00, 'myeik', 68.40, '2024-06-18', 'No-20,Aung Yadanar Road, 5 ward, Palaw', 'delivered'),
(2, 1, 650.00, 0.00, 'free shipping', 650.00, '2024-06-18', 'No-224,Aung Aung Road, 33 ward, South Dagon', 'delayed'),
(3, 1, 26.40, 5.00, 'mandalay', 31.40, '2024-06-18', 'No-44, Kyw Kyw Road, 33 wards, Aung Myae', 'delivered'),
(4, 1, 165.90, 4.00, 'nay pyitaw', 169.90, '2024-06-18', 'no-2424,aye aye road, Bo Kyar ward,', 'delayed'),
(5, 1, 124.00, 7.00, 'taug gyi', 131.00, '2024-06-19', 'No-635, Kyar Nyo Road, 34 wards', 'progress'),
(6, 3, 155.60, 7.00, 'taug gyi', 162.60, '2024-06-20', 'No-2453, Kyar Zan Road, 22 Yards', 'delivered'),
(7, 3, 92.00, 5.00, 'mandalay', 97.00, '2024-06-20', 'No.446, Mg Gyi Road, 77 Yards', 'delayed'),
(8, 4, 115.00, 8.00, 'myeik', 123.00, '2024-06-24', 'No-245, Yandar Road, 25 ward', 'progress'),
(9, 4, 130.00, 2.00, 'yangon', 132.00, '2024-06-24', 'No-422, Kyw Nyut Road, 55 yards', 'delivered'),
(10, 1, 130.00, 2.00, 'yangon', 132.00, '2024-06-24', 'No-242, Aung Aung Road, 83 yard', 'progress');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `crid` int(11) NOT NULL,
  `crnumber` varchar(100) NOT NULL,
  `crname` varchar(60) NOT NULL,
  `expdate` varchar(10) NOT NULL,
  `ccv` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`crid`, `crnumber`, `crname`, `expdate`, `ccv`) VALUES
(1, '42424242424242424242', 'thaw maung oo', '11/25', '222');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` int(11) NOT NULL,
  `pname` varchar(60) NOT NULL,
  `description` text NOT NULL,
  `additionalinfo` text NOT NULL,
  `size` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` tinyint(1) NOT NULL,
  `newarrived` tinyint(1) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `pname`, `description`, `additionalinfo`, `size`, `price`, `quantity`, `discount`, `newarrived`, `rating`, `categoryid`) VALUES
(29, 'Calathea Makoyana', 'The Peacock plant, also known as Calathea makoyana, is a beautiful tropical houseplant, famed for its beautiful, contrasting green and purplish-red leaves that will brighten up any indoor living space. They do take a little bit of work to keep in good condition, so read on to learn all you need to know about peacock plant care.\r\n\r\nPeacock plants are native to the tropical forests of Brazil, where they grow in the understory of the forest, where there is limited direct light at ground level. Because of this, they have lower light requirements than many other houseplants.\r\n\r\nPeacock plants will thrive in homes with higher humidity especially where humidity levels are upwards of 60%. They do well in bathrooms if they can receive enough sunlight. The warm, moist air is reminiscent of their rainforest origins.', 'If you find a burnt tip or edge on your Calathea Freddie, frequently with a yellowish tint, it might be caused by your tap water. Use distilled water, or rainfall, or let your tap water stand overnight before watering to let the chlorine and fluoride dissipate. To maintain your plant healthy and thriving, remove any yellow leaves. Instead of using shine agents on the leaves, wipe them with a wet towel on a regular basis.', '30cm-50cm', 27.00, 20, 1, 0, 4.8, 2),
(30, 'White Crown Cactus', 'Meet the cactus, a symbol of endurance and strength in the plant kingdom. Native to arid regions, this unique plant has evolved to thrive in challenging environments, storing water in its thick, fleshy stems to survive long periods of drought. Its spines, while often seen as a defense mechanism, also play a crucial role in shading the plant and reducing water loss. Beyond its survival tactics, the cactus boasts a variety of shapes, sizes, and colors, making it a versatile addition to any home or garden. Whether you\'re a seasoned gardener or a plant novice, the cactus offers a low-maintenance option that brings a touch of the desert\'s mystique to your space. Embrace the beauty and resilience of the cactus, and let it be a daily reminder of nature\'s incredible adaptability.', 'If you want to grow your Cactus Coroa in a container indoors, make sure to provide it with the right growing conditions. But before that, you will need to learn how to transfer it into a container. Firstly, place some gravel into the bottom of your plant pot and fill it with a potting mix, which is specifically made for succulents. However, Like other succulents, coroa plant is prone to developing mealybug. Therefore, if you notice yours has stopped growing, it is highly likely that your plant has become a home to pests. Fertilize your plant a few times each year during the growing season. Decrease or wipe out fertilizer throughout the colder time of year.', '15cm-20cm', 19.00, 25, 1, 1, 0.0, 2),
(31, 'Philodendron White Princess', 'A hugely popular philodendron, with its bold dark green-burgundy leaves with large splashes and speckles of bright pink. It is a stand out plant, especially as it matures and the leaves grow larger. New leaves emerge a dark olive-green, maturing to a dark burgundy-green with pink tones. The white princess is a natural climber, and needs a trellis, stake or moss pole to climb up, which also encourages large, healthy new leaves. Develops best pink colouring in bright indirect light. Requires a free draining mix. Allow surface to dry between watering, keep a little on the drier side in the winter.', 'Philodendron White Princess for the plant to thrive, grow in a bright spot with indirect light in a pot that has moist, well draining soil. Water when the top 1-inch (2.5 cm) of soil is dry. Keep humidity medium-high, the temperature between 60°F and 84°F (16°C – 29°C), fertilize once a month and prune stem to encourage bushy growth.', '30cm-50cm', 80.00, 5, 0, 1, 0.0, 2),
(32, 'Mandarin Lemon', 'The fragrant flowers and juicy fruits make citrus trees a desirable addition to home gardens. Growing a lemon tree isn’t that difficult. As long as you provide their basic needs, growing lemons can be a very rewarding experience.While lemon trees can tolerate a range of soils, including poor soil, most prefer well-drained, slightly acidic soil. Lemon trees should be set slightly higher than ground. Therefore, dig a hole somewhat shallower than the length of the root ball. Place the tree in the hole and replace soil, tamping firmly as you go. Water sufficiently and add some mulch to help retain moisture. Lemon trees require deep watering once weekly. If necessary, pruning may be done to maintain their shape and height. ', 'With their vibrant citrus flavor, Mandarin lemons are delightful additions to any garden. These small, round fruits also pack a punch of sweetness and tanginess, making them a popular choice for home growers. Mandarin lemon plants thrive in sunlight, so choose a sunny spot for planting. Ensure they receive at least 6–8 hours of sunlight daily. Placing them in a sunny location helps the plants produce juicy and flavorful fruits. For optimal growth, plant your lemon plant in well-draining soil. A mix of potting soil and sand works well. Moreover, good drainage prevents waterlogged roots, keeping your plant healthy and happy.', '120cm-150cm', 160.00, 4, 1, 1, 0.0, 3),
(33, 'Guzmania', 'Guzmania (tufted airplant) is a genus of over 120 species of flowering plants in the botanical family Bromeliaceae, subfamily Tillandsioideae.They are mainly stemless, evergreen, epiphytic perennials native to Florida, the West Indies, southern Mexico, Central America, and northern and western South America. Growing guzmania conifera is simple and their unique growth habit and flower bracts will add interest to the home year round.', 'Guzmania houseplant care is easy, which adds to this plant’s popularity. Guzmanias require low light and should be kept out of direct sunlight. Place distilled or filtered water in the central cup of the plant and replace frequently to keep it from rotting. Keep the potting mix moist during the spring and the summer months. Guzmanias thrive in temperatures of at least 55 F. (13 C.) or higher. Because these are tropical plants, they benefit from high humidity. A light mist daily will keep your guzmania looking its best. Add a balanced fertilizer every two weeks during the spring and summer and a slow release fertilizer at the end of the summer.', '30cm-50cm', 22.00, 12, 0, 1, 0.0, 1),
(34, 'Marigold African Seeds', 'African Marigold bears flowers with double set of rows of petals in bright yellow shades, making the flower wonderfully fluffy. These flowers are taller than most other marigold varieties and look far more dramatic and appealing as tall bedding in your landscape. These will add variety to your marigold bedding and protect your garden from insects as well.', 'The seeds have a successful germination rate of over 70%, the seeds in the packet can be used for several sowings. Follow the sowing instructions on the back of the packet.', '20cm-30cm', 2.00, 14, 0, 0, 0.0, 4),
(35, 'Okra Nano F1 Seeds', 'Test your soil with a kit from the garden center to determine your garden bed\'s pH level. If it is higher than 6.8, use sulfur or peat moss to lower it to the slightly acidic level okra prefers. If it is lower than 6.0, use limestone to raise the pH level so that it is not overly acidic. Work a 3-inch layer of compost into the top 6 to 8 inches of soil to add fertility and improve drainage.Sow lady\'s finger seeds in early summer, at least four weeks after all danger of frost has passed. Night temperatures should not fall below 60 degrees Fahrenheit. Plant them at a depth of 0.5 inch, at a spacing of 6 inches apart within rows. Rows should be at least 24 inches apart. Water the planting area lightly.', 'Begin picking okra about 50 days after sowing seeds, after the plants\' petals fall. To decrease the pods\' tendency to have a \"slimy\" texture, they should be no longer than 4 inches at harvest. Remove the pods with about 1 inch of stem attached, using sharp pruning shears.Continue harvesting lady\'s fingers as long as the plants continue to produce pods, which can be up to a year in warm climates. If you harvest every other day, you\'ll keep the plants producing -- otherwise, over-ripened pods will signal the plants to stop bearing okra.', '5cm-15cm', 4.00, 20, 1, 0, 0.0, 5),
(36, 'European Cherry Seeds', 'Most cherry species are native to the Northern Hemisphere. Some 10 to 12 species are recognized in North America and a similar number in Europe. The greatest concentration of species, however, appears to be in eastern Asia. The native habitat of the species from which the cultivated cherries came is believed to be in western Asia and eastern Europe from the Caspian Sea to the Balkans. Cherries are grown in all areas of the world where winter temperatures are not too severe and where summer temperatures are moderate. They require winter cold in order to blossom in spring. The trees bloom quite early in the spring, just after peaches and earlier than apples.', 'Cherry pits are not ready to plant immediately. They need to go through a cold period called stratification. To do this, mix them in their container with moist peat moss or sand, and put them in the refrigerator for about 10 weeks. Make sure not to expose them to fruits that produce ethylene gas, such as apples and bananas. Different varieties of cherry may require different periods of stratification.\r\nWhy are you doing this? Cherry seeds need to go through a cold or stratification period that normally occurs naturally during the winter, prior to germination in the spring. Refrigerating the pits is artificially mimicking this process.\r\nOkay, seed planting of cherry trees is now ready to commence.', '15cm-20cm', 3.00, 4, 0, 0, 0.0, 6),
(38, 'Tiny Terracotta Pot', 'Tiny Terracotta Pot is a superior quality item. You can decorate it with flowers and green plants inside your home, nursery, balcony, and any place to add a bit of nature around you. Fiber Poly Pots are produced using raw or recycled materials and are biodegradable. ', 'An example photo gives a valid picture of a pot with good care. This is an example, so variations are possible. Finally, the delivered pot may vary in colour shade and size. The actual item may vary from the image shown here due to high image quality.', '3cm-5cm', 4.00, 23, 1, 0, 4.3, 7),
(39, 'Peace Lily', 'Peace lilies (Spathiphyllum), also known as closet plants, are a popular choice for offices and homes. When it comes to indoor plants, peace lily plants are some of the easiest to care for. But, while peace lily plant care is easy, proper growing conditions are still important.\r\nPeace lilies are one of the best air filtering indoor plant. The NASA Clean Air Study found that Spathiphyllum cleans indoor air of certain environmental contaminants, including benzene, and formaldehyde.', 'Spathiphyllum Vivaldi | Peace Lily are generally grown in the ground outdoors only in tropical regions such as Florida or Hawaii elsewhere, they are grown only as potted plants. If you have potted Spathiphyllum Vivaldi | Peace Lily, you can move them outside during the summer months but once temperatures dip, bring them back inside. When grown in pots, soil for a Spathiphyllum Vivaldi | Peace Lily must be kept moist but not soggy, which will cause the leaves to turn yellow. Avoid direct sunlight, but do give them lots of bright filtered light. They like warm conditions and will react badly if exposed to temperatures below 40 degrees Fahrenheit.', '30cm-50cm', 130.00, 10, 0, 1, 0.0, 1),
(40, 'Mini Phalaenopsis Orchid', 'Orchids are a hugely popular houseplant and for very good reasons. Their flowers are stunning and they grow really well in indoor climates. Many people assume that such a delicate and beautiful plant must be hard to care for. In fact, the opposite is true. Many orchids are easy to care for. Phalaenopsis orchids are a wonderful alternative to a bunch of flowers to brighten up your home, as the flowers last for months at a time, and the plants can be kept for years, re-flowering many many times.', 'Place your orchid in a bright room, but not in direct sunlight. Water the orchid infrequently, being guided by the plant, rather than watering on a schedule. Many more orchids die from over-watering than under-watering. Only water the roots. Keep the flowers and leaves dry. Use a well-draining pot and growing media and never let your orchid sit in water for more than a few minutes. Use a water-soluble fertilizer. Fertilize every 1-2 weeks, but not during the flowering phase.', '30cm-50cm', 24.00, 34, 1, 0, 4.0, 1),
(41, 'Fire Red Pencil Cactus', 'Euphorbia tirucalli, commonly known as Pencil Cactus, Milk Bush, or Firestick Plant, is a distinctive succulent shrub or small tree belonging to the Euphorbiaceae family. Here\'s a description and care guide for Euphorbia tirucall.  Pencil Cactus has a unique appearance, featuring upright, pencil-thin stems that resemble the shape of pencils or sticks. The stems are green, but in bright sunlight, they can take on orange or red hues, especially at the tips. The plant has tiny, inconspicuous leaves that are shed early, and the photosynthesis primarily occurs in the stems. Pencil Cactus produces small, greenish-yellow flowers, but they are not particularly showy. The main ornamental appeal comes from the slender, colorful stems.', ' Pencil Cactus thrives in full sunlight. Provide it with at least 6 hours of direct sunlight daily for optimal growth. It can tolerate partial shade but may not display vibrant coloration. This succulent is well-suited for warm climates and does not tolerate frost. It is best grown in USDA hardiness zones 9-11. Pencil Cactus is drought-tolerant and prefers dry conditions. Water sparingly, allowing the soil to dry out between waterings. Overwatering can lead to root rot. Plant in well-draining soil. A cactus or succulent mix is suitable. Good drainage is crucial to prevent waterlogging.  Pencil Cactus doesn\'t require frequent fertilization. Feed it with a balanced, diluted, liquid fertilizer during the growing season (spring to early fall).', '20cm-30cm', 15.00, 8, 0, 1, 0.0, 3),
(42, 'Japanese Camellia', 'Camellia japonica, commonly known as Japanese camellia, is a beautiful evergreen shrub or small tree known for its stunning and often fragrant flowers. Here\'s a description of Camellia japonica: The most notable feature of Camellia japonica is its large and showy flowers. They come in a variety of forms, including single, semi-double, and double, with colors ranging from white and pink to red and even variegated. The flowers can be quite intricate and may resemble roses or peonies. The dark green, glossy leaves of Camellia japonica are leathery and elliptical in shape. The foliage provides an attractive backdrop to the colorful flowers and remains on the plant year-round. The size of Camellia japonica can vary depending on the cultivar and growing conditions. They typically reach a height of 6 to 12 feet (1.8 to 3.6 meters), but some varieties may grow taller. Camellia japonica has a dense, upright to spreading habit. It forms a well-branched structure with a symmetrical shape.', 'Plant Camellia japonica in partial shade to protect it from intense afternoon sunlight. Morning sunlight or dappled shade is ideal. Avoid planting in full sun, especially in warmer climates. Japanese camellias prefer moderate temperatures and are well-suited for USDA hardiness zones 7-9. They may need protection from frost, especially for the buds and flowers. Provide well-draining, slightly acidic to neutral soil. Camellias are sensitive to overly alkaline soils, so amending the soil with organic matter like compost or well-rotted leaves can be beneficial. Keep the soil consistently moist, especially during dry periods. Water deeply, and mulch around the base of the plant to retain moisture and regulate soil temperature. Feed Camellia japonica with a balanced, acidic fertilizer formulated for acid-loving plants. Apply in late winter or early spring before new growth begins. Follow the recommended dosage on the fertilizer package.', '60cm-80cm', 79.00, 6, 1, 0, 4.7, 3),
(43, 'Ehretia Microphylla Buxus', 'The Fukien Tea is originally from China and it was named after the province Fukien, in Chinese Fuijan. It is also endemic in parts of Japan, Indonesia, Taiwan and Australia. The Fukien Tea is still very popular for Penjing in China and in Western countries it is a common indoor Bonsai tree.Its small dark-green shiny leaves have tiny white dots on the upper side and are covered with hairs underneath. Small white flowers can appear all year round and sometimes produce small yellow-red to dark berries.', 'The Fukien Tea is an indoor Bonsai which can only be kept outside all year in very warm climates. It needs a lot of light . In addition to the few hours of daylight there is the problem of dry air. You can use a plant lamp if necessary and put a large tray filled with wet gravel or foamed clay under the pot for more humidity. Keep the tree moist, as it doesn’t like droughts. But be careful not to water too often because it doesn\'t like soil wetness either. As soon as the soil surface gets dry the tree needs to be watered generously but it must not be left standing in excess water. Liquid fertilizers can be used in carefully measured dosage and only on moist soil.', '60cm-80cm', 125.00, 3, 1, 1, 0.0, 3),
(44, 'Butterfly Flower Pea Seeds', 'Clitoria ternatea is easy to grow from seed. In spring, prepare the seeds by lightly scratching the surface with a nail file and soak them overnight in water. The next day, sow them either direct or raise as seedlings by planting 2cm deep. Raise seedlings to protect them from being eaten by birds or destroyed by other critters. If planting in the ground, for protection, cover the spot with the top half of a clear plastic bottle with the lid removed.The seeds generally take two to three weeks to germinate. Once the seedlings have grown to at least 4-5cm in height, transplant them in the ground, against a wall or a trellis in a position that gets full sun or part shade. It takes 90 days until the plant begins to flower. It may seem slow at first, but once the flowers start forming, it is prolific and continues flowering from early summer until late autumn.', 'Very little maintenance is required for this plant. Keep it moist while it’s growing, but don’t overwater. Once it’s established, it becomes drought tolerant. As a legume, its nitrogen fixing capabilities means it needs little fertiliser. If you’ve prepared the soil beforehand, as is my preference, it doesn’t need additional fertiliser. Furthermore, the vine seems to be fairly resistant to garden pests.', '15cm-20cm', 3.00, 11, 1, 0, 0.0, 4),
(45, 'English Lavender Seeds', 'Lavandula angustifolia is thought to be the true English Lavender. Also called True Lavender or Fine Lavender, it is thought to be the best Lavender for medicinal and aromatherapy purposes. This evergreen is a staple plant for the herb garden, the fragrant flowers have been used in perfumes, poultices and potpourris for centuries.Lavender is an excellent plant for low informal hedging and as a specimen evergreen for borders and formal gardens. The plants grow to about 45 to 60cm (18 to 24in) tall and flowering generally begins from mid to late June to early July. The flowers have a rich sweet scent and are highly attractive to bees and other beneficial insects.The plants tolerate acid soil but favours neutral to alkaline soils. They are drought resistant and cold hardy and remain attractive well into winter. For best effect plant it by doors and paths, where it’s delightful scent can be fully appreciated.', 'Late winter to late spring (February to April) or sow in late summer to autumn (August to Oct)Lavender can be sown at anytime of year but prefers the ground temperature to be around 13 to 18°C (55 to 65°F). Sow seed on the surface of a well drained, seed compost in pots or trays. Cover seed with a light sprinkling of compost or vermiculite. Keep at a temperature of between 15 to 20°C (59 to 68°F). Germination in 21 to 90 days.When large enough to handle, transplant seedlings into 7.5cm (3in) pots. Acclimatise to outdoor conditions for 10 to 15 days before planting out after all risk of frost, 45cm (18in) apart. For best results, provide any ordinary, well-drained soil in full sun. Lavenders do best in moderately fertile, well-drained, alkaline soils in full sun. Once established they thrive on poor, dry, stony soils, but do not tolerate water logging. In poorly-drained soils plant on a mound or, in the case of hedging, on a ridge which will keep the base of the plants out of saturated soil. On heavier soils consider adding large quantities of gravel to improve drainage. It will grow in slightly acid soils.Adequate spacing is essential to provide good air circulation. For informal plantings allow up to 90cm (36in). Where grown as a hedge, plant about 30cm (12in) apart or 45cm (18in) apart for taller cultivars. Prune back to encourage bushy growth. Although lavenders are drought-tolerant, they need watering until established. Avoid high-nitrogen fertilisers.Lavenders grow well in containers but are deep rooted and need large pots with a diameter of 30 to 45cm (12 to 18in). Use a loam-based compost such as John Innes No. 3 with added coarse grit for drainage and a controlled-release fertiliser. Plants will need regular watering in summer, but should be kept on the dry side over winter.', '5cm-15cm', 4.00, 4, 0, 1, 0.0, 4),
(46, 'Orange Carrot Seeds', 'Because carrot seeds are tiny, they need to be sown shallowly. The trick is to keep the top-most layer of soil damp during the long germination period. Water deeply prior to planting. Direct sow the tiny seeds 5mm (¼”) deep, 4 seeds per 2cm (1″), and firm soil lightly after seeding. Make sure the seeds are only just buried. Water the area with the gentlest stream you can provide, and keep it constantly moist until the seeds sprout.', 'Ideal pH: 6.0-6.8. The softer and more humus-based the soil, the better. When soil is dry enough in spring, work it to a fine texture. Broadcast and dig in ½ cup complete organic fertilizer for every 3m (10′) of row. Avoid fresh manure. Carrots will become misshapen, but still edible if they hit anything hard as they grow down into the soil. Keep weeded and watered.\r\nIt is very important to thin carrots in order to allow them room to grow, and so they don’t compete for available nutrients, moisture, and light. Then to 4-10cm (1½-4″) when the young plants are 2cm (1″) tall. Use wider spacing to get larger roots. As they grow, carrots push up, out of the soil, so hill soil up to prevent getting a green shoulder.\r\nCarrots can be harvested at any size, but flavour is best when the carrot has turned bright orange. After harvest, store at cold temperatures just above 0ºC. You can store in sand or sawdust, or simply leave carrots under heaped soil in the garden during the winter, and pull as you need them.\r\nPlant with bean seeds, Brassicas, chives, leeks, lettuce, onions, peas, peppers, pole beans, radish, rosemary, sage, and tomatoes. Avoid planting with dill, parsnips, and potatoes. Carrots planted near tomatoes may have stunted roots, but will have exceptional flavour. Chives also benefit carrots.', '5cm-15cm', 3.00, 5, 0, 1, 0.0, 5),
(47, 'Black Carrot F1 Hybrid Seeds', 'Because carrot seeds are tiny, they need to be sown shallowly. The trick is to keep the top-most layer of soil damp during the long germination period. Water deeply prior to planting. Direct sow the tiny seeds 5mm (¼”) deep, 4 seeds per 2cm (1″), and firm soil lightly after seeding. Make sure the seeds are only just buried. Water the area with the gentlest stream you can provide, and keep it constantly moist until the seeds sprout.', 'Ideal pH: 6.0-6.8. The softer and more humus-based the soil, the better. When soil is dry enough in spring, work it to a fine texture. Broadcast and dig in ½ cup complete organic fertilizer for every 3m (10′) of row. Avoid fresh manure. Carrots will become misshapen, but still edible if they hit anything hard as they grow down into the soil. Keep weeded and watered.\r\nIt is very important to thin carrots in order to allow them room to grow, and so they don’t compete for available nutrients, moisture, and light. Then to 4-10cm (1½-4″) when the young plants are 2cm (1″) tall. Use wider spacing to get larger roots. As they grow, carrots push up, out of the soil, so hill soil up to prevent getting a green shoulder.\r\nCarrots can be harvested at any size, but flavour is best when the carrot has turned bright orange. After harvest, store at cold temperatures just above 0ºC. You can store in sand or sawdust, or simply leave carrots under heaped soil in the garden during the winter, and pull as you need them.\r\nPlant with bean seeds, Brassicas, chives, leeks, lettuce, onions, peas, peppers, pole beans, radish, rosemary, sage, and tomatoes. Avoid planting with dill, parsnips, and potatoes. Carrots planted near tomatoes may have stunted roots, but will have exceptional flavour. Chives also benefit carrots.', '5cm-15cm', 14.00, 5, 0, 1, 0.0, 5),
(48, 'Strawberry Seeds', 'Strawberries can be everbearers, meaning they provide fruit to harvest all season long. Or they can be summer-fruiting, having one big harvest time.\r\nTo encourage the best growth from your plants, provide well-draining soil fed with organic compost or fertilizer. Also, adding a layer of mulch around your plants can help to block out weeds that would compete with your strawberries. Pull weeds as soon as you spot them, and prune off yellowed or browning leaves from the strawberry plants. This helps a plant get as much moisture and nutrients to the healthy leaves and fruits as it can, giving you a better harvest.', 'One major benefit of growing strawberries from seed is you can plant several different varieties of your choosing, as long as they can grow in your climate. But a drawback is you likely won\'t have a good harvest of fruit for a year after planting. This is certainly a case where good things come to those who wait.\r\n\r\nStrawberry plants can go almost anywhere. From indoor potted plants to outdoor patches and interplanted areas that need ground cover, strawberries aren’t picky. They also don’t grow very deep roots. So if you can find a spot for a container of any sort or designate a section of the garden, you probably can put strawberries there.', '5cm-15cm', 3.00, 9, 1, 0, 0.0, 6),
(49, 'Lychee Seeds', 'Lychee is a tropical fruit that grows on the evergreen lychee tree (Litchi chinensis), native to southern China. However, many different cultivars are grown worldwide, from the Indian subcontinent to Hawaii. Lychee fruits have a bright red, fibrous, and scaly outer shell, which you can easily peel away to reveal a soft, lightly tart fruit surrounding a single large seed. Lychees grow in clusters, with anywhere from three to fifty fruits per bunch. You can find fresh lychees in most Asian markets and some grocery stores.\r\nSoak the seed for three days. Gently rinse the seed and then pat it with paper towels. Soak the seed in a small bowl filled with warm water. The seed will need to sit for three days, which helps with germination. Replace the water each day for freshness, and when the exterior of the seed begins to crack, it’s time to plant.', 'Plant the lychee seedling. Get a container with drainage holes that’s almost a foot tall and fill it with potting soil. The soil should be slightly acidic. Bury the seed fully, about an inch into the soil. You’ll want to mimic a subtropical environment for growing lychee trees: wet and warm. Keep the pot in a warm room that stays in the seventies, but keep your growing lychee plant away from direct sunlight. In the early stages of growth, shade is best.\r\nWater and relocate your plant. Lychee trees need more water than the average houseplant. Give it water every other day, checking the soil to see how moist it is. When green lychee leaves begin to poke out of the soil, move your pot toward more direct sunlight.\r\nWatch your plant’s growth. Lychee trees can grow quite tall—up to ten feet—so you’ll want to prune by cutting branches and leaves from the top to keep it in check. After your first year, repot your plant, giving it a larger home. As it gets bigger, your plant will need more sunlight; because of this, lychee plants tend to grow best outdoors in direct sunlight. As evergreen trees accustomed to tropical climates, lychees grow best in hardiness zones ten and eleven.', '5cm-15cm', 3.00, 6, 1, 0, 0.0, 6),
(50, 'Vaselife Cut Flower Food', 'Vaselife Universal Pro II Bucket Bag is to be used by bouquet makers and retailers during transport, storage, processing and display. This product has been designed and optimized to work on all flower types (except Anthuriums) in such a way that the flowers receive enough nutrients to keep them healthy but not enough to cause rapid flower opening.', 'Provides the necessary amount of nutrition required during the transport & display stage.\r\nStimulates water uptake, keeping the flowers and foliage in optimum condition.\r\nLowers the pH of the water to the optimal level of between 3.5 and 5.5.\r\nIncreases the vase life of your flowers.\r\nEnsures color retention for the flowers.Dose rate is 1 bucket bag per 1, 1.5 or 2 litre of water. Remove foliage from the section of the flower that will be below the water level and make sure to cut the flowers with a sharp and disinfected cutting tool.  Only use acid resistant materials with the solution. Do not use it in combination with metal materials (iron, zinc, copper or tin).', '5cm-15cm', 1.00, 20, 0, 1, 0.0, 7),
(51, 'Folikraft Plant Fungicide', 'Ready to use, Plant Fugicide from FOLIKRAFT is the breakthrough range of gardening product effective in controlling a wide range of fungal diseases. Controls/Stops and Prevents over 75 diseases including Tomato Blight, Anthracnose, Fusarium Wilt, Mold, Powdery Mildew, and more', 'Shake the bottle before use. This ready to use mix can be directly applied on the base of each plant until the soil is completely wet. Use on all ornamental and tropical indoor & outdoor plants, Fruit & Veg plants all around the house and garden. Has systemic activity and does not leave any residue on plants or soil. Is both fungicidal and fungi-static in nature. Prevent, stop or control active fungal diseases on vegetables, fruits, roses, flowers, shrubs, ornamental trees and conifers. It\'s rain-proof protection you can trust.', '15cm-20cm', 16.00, 20, 1, 1, 4.3, 7),
(52, 'Kalanchoe', 'Kalanchoe is a genus of about 125 species of tropical, succulent flowering plants in the family Crassulaceae, mainly native to Madagascar and tropical Africa. Kalanchoe was one of the first plants to be sent into space, sent on a resupply to the Soviet Salyut 1 space station in 1971. Kalanchoes are characterized by opening their flowers by growing new cells on the inner surface of the petals to force them outwards, and on the outside of the petals to close them. Kalanchoe flowers are divided into 4 sections with 8 stamens. These plants are cultivated as ornamental houseplants and rock or succulent garden plants.', 'Kalanchoe | Widows Thrill care is minimal but be cautious about light levels. Intense sunlight can burn the tips of the leaves. Place pots in partial sun to light shade areas when growing Kalanchoes. The flowering varieties are highly rewarding for their colorful and long-lasting flowers. They prefer bright, sunny locations, especially in the growing season. Water moderately from fall to winter when the growth is most active. Reduce watering during the hottest summer months when the plants are mostly dormant and winter when the growth slows down significantly.', '20cm-29cm', 115.00, 20, 0, 0, 0.0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `productimage`
--

CREATE TABLE `productimage` (
  `imgid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productimage`
--

INSERT INTO `productimage` (`imgid`, `productid`, `url`) VALUES
(33, 29, 'assets/img/products/Thursday-13th-June-2024-1978105027.jpg'),
(34, 30, 'assets/img/products/Thursday-13th-June-2024-961478200.jpg'),
(35, 31, 'assets/img/products/Thursday-13th-June-2024-876349493.jpg'),
(36, 32, 'assets/img/products/Thursday-13th-June-2024-1349507186.jpg'),
(37, 33, 'assets/img/products/Thursday-13th-June-2024-1671935777.jpg'),
(38, 34, 'assets/img/products/Thursday-13th-June-2024-406953728.jpg'),
(39, 35, 'assets/img/products/Thursday-13th-June-2024-1259539089.jpg'),
(40, 36, 'assets/img/products/Thursday-13th-June-2024-583611367.jpg'),
(42, 38, 'assets/img/products/Thursday-13th-June-2024-939026530.jpg'),
(43, 29, 'assets/img/products/Thursday-13th-June-2024-2115380370.jpg'),
(44, 29, 'assets/img/products/Thursday-13th-June-2024-1794103712.jpg'),
(47, 29, 'assets/img/products/Thursday-13th-June-2024-756473319.jpg'),
(48, 30, 'assets/img/products/Thursday-13th-June-2024-769849000.jpg'),
(49, 30, 'assets/img/products/Thursday-13th-June-2024-1498748580.jpg'),
(50, 30, 'assets/img/products/Thursday-13th-June-2024-1396189917.png'),
(51, 31, 'assets/img/products/Thursday-13th-June-2024-1238701878.jpg'),
(52, 31, 'assets/img/products/Thursday-13th-June-2024-1368289346.jpg'),
(53, 32, 'assets/img/products/Thursday-13th-June-2024-1868692504.jpg'),
(54, 32, 'assets/img/products/Thursday-13th-June-2024-1542619312.jpg'),
(55, 33, 'assets/img/products/Thursday-13th-June-2024-1107928505.jpg'),
(56, 33, 'assets/img/products/Thursday-13th-June-2024-290432272.jpg'),
(57, 34, 'assets/img/products/Friday-14th-June-2024-719746514.jpeg'),
(58, 34, 'assets/img/products/Friday-14th-June-2024-1106800427.jpeg'),
(59, 35, 'assets/img/products/Friday-14th-June-2024-492599194.jpeg'),
(60, 36, 'assets/img/products/Friday-14th-June-2024-1089686993.jpg'),
(61, 36, 'assets/img/products/Friday-14th-June-2024-691078720.jpg'),
(62, 38, 'assets/img/products/Friday-14th-June-2024-1429706564.jpg'),
(63, 38, 'assets/img/products/Friday-14th-June-2024-2001079171.jpg'),
(64, 39, 'assets/img/products/Friday-14th-June-2024-83145892.jpg'),
(65, 39, 'assets/img/products/Friday-14th-June-2024-1474958576.jpg'),
(66, 39, 'assets/img/products/Friday-14th-June-2024-272813548.jpg'),
(67, 40, 'assets/img/products/Friday-14th-June-2024-1588588021.jpg'),
(68, 40, 'assets/img/products/Friday-14th-June-2024-2069805673.jpg'),
(69, 40, 'assets/img/products/Friday-14th-June-2024-1284864633.jpg'),
(70, 40, 'assets/img/products/Friday-14th-June-2024-796073789.jpg'),
(71, 41, 'assets/img/products/Friday-14th-June-2024-1598017123.jpg'),
(72, 41, 'assets/img/products/Friday-14th-June-2024-1030893709.jpg'),
(73, 41, 'assets/img/products/Friday-14th-June-2024-832627688.jpg'),
(74, 42, 'assets/img/products/Friday-14th-June-2024-1813411203.jpg'),
(75, 42, 'assets/img/products/Friday-14th-June-2024-1450135574.jpg'),
(76, 43, 'assets/img/products/Friday-14th-June-2024-380490383.jpg'),
(77, 43, 'assets/img/products/Friday-14th-June-2024-1164059239.jpg'),
(78, 43, 'assets/img/products/Friday-14th-June-2024-1176515939.jpg'),
(79, 44, 'assets/img/products/Friday-14th-June-2024-671368124.jpg'),
(80, 44, 'assets/img/products/Friday-14th-June-2024-1452456613.jpg'),
(81, 44, 'assets/img/products/Friday-14th-June-2024-1712117776.jpg'),
(82, 45, 'assets/img/products/Friday-14th-June-2024-956818203.jpg'),
(83, 45, 'assets/img/products/Friday-14th-June-2024-44959650.jpg'),
(84, 45, 'assets/img/products/Friday-14th-June-2024-175175394.jpg'),
(85, 46, 'assets/img/products/Friday-14th-June-2024-236787766.jpg'),
(86, 46, 'assets/img/products/Friday-14th-June-2024-788677834.jpg'),
(87, 46, 'assets/img/products/Friday-14th-June-2024-1787048215.jpg'),
(88, 46, 'assets/img/products/Friday-14th-June-2024-1980310017.jpg'),
(89, 47, 'assets/img/products/Friday-14th-June-2024-1548256877.jpg'),
(90, 47, 'assets/img/products/Friday-14th-June-2024-411461306.jpg'),
(91, 47, 'assets/img/products/Friday-14th-June-2024-1853930574.jpg'),
(92, 48, 'assets/img/products/Friday-14th-June-2024-450431404.jpg'),
(93, 48, 'assets/img/products/Friday-14th-June-2024-597757152.jpg'),
(94, 48, 'assets/img/products/Friday-14th-June-2024-1378526122.jpeg'),
(95, 48, 'assets/img/products/Friday-14th-June-2024-1879518709.png'),
(96, 49, 'assets/img/products/Friday-14th-June-2024-1911325090.jpg'),
(97, 49, 'assets/img/products/Friday-14th-June-2024-819175301.jpg'),
(98, 49, 'assets/img/products/Friday-14th-June-2024-1646679249.jpg'),
(99, 50, 'assets/img/products/Friday-14th-June-2024-1462451224.jpg'),
(100, 50, 'assets/img/products/Friday-14th-June-2024-111696354.jpg'),
(101, 51, 'assets/img/products/Friday-14th-June-2024-527618910.jpg'),
(102, 52, 'assets/img/products/Monday-24th-June-2024-1168990217.jpg'),
(103, 52, 'assets/img/products/Monday-24th-June-2024-1564339706.jpg'),
(104, 52, 'assets/img/products/Monday-24th-June-2024-987252197.jpg'),
(105, 52, 'assets/img/products/Monday-24th-June-2024-2144967533.jpg'),
(106, 52, 'assets/img/products/Monday-24th-June-2024-1099725674.jpg'),
(107, 52, 'assets/img/products/Monday-24th-June-2024-1741396333.jpg'),
(108, 52, 'assets/img/products/Monday-24th-June-2024-1581919123.jpg'),
(109, 52, 'assets/img/products/Monday-24th-June-2024-1525682645.jpg'),
(112, 29, 'assets/img/products/Monday-24th-June-2024-1105357136.jpg'),
(113, 29, 'assets/img/products/Monday-24th-June-2024-2090933894.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `productreview`
--

CREATE TABLE `productreview` (
  `prid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `prcname` varchar(60) NOT NULL,
  `prtitle` varchar(100) NOT NULL,
  `prcontent` text NOT NULL,
  `rating` tinyint(5) NOT NULL,
  `date` date NOT NULL,
  `like` int(11) NOT NULL,
  `unlike` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productreview`
--

INSERT INTO `productreview` (`prid`, `productid`, `customerid`, `prcname`, `prtitle`, `prcontent`, `rating`, `date`, `like`, `unlike`) VALUES
(9, 42, 1, 'Toe Toe', 'Good Plant', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 5, '2024-06-15', 0, 0),
(10, 29, 2, 'PP Chan', 'Excellent Quality', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage.', 4, '2024-06-16', 3, 2),
(11, 40, 2, 'PP Chan', 'Good Quality', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage.', 5, '2024-06-16', 0, 0),
(12, 51, 1, 'Aung Kyaw Myint', 'Beautiful plants', 'I received them in good condition along with care card. Although the spider plant has some bad leaves they will disappear once I take care of them. Overall till now the plants are doing fine. How that I can properly take care of them', 5, '2024-06-17', 0, 0),
(13, 38, 1, 'Aung Aung', 'Received wonderful lush green plant', 'This is my second order with Ugaoo and to my surprise I received a bigger plant than what I received in previous order.\r\nAlongwith the plant size, the associated Krish Pot is also bigger.', 4, '2024-06-17', 0, 0),
(14, 38, 1, 'Kyaw Kyaw', 'Best and Healthy Planats', 'My sister in law send me the 2 plants as gift and believe me they are quite healthy and fresh when arrived. Didn’t expected that online plants can so good quality and packaging was best.', 5, '2024-06-17', 0, 0),
(15, 29, 1, 'Mg Mg', 'Good quality, easy to maintain', 'I bought total 5 indoor plants from this website for the first time...the plants I bought are very fresh and healthy...and the packaging was excellent overall great service....thanks', 5, '2024-06-17', 1, 0),
(16, 40, 1, 'Yin Min', 'Loved everything about the purchase', 'The plant life, condition, the petals flowers everything was perfect. Packaging was amazing too. Loved the purchase.', 4, '2024-06-17', 0, 0),
(17, 51, 1, 'Min Khant', 'Variety, Range and Guidance', 'Never have come across a site that sells plants online and also has so detailed description on how to make them thrive.', 4, '2024-06-17', 0, 0),
(18, 42, 1, 'Shin Thant', 'Beautiful and Low Maintenance', 'I bought peace lily from Ugaoo a few months back and it has grown at such a good rate in mere 2 months! Absolutely loved it! Now waiting for it to bloom', 4, '2024-06-17', 0, 0),
(19, 42, 1, 'Khine Khant', 'A Beautiful and Low-Maintenance Houseplant', 'The peace lily is a stunning and popular houseplant known for its elegant white flowers and glossy, deep green leaves. Not only does it add a touch of beauty to any indoor space, but it also offers a range of benefits that make it a must-have for any plant lover.\r\nAnd Ugaoo provide me a healthy plant thanks for providing this elegant plant.', 5, '2024-06-17', 0, 0),
(20, 40, 1, 'Khant Aung', 'Nice plant', 'Received my peace lily plant. Couple of leaves were broken. It&#039;s beautiful but expected a slightly larger plant. Overall, good buy.', 3, '2024-06-17', 0, 0),
(21, 51, 1, 'Si Thu', 'Plant is not healthy', 'Plant is hot healthy. After two days it looks more bad condition. I repotted them and lets see.', 4, '2024-06-17', 0, 0),
(22, 29, 1, 'Thant Htoo San', 'Excellent', 'Received my plant in a very good condition. It added a fresh charm to my desktop place. Thanks ugaoo for this beautiful fresh plant.', 5, '2024-06-17', 1, 0),
(23, 38, 1, 'Kyar Nyo', 'Plant is very Healthy &amp; Growing Fast', 'This is best place to buy online plants, I bought a Peace lily 2 month back and it is very healthy and growing fast...I bought for my office.', 4, '2024-06-17', 0, 0),
(24, 29, 1, 'Aung Aung', 'Good', 'agetetetet', 5, '2024-06-24', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `rid` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `rcname` varchar(60) NOT NULL,
  `rtitle` varchar(100) NOT NULL,
  `rcontent` varchar(2000) NOT NULL,
  `rating` int(5) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`rid`, `customerid`, `rcname`, `rtitle`, `rcontent`, `rating`, `date`) VALUES
(1, 1, 'Toe Aung', 'Excellent Service', 'I got a pair of boots from store X and I’m very satisfied. They are high-quality and worth the money. The store also offered free shipping at that price so that’s a plus!', 5, '2024-06-02'),
(2, 1, 'Toe Tat Aung', 'Good Service', 'I recently purchased from planti shop], and I couldn’t be happier with my online shopping experience. Their website was user-friendly, making it easy to find the perfect item. The checkout process was smooth, and I received my order promptly. The [product] arrived in excellent condition, exactly as described on their website. I’m thrilled with the quality and will definitely shop at [Store Name] again in the future. Highly recommended!', 5, '2024-06-02'),
(3, 1, 'Toe Tat Aung', 'Quit Delivery', 'I ordered from planti shop last week, and I was amazed at how quickly it arrived. The packaging was secure, ensuring the item was undamaged. The customer service was exceptional, as they kept me updated throughout the entire process. I had a question about the product, and their support team responded promptly and professionally.', 4, '2024-06-02'),
(4, 1, 'Toe Aung Kyaw', 'Good Quality', 'I recently discovered planti shop while searching for a specific plant. Not only did they have the item I was looking for, but their selection was vast, and the prices were competitive. The website was easy to navigate, and the product descriptions were informative and accurate. I was pleasantly surprised by the fast shipping and the care they took in packaging my order.', 4, '2024-06-02'),
(5, 2, 'PP Chan', 'Complaint ', 'No weed to smoke only house plant.', 2, '2024-06-02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`amid`),
  ADD UNIQUE KEY `amemail` (`amemail`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartid`),
  ADD KEY `productid` (`productid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ctgid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`),
  ADD UNIQUE KEY `cemail` (`cemail`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`dpid`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `newarrived`
--
ALTER TABLE `newarrived`
  ADD PRIMARY KEY (`npid`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `orderlines`
--
ALTER TABLE `orderlines`
  ADD PRIMARY KEY (`olid`),
  ADD KEY `orderid` (`orderid`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`odid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`crid`),
  ADD UNIQUE KEY `crnumber` (`crnumber`),
  ADD UNIQUE KEY `ccv` (`ccv`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `productimage`
--
ALTER TABLE `productimage`
  ADD PRIMARY KEY (`imgid`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `productreview`
--
ALTER TABLE `productreview`
  ADD PRIMARY KEY (`prid`),
  ADD KEY `productid` (`productid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `customerid` (`customerid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `amid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ctgid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `dpid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `newarrived`
--
ALTER TABLE `newarrived`
  MODIFY `npid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orderlines`
--
ALTER TABLE `orderlines`
  MODIFY `olid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `odid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `crid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `productimage`
--
ALTER TABLE `productimage`
  MODIFY `imgid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `productreview`
--
ALTER TABLE `productreview`
  MODIFY `prid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`pid`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`customerid`) REFERENCES `customer` (`cid`);

--
-- Constraints for table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `discount_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`pid`);

--
-- Constraints for table `newarrived`
--
ALTER TABLE `newarrived`
  ADD CONSTRAINT `newarrived_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`pid`);

--
-- Constraints for table `orderlines`
--
ALTER TABLE `orderlines`
  ADD CONSTRAINT `orderlines_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `orders` (`odid`),
  ADD CONSTRAINT `orderlines_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`pid`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`cid`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `category` (`ctgid`);

--
-- Constraints for table `productimage`
--
ALTER TABLE `productimage`
  ADD CONSTRAINT `productimage_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`pid`);

--
-- Constraints for table `productreview`
--
ALTER TABLE `productreview`
  ADD CONSTRAINT `productreview_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`pid`),
  ADD CONSTRAINT `productreview_ibfk_2` FOREIGN KEY (`customerid`) REFERENCES `customer` (`cid`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`cid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
