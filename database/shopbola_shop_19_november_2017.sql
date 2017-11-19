-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 19, 2017 at 12:15 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopbola_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `adverts`
--

CREATE TABLE `adverts` (
  `id` int(5) NOT NULL,
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(5) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT 'Title',
  `date_start` date NOT NULL COMMENT 'Start date',
  `date_end` date NOT NULL COMMENT 'End date',
  `category_id` int(2) NOT NULL COMMENT 'Category',
  `advert_type_id` int(1) NOT NULL DEFAULT '1' COMMENT 'Advert Type',
  `file_name_logo` varchar(255) DEFAULT NULL COMMENT 'Logo',
  `enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Enabled'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `advert_types`
--

CREATE TABLE `advert_types` (
  `id` int(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dimensions` varchar(50) NOT NULL DEFAULT '0px x 0px'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `advert_types`
--

INSERT INTO `advert_types` (`id`, `name`, `dimensions`) VALUES
(1, 'Main Carousel', '1200x300 px'),
(2, 'Companies Carousel', '183x94 px'),
(3, 'Sidebar Banners', '160x500 px');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(2) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Tables'),
(2, 'Chairs'),
(3, 'Mirrors'),
(4, 'Paintings'),
(5, 'Other'),
(7, 'Crafts');

-- --------------------------------------------------------

--
-- Table structure for table `col_sizes`
--

CREATE TABLE `col_sizes` (
  `id` int(1) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `col_sizes`
--

INSERT INTO `col_sizes` (`id`, `name`) VALUES
(1, 'col-md-3'),
(2, 'col-md-6'),
(3, 'col-md-9'),
(4, 'col-md-12');

-- --------------------------------------------------------

--
-- Table structure for table `list_countries`
--

CREATE TABLE `list_countries` (
  `id` int(2) NOT NULL,
  `name` varchar(200) NOT NULL COMMENT 'Country'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_countries`
--

INSERT INTO `list_countries` (`id`, `name`) VALUES
(1, 'Afghanistan'),
(2, 'Albania'),
(3, 'Algeria'),
(4, 'Andorra'),
(5, 'Angola'),
(6, 'Antigua and Barbuda'),
(7, 'Argentina'),
(8, 'Armenia'),
(9, 'Australia'),
(10, 'Austria'),
(11, 'Azerbaijan'),
(12, 'Bahamas'),
(13, 'Bahrain'),
(14, 'Bangladesh'),
(15, 'Barbados'),
(16, 'Belarus'),
(17, 'Belgium'),
(18, 'Belize'),
(19, 'Benin'),
(20, 'Bhutan'),
(21, 'Bolivia'),
(22, 'Bosnia and Herzegovina'),
(23, 'Botswana'),
(24, 'Brazil'),
(25, 'Brunei'),
(26, 'Bulgaria'),
(27, 'Burkina Faso'),
(28, 'Burundi'),
(29, 'Cabo Verde'),
(30, 'Cambodia'),
(31, 'Cameroon'),
(32, 'Canada'),
(33, 'Central African Republic (CAR)'),
(34, 'Chad'),
(35, 'Chile'),
(36, 'China'),
(37, 'Colombia'),
(38, 'Comoros'),
(39, 'Democratic Republic of the Congo'),
(40, 'Republic of the Congo'),
(41, 'Costa Rica'),
(42, 'Cote d\'Ivoire'),
(43, 'Croatia'),
(44, 'Cuba'),
(45, 'Cyprus'),
(46, 'Czech Republic'),
(47, 'Denmark'),
(48, 'Djibouti'),
(49, 'Dominica'),
(50, 'Dominican Republic'),
(51, 'Ecuador'),
(52, 'Egypt'),
(53, 'El Salvador'),
(54, 'Equatorial Guinea'),
(55, 'Eritrea'),
(56, 'Estonia'),
(57, 'Ethiopia'),
(58, 'Fiji'),
(59, 'Finland'),
(60, 'France'),
(61, 'Gabon'),
(62, 'Gambia'),
(63, 'Georgia'),
(64, 'Germany'),
(65, 'Ghana'),
(66, 'Greece'),
(67, 'Grenada'),
(68, 'Guatemala'),
(69, 'Guinea'),
(70, 'Guinea-Bissau'),
(71, 'Guyana'),
(72, 'Haiti'),
(73, 'Honduras'),
(74, 'Hungary'),
(75, 'Iceland'),
(76, 'India'),
(77, 'Indonesia'),
(78, 'Iran'),
(79, 'Iraq'),
(80, 'Ireland'),
(81, 'Israel'),
(82, 'Italy'),
(83, 'Jamaica'),
(84, 'Japan'),
(85, 'Jordan'),
(86, 'Kazakhstan'),
(87, 'Kenya'),
(88, 'Kiribati'),
(89, 'Kosovo'),
(90, 'Kuwait'),
(91, 'Kyrgyzstan'),
(92, 'Lao People\'s Democratic Republic'),
(93, 'Latvia'),
(94, 'Lebanon'),
(95, 'Lesotho'),
(96, 'Liberia'),
(97, 'Libya'),
(98, 'Liechtenstein'),
(99, 'Lithuania'),
(100, 'Luxembourg'),
(101, 'Macedonia'),
(102, 'Madagascar'),
(103, 'Malawi'),
(104, 'Malaysia'),
(105, 'Maldives'),
(106, 'Mali'),
(107, 'Malta'),
(108, 'Marshall Islands'),
(109, 'Mauritania'),
(110, 'Mauritius'),
(111, 'Mexico'),
(112, 'Micronesia'),
(113, 'Moldova'),
(114, 'Monaco'),
(115, 'Mongolia'),
(116, 'Montenegro'),
(117, 'Morocco'),
(118, 'Mozambique'),
(119, 'Myanmar (Burma)'),
(120, 'Namibia'),
(121, 'Nauru'),
(122, 'Nepal'),
(123, 'Netherlands'),
(124, 'New Zealand'),
(125, 'Nicaragua'),
(126, 'Niger'),
(127, 'Nigeria'),
(128, 'North Korea'),
(129, 'Norway'),
(130, 'Oman'),
(131, 'Pakistan'),
(132, 'Palau'),
(133, 'Palestine'),
(134, 'Panama'),
(135, 'Papua New Guinea'),
(136, 'Paraguay'),
(137, 'Peru'),
(138, 'Philippines'),
(139, 'Poland'),
(140, 'Portugal'),
(141, 'Qatar'),
(142, 'Romania'),
(143, 'Russia'),
(144, 'Rwanda'),
(145, 'Saint Kitts and Nevis'),
(146, 'Saint Lucia'),
(147, 'Saint Vincent and the Grenadines'),
(148, 'Samoa'),
(149, 'San Marino'),
(150, 'Sao Tome and Principe'),
(151, 'Saudi Arabia'),
(152, 'Senegal'),
(153, 'Serbia'),
(154, 'Seychelles'),
(155, 'Sierra Leone'),
(156, 'Singapore'),
(157, 'Slovakia'),
(158, 'Slovenia'),
(159, 'Solomon Islands'),
(160, 'Somalia'),
(161, 'South Africa'),
(162, 'South Korea'),
(163, 'South Sudan'),
(164, 'Spain'),
(165, 'Sri Lanka'),
(166, 'Sudan'),
(167, 'Suriname'),
(168, 'Swaziland'),
(169, 'Sweden'),
(170, 'Switzerland'),
(171, 'Syria'),
(172, 'Taiwan'),
(173, 'Tajikistan'),
(174, 'Tanzania'),
(175, 'Thailand'),
(176, 'Timor-Leste'),
(177, 'Togo'),
(178, 'Tonga'),
(179, 'Trinidad and Tobago'),
(180, 'Tunisia'),
(181, 'Turkey'),
(182, 'Turkmenistan'),
(183, 'Tuvalu'),
(184, 'Uganda'),
(185, 'Ukraine'),
(186, 'United Arab Emirates (UAE)'),
(187, 'United Kingdom (UK)'),
(188, 'United States of America (USA)'),
(189, 'Uruguay'),
(190, 'Uzbekistan'),
(191, 'Vanuatu'),
(192, 'Vatican City (Holy See)'),
(193, 'Venezuela'),
(194, 'Vietnam'),
(195, 'Yemen'),
(196, 'Zambia'),
(197, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `list_sex`
--

CREATE TABLE `list_sex` (
  `id` int(2) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_sex`
--

INSERT INTO `list_sex` (`id`, `name`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `list_visibility`
--

CREATE TABLE `list_visibility` (
  `id` int(2) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_visibility`
--

INSERT INTO `list_visibility` (`id`, `name`) VALUES
(1, 'visible'),
(2, 'hidden');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `quote_id` int(10) NOT NULL COMMENT 'Quote ID',
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` float NOT NULL COMMENT 'Amount',
  `paypal_paymentid` varchar(255) NOT NULL COMMENT 'PayPal Payment Id',
  `paypal_payer_email` varchar(255) NOT NULL COMMENT 'Payer Email'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`quote_id`, `entrydate`, `amount`, `paypal_paymentid`, `paypal_payer_email`) VALUES
(24, '2017-11-18 18:14:30', 1.5, 'PAY-3DA82950RT4662238LIIG7PA', 'william@sengdarait.com');

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(10) NOT NULL,
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hash` varchar(255) NOT NULL COMMENT 'Customer hash',
  `date_quote_sent` varchar(30) DEFAULT 'Pending' COMMENT 'Date quote sent',
  `date_quote_accepted` varchar(30) DEFAULT 'Pending' COMMENT 'Date quote accepted',
  `date_payment_made` varchar(30) DEFAULT 'Pending' COMMENT 'Date payment made',
  `sales_tax` float DEFAULT '0' COMMENT 'Sales Tax',
  `date_item_shipped` varchar(30) DEFAULT 'Pending' COMMENT 'Date item shipped',
  `date_item_received` varchar(30) DEFAULT 'Pending' COMMENT 'Date item received'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `entrydate`, `hash`, `date_quote_sent`, `date_quote_accepted`, `date_payment_made`, `sales_tax`, `date_item_shipped`, `date_item_received`) VALUES
(11, '2017-09-14 18:34:26', 'ZxRPMUThLe9WEYr', '2017-09-22 14:01:55', '2017-09-20 10:32:05', 'Pending', 0, 'Pending', 'Pending'),
(14, '2017-09-18 19:00:53', 'lhhOySuc0IlJ7f4', '2017-09-21 00:05:31', '2017-09-21 00:07:05', 'Pending', 0, 'Pending', 'Pending'),
(15, '2017-09-18 19:07:35', '5BYOCNN4zIcn0Tp', '2017-09-20 20:28:40', '2017-09-20 20:28:07', 'Pending', 0, 'Pending', 'Pending'),
(16, '2017-09-22 12:47:29', 'MkIizrXIxXJoDIA', '2017-09-25 01:40:58', '2017-09-25 11:52:49', 'Pending', 129.99, 'Pending', 'Pending'),
(17, '2017-09-22 12:59:50', 'r5KGfusW6FxgJTu', '2017-11-07 21:43:51', '2017-11-01 11:43:02', 'Pending', 0.5, 'Pending', 'Pending'),
(18, '2017-09-22 21:23:30', 'n80GOTi7ZFiY3ku', '2017-11-07 21:46:32', '2017-09-22 23:25:27', 'Pending', 0, 'Pending', 'Pending'),
(20, '2017-10-04 15:46:10', 'AWHKQtZNDG5SJUh', '2017-10-04 17:46:39', 'Pending', 'Pending', 0, 'Pending', 'Pending'),
(22, '2017-10-05 18:05:59', 'Np7o48bQK4aImxA', '2017-10-05 20:10:29', 'Pending', 'Pending', 47.99, 'Pending', 'Pending'),
(23, '2017-10-05 18:14:41', 'E44RCTH5jjwkSZT', '2017-10-05 20:16:01', 'Pending', 'Pending', 0, 'Pending', 'Pending'),
(24, '2017-11-18 17:00:29', '6iq7ixyxSQnXDOD', '2017-11-18 19:04:05', '2017-11-18 19:36:08', '2017-11-18 20:14:30', 0.5, 'Pending', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `quote_emails`
--

CREATE TABLE `quote_emails` (
  `id` int(10) NOT NULL,
  `quote_id` int(10) NOT NULL COMMENT 'Quote ID',
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(5) NOT NULL,
  `subject` varchar(255) NOT NULL COMMENT 'Subject',
  `body` longtext NOT NULL COMMENT 'Body'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quote_emails`
--

INSERT INTO `quote_emails` (`id`, `quote_id`, `entrydate`, `user_id`, `subject`, `body`) VALUES
(10, 11, '2017-09-20 05:33:57', 4, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(18, 15, '2017-09-20 18:27:13', 3, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(19, 15, '2017-09-20 18:28:40', 3, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(46, 14, '2017-09-20 22:05:31', 3, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(69, 11, '2017-09-22 12:01:55', 4, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(70, 16, '2017-09-22 12:47:29', 1, 'Request for quotation', 'The client has made a request for quotation.'),
(71, 17, '2017-09-22 12:59:50', 1, 'Request for quotation', 'The client has made a request for quotation.'),
(73, 16, '2017-09-22 17:42:34', 4, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(74, 18, '2017-09-22 21:23:30', 1, 'Request for quotation', 'The client has made a request for quotation.'),
(75, 18, '2017-09-22 21:24:15', 3, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(89, 16, '2017-09-24 23:40:58', 4, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(92, 20, '2017-10-04 15:46:10', 1, 'Request for quotation', 'The client has made a request for quotation.'),
(93, 20, '2017-10-04 15:46:39', 3, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(96, 22, '2017-10-05 18:05:59', 1, 'Request for quotation', 'The client has made a request for quotation.'),
(97, 22, '2017-10-05 18:10:29', 2, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(98, 23, '2017-10-05 18:14:41', 1, 'Request for quotation', 'The client has made a request for quotation.'),
(99, 23, '2017-10-05 18:16:01', 3, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(104, 17, '2017-11-01 09:36:26', 1, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(105, 17, '2017-11-07 19:43:51', 1, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(106, 18, '2017-11-07 19:46:32', 1, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.'),
(107, 24, '2017-11-18 17:00:29', 1, 'Request for quotation', 'The client has made a request for quotation.'),
(108, 24, '2017-11-18 17:04:05', 1, 'Prepared Quotation', 'The quotation with figures attached was sent to the client.');

-- --------------------------------------------------------

--
-- Table structure for table `quote_items`
--

CREATE TABLE `quote_items` (
  `quote_id` int(10) NOT NULL COMMENT 'Quote ID',
  `item_id` int(2) NOT NULL COMMENT 'Item',
  `price` float NOT NULL COMMENT 'Price',
  `quantity` int(2) DEFAULT '1' COMMENT 'Quantity',
  `shipping` float DEFAULT NULL COMMENT 'Shipping Price',
  `specifications` varchar(255) DEFAULT NULL COMMENT 'Color, Material'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quote_items`
--

INSERT INTO `quote_items` (`quote_id`, `item_id`, `price`, `quantity`, `shipping`, `specifications`) VALUES
(11, 18, 599.99, 2, 199.99, 'None'),
(14, 47, 399.99, 2, 50, 'None'),
(14, 36, 0.5, 2, 0.5, 'None'),
(15, 24, 699, 1, 56, 'Blue'),
(16, 58, 129.99, 1, 12.99, 'None'),
(16, 57, 139.99, 1, 12.99, 'None'),
(16, 53, 12.99, 1, 12.99, 'None'),
(16, 52, 150, 1, 120, 'None'),
(17, 54, 0.5, 1, 0.5, 'None'),
(17, 49, 0.5, 1, 0.5, 'None'),
(17, 44, 0.5, 1, 1, 'None'),
(17, 26, 0.5, 1, 0.5, 'None'),
(18, 20, 200, 1, 10, 'None'),
(20, 6, 400, 1, 50, 'None'),
(22, 28, 500, 1, 39.99, 'None'),
(23, 41, 100, 1, 50, 'None'),
(24, 36, 0.5, 1, 0.5, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_address`
--

CREATE TABLE `shipping_address` (
  `quote_id` int(10) NOT NULL COMMENT 'Quote ID',
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `full_name` varchar(50) NOT NULL COMMENT 'Full Name',
  `cellphone` varchar(20) DEFAULT NULL COMMENT 'Cellphone',
  `physical_address` varchar(255) NOT NULL COMMENT 'Physical Address',
  `apt_suit` varchar(255) DEFAULT NULL COMMENT 'Apt, suit, etc.',
  `city` varchar(255) NOT NULL COMMENT 'City',
  `country_id` int(2) NOT NULL COMMENT 'Country'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipping_address`
--

INSERT INTO `shipping_address` (`quote_id`, `email`, `full_name`, `cellphone`, `physical_address`, `apt_suit`, `city`, `country_id`) VALUES
(11, 'william.sengdara@gmail.com', 'Andy Dufrane', '0813918334', 'Erf 5389, Shoveller Street, Khomasdal', 'Darlington Court, Unit #4', 'Windhoek', 152),
(14, 'ubahashipoke@gmail.com', 'Ubaha Shipoke', '+447599858658', '28 Matheson Lang Gardens', '', 'London', 187),
(15, 'ubahashipoke@gmail.com', 'Ubaha Shipoke', '+447599858658', '28 Matheson Lang Gardens', '', 'London', 187),
(16, 'william.sengdara@gmail.com', 'Han-Joon Soo', '0813918334', '# hana sep tul', '', 'Seoul', 162),
(17, 'william.sengdara@gmail.com', 'Donald Trump', '0813918334', 'Trump Tower', '#1000', 'Chicago', 188),
(18, 'ubahashipoke@gmail.com', 'Ubaha', '07599858658', '123 Windhoek', '', 'Windhoek', 7),
(20, 'ubahashipoke@gmail.com', 'Anna', '08145876859', '15 Troupant', '', 'Gaborone', 23),
(22, 'ubahashipoke@gmail.com', 'Rosa', '09862177990', '13 Schafer', '', 'Windhoek', 1),
(23, 'jay_j0753@hotmail.com', 'Jj', '0812115337', '7 saffier street', '', 'san jose', 1),
(24, 'william.sengdara@gmail.com', 'Developer', '0813918334', 'Erf 5389, Shoveller street, Khomasdal', 'Darlington court, Unit 4', 'Windhoek', 120);

-- --------------------------------------------------------

--
-- Table structure for table `store_items`
--

CREATE TABLE `store_items` (
  `id` int(10) NOT NULL,
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(5) NOT NULL,
  `store_item_category_id` int(2) NOT NULL COMMENT 'Root category',
  `title` varchar(100) NOT NULL COMMENT 'Title',
  `description` longtext COMMENT 'Description',
  `material` varchar(255) DEFAULT NULL COMMENT 'Material',
  `cost` varchar(10) DEFAULT NULL COMMENT 'Item cost',
  `shipping` varchar(10) DEFAULT NULL COMMENT 'Shipping Cost',
  `discount` varchar(10) DEFAULT NULL COMMENT 'Discount',
  `tag` varchar(20) DEFAULT NULL COMMENT 'Tag',
  `category_id` int(2) NOT NULL COMMENT 'Category',
  `url_image_1` varchar(255) NOT NULL COMMENT 'Image 1 URL',
  `url_image_2` varchar(255) NOT NULL COMMENT 'Image 2 URL',
  `url_image_3` varchar(255) NOT NULL COMMENT 'Image 3 URL',
  `url_image_4` varchar(255) NOT NULL COMMENT 'Image 4 URL',
  `url_video` varchar(255) NOT NULL COMMENT 'Video URL',
  `visibility_id` int(2) NOT NULL DEFAULT '1' COMMENT 'Visibility'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_items`
--

INSERT INTO `store_items` (`id`, `entrydate`, `user_id`, `store_item_category_id`, `title`, `description`, `material`, `cost`, `shipping`, `discount`, `tag`, `category_id`, `url_image_1`, `url_image_2`, `url_image_3`, `url_image_4`, `url_video`, `visibility_id`) VALUES
(1, '2017-09-06 21:34:07', 2, 1, 'Banner 1', 'Banner 1', NULL, '', NULL, '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21430319_125848634732777_215217254450658145_n.jpg?oh=a947514e516c680f6f6ac9174633f7b3&oe=5A5EEE1E', '', '', '', '', 1),
(2, '2017-09-06 21:34:33', 2, 1, 'Banner 2', 'Banner 2', NULL, '', NULL, '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21369282_125848628066111_6545589330668902068_n.jpg?oh=94e4521dfd3e23ce10c2ae4d5ffc8b37&oe=5A602C7E', '', '', '', '', 1),
(3, '2017-09-06 21:34:54', 2, 1, 'Banner 3', 'Banner 3A', '', '', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21369435_125848631399444_7876089275034236301_n.jpg?oh=9b2b62c1c03cb9700b8aa02f5da3469b&amp;oe=5A5F6E96', '', '', '', '', 1),
(4, '2017-09-06 22:37:11', 2, 2, 'Print Chair', 'Chair with print / fabric', '', '4000.00', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21271066_125848008066173_4447641343423515598_n.jpg?oh=000fd12a863dce060ef34194e2ce1209&amp;oe=5A52485B', '', '', '', '', 1),
(5, '2017-09-06 22:47:44', 2, 2, 'Hour Glass', 'Carved sandial side table', '', '300', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21430304_125848004732840_1425027499839342531_n.jpg?oh=87bb7964801f26f501372105c3ad0249&amp;oe=5A1CD88C', '', '', '', '', 1),
(6, '2017-09-06 22:48:30', 2, 2, '4 Legged Plush Stool', 'Plush stool with 4 legs', '', '100', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21433001_125848011399506_517947916846697702_n.jpg?oh=405c44b1d338b4e4348072b7dd06a185&amp;oe=5A54E247', '', '', '', '', 1),
(7, '2017-09-06 22:49:22', 2, 2, 'The Symmetrical', 'Symmetrical-like side table', '', '100', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21317444_125848034732837_6976988221271078228_n.jpg?oh=8c1b713a434178b1754ea58c3d0c0270&amp;oe=5A1F2EF6', '', '', '', '', 1),
(8, '2017-09-06 22:50:39', 2, 2, 'Mirror', 'Mirror', NULL, '700', NULL, '', '', 3, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21314674_125848038066170_7161215156199740850_n.jpg?oh=69b99680bf4d163cd6021511fe36b962&amp;oe=5A128255', '', '', '', '', 1),
(9, '2017-09-06 22:56:50', 2, 2, 'Mirror 2', 'Mirror 2', NULL, '', NULL, '', '', 3, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21272526_125848044732836_7702578419335287918_n.jpg?oh=3bf42d3f27e13564c8bfdd4d156644ce&amp;oe=5A1F24FD', '', '', '', '', 1),
(10, '2017-09-06 23:01:11', 2, 2, 'Sculpture', 'Metal sculpture', '', '89.99', '', '', '', 5, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21369353_125848084732832_7203658193581296963_n.jpg?oh=978594f4c72b4efe1a96c3f258f46712&amp;oe=5A50A8C3', 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21728139_127362624581378_6372666251351692882_n.jpg?oh=25d34e7a9fe4efe137289213cda506e4&amp;oe=5A598C9C', '', '', '', 1),
(11, '2017-09-06 23:01:34', 2, 2, 'Mirror 3', 'Mirror 3', NULL, '99.99', NULL, '', '', 3, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21462841_125848088066165_6991847671108431209_n.jpg?oh=5061b033904c440ab48ea858ada36aaa&amp;oe=5A186975', '', '', '', '', 1),
(12, '2017-09-06 23:02:19', 2, 2, 'The Fish', 'Fish carved mahogany side table', '', '100.00', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21317898_125848134732827_6681368131681831599_n.jpg?oh=c8224a68152c976d64a554ca0e34621e&amp;oe=5A21EE3E', '', '', '', '', 1),
(13, '2017-09-06 23:03:17', 2, 2, 'Spider', 'Carved spider side tables', '', '89.00', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21272563_125848161399491_1498948535406121894_n.jpg?oh=3ea172ce79faed8708d829870369c75c&amp;oe=5A4C342D', '', '', '', '', 1),
(14, '2017-09-06 23:04:06', 2, 2, 'Radiating Sun Mirror', 'Glass star mahogany mirror', '', '89.99', '', '', '', 3, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21430094_125848251399482_5623778029644660874_n.jpg?oh=4ba27ea76bb97d79d27654bb5e2249f9&amp;oe=5A4E5392', '', '', '', '', 1),
(15, '2017-09-06 23:05:00', 2, 2, 'Horn Server Table', 'Horn server table', '', '99.99', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21317851_125848278066146_5027684409759997119_n.jpg?oh=a1c4d84d0af8fd82fc910b0bb26d76c8&amp;oe=5A55C8F4', '', '', '', '', 1),
(16, '2017-09-06 23:05:27', 2, 2, 'Pende', 'Wood side table', '', '89.00', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21369641_125848164732824_2662155087568834783_n.jpg?oh=f3ef8555bd52e57e503c89b1634a1cee&amp;oe=5A51CBFD', '', '', '', '', 1),
(17, '2017-09-06 23:06:13', 2, 2, 'Scifi Table Base', 'Scifi', '', '89.99', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21369121_125848174732823_7115404668902938612_n.jpg?oh=79f4d8e80288a8e8a7a8758972d535d3&amp;oe=5A1A2C09', '', '', '', '', 1),
(18, '2017-09-06 23:06:36', 2, 2, 'Black Sun Mirror', 'Black sun', '', '89.99', '', '', '', 3, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21462231_125848311399476_5137550521741677571_n.jpg?oh=eea911cf28357c8595bb6e5ec6ca1259&amp;oe=5A14763D', '', '', '', '', 1),
(19, '2017-09-06 23:09:29', 2, 2, 'Wishbone', 'Wishbone side table', '', '89.99', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21462949_125848094732831_2914723068387105325_n.jpg?oh=9b046a96cd3e4f72d1e0d07daa6f7697&amp;oe=5A611424', '', '', '', '', 1),
(20, '2017-09-06 23:10:20', 2, 2, 'Majestic Silver', 'Majestic / Silver chair', '', '1200', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21317918_125848458066128_5389179450652987577_n.jpg?oh=2a6bc34a0257a183628bf54a27318618&amp;oe=5A1C4DE2', '', '', '', '', 1),
(21, '2017-09-06 23:11:05', 2, 2, 'Majestic Leather', 'Majestic / Leather chair', '', '1200', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21317631_125848374732803_5342227402660887758_n.jpg?oh=bfd42dc4886f8ae0461701feec3eebc6&amp;oe=5A20C88D', '', '', '', '', 1),
(22, '2017-09-06 23:11:57', 2, 2, 'Wood Barrel', 'Wood / Barrel', NULL, '65.00', NULL, '', '', 5, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21272547_125848411399466_6542642887488210767_n.jpg?oh=7d53e9ffefaae795f6dd164e8d764d95&amp;oe=5A5A7D9E', '', '', '', '', 1),
(23, '2017-09-06 23:12:34', 2, 2, '3 Legged Hoof', '3 legs / hooves / mini table', '', '89.00', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21432870_125848204732820_5394679600921408562_n.jpg?oh=29dc5db4ef3efe26ae73b332cc41da26&amp;oe=5A221DD2', '', '', '', '', 1),
(24, '2017-09-06 23:13:41', 2, 2, 'Animal Hide', 'Animal hide chair', '', '1500', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21430529_125848468066127_6035197106875429167_n.jpg?oh=5ace2da124c2efb2be8fef34dbdce07c&amp;oe=5A1BFF0E', '', '', '', '', 1),
(25, '2017-09-06 23:14:08', 2, 2, 'Arachnid', 'Wood 3-legged side table', '', '89.00', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21371184_125848208066153_4419001068481360093_n.jpg?oh=4f39d6686ae5747a5314761b48a3e254&amp;oe=5A53EFC9', '', '', '', '', 1),
(26, '2017-09-06 23:14:43', 2, 2, 'Artistic Table', 'Artistic / Mini table', '', '89.99', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21106597_125848221399485_5409750916582934576_n.jpg?oh=63b35cf4771d0ff24f9f004659e666cc&amp;oe=5A20EA8E', '', '', '', '', 1),
(27, '2017-09-06 23:15:14', 2, 2, 'Majestic', 'Black suede chair', '', '400', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21430412_125848421399465_8243309208388839106_n.jpg?oh=39f7f67865c04d8fa1ae45ff99d24404&amp;oe=5A5E22D8', '', '', '', '', 1),
(28, '2017-09-06 23:15:43', 2, 2, 'Artistic Table', 'Artistic', '', '300.00', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21369392_125848248066149_8560795945752413878_n.jpg?oh=fde7454e8f6602e61152d7f01cff93ce&amp;oe=5A220890', '', '', '', '', 1),
(29, '2017-09-06 23:16:21', 2, 2, 'Bubbles', 'Circular decor mirror', '', '300.00', '', '', '', 3, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21371172_125848434732797_4500206617072856956_n.jpg?oh=8f12a6244ddbff11b6cceb6f674ef180&amp;oe=5A57A5E0', '', '', '', '', 1),
(30, '2017-09-06 23:16:54', 2, 2, 'Paris Mirror', 'Long glass mirror', '', '', '', '', '', 3, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21272667_125848358066138_8040692941652551956_n.jpg?oh=2fa2fb1a976b76cf9bcea33138a93f8e&amp;oe=5A1C7064', '', '', '', '', 1),
(31, '2017-09-06 23:17:24', 2, 2, 'Circled Ottoman', 'Cushion', '', '', '', '', '', 5, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21314723_125848361399471_2805624565171284799_n.jpg?oh=807cef50aefa90e5e3658354efe0d424&amp;oe=5A544141', '', '', '', '', 1),
(32, '2017-09-07 10:26:28', 2, 2, 'Hollow Wood Table', 'Wood', '', '', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21314417_125936858057288_4449401379822466229_n.jpg?oh=8552ab1fc9fdb5ee73ae477a069c1dd0&amp;oe=5A156068', '', '', '', '', 1),
(33, '2017-09-07 10:27:21', 2, 2, 'Grey Velvet Lounger', 'Sofa', '', '999.00', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21317932_125936941390613_3060821875190552615_n.jpg?oh=7f552f7bce2358555806a8998861a268&amp;oe=5A1BC9DA', '', '', '', '', 1),
(34, '2017-09-07 10:28:06', 2, 2, 'Flat Silvered Chair', 'flat chair', '', '899.99', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21369444_125936861390621_5688998092635843106_n.jpg?oh=e8eef687a938f0b7b251c7920fadb570&amp;oe=5A5CCDCB', '', '', '', '', 1),
(35, '2017-09-07 10:28:31', 2, 2, 'Velvet Lounger', 'Sofa 2', '', '', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21314551_125936854723955_3994835834129151494_n.jpg?oh=3a902f12ed31d0068252984e3b479fee&amp;oe=5A16A4CB', '', '', '', '', 1),
(36, '2017-09-07 10:29:17', 2, 2, 'Tiered Glass Table', 'Wood circled boarders with glass fittings', '', '', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21314586_125936884723952_4910861029492542288_n.jpg?oh=a26f500968b395003a5ce7a9bf630fca&amp;oe=5A1BF535', '', '', '', '', 1),
(37, '2017-09-07 10:30:21', 2, 2, 'Mirrored Storage Cabinet', 'Mirror / cabinet', '', '', '', '', '', 5, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21317667_125936888057285_8229581619720031959_n.jpg?oh=6a0b35a4798b4788e156388c065ec92d&amp;oe=5A186448', '', '', '', '', 1),
(38, '2017-09-07 10:30:59', 2, 2, 'High Table', 'Table', '', '399.99', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21430145_125936901390617_2935410355349809559_n.jpg?oh=2d11f47acbda3bad1757350328ced434&amp;oe=5A1E44DC', '', '', '', '', 1),
(39, '2017-09-07 10:31:29', 2, 2, 'Sleek Black Sofa', 'Sofa', '', '', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21371154_125936934723947_5883290142199043523_n.jpg?oh=f186facc7a585cbf9ab9278b68519414&amp;oe=5A154230', '', '', '', '', 1),
(40, '2017-09-07 10:41:31', 2, 2, 'African Print Chairs', 'Print / chairs', '', '', '', '', '', 2, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21371333_125936988057275_1833339764507043734_n.jpg?oh=ca52708ea0a962a83dc3431e7b2ce45a&amp;oe=5A5DE26C', '', '', '', '', 1),
(41, '2017-09-07 10:42:22', 2, 2, 'Curved Leg Dining Table', 'Table / 4 legs', '', '', '', '', '', 1, 'https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/21371069_125936931390614_7223973190381959695_n.jpg?oh=4d77a985578436e4a2fe4eb5d761c6ca&amp;oe=5A1F2166', '', '', '', '', 1),
(42, '2017-09-13 19:35:39', 3, 2, 'Shaka Dyed Springbok', 'Chair covered in Springbok hyde', '', '', '', '', '', 2, 'https://scontent-lhr3-1.xx.fbcdn.net/v/t1.0-9/21730998_127362781248029_5498304389282553076_n.jpg?oh=16bca589440b588f1f1e2993d1dec2ba&amp;oe=5A44CCC2', '', '', '', '', 1),
(43, '2017-09-15 20:56:53', 3, 2, 'Brown Velvet Couches', 'Conjoined brown velvet couches', '', '0', '', '', '', 2, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21740533_127360754581565_8559070004051037978_n.jpg?oh=8dd1e93c6d6b99364ccbbc66d3526ba8&amp;oe=5A438B36', '', '', '', '', 1),
(44, '2017-09-15 21:00:57', 3, 2, 'Mirror Divider', 'Wood-mirror divider', '', '0', '', '', '', 3, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21751397_127360767914897_4394940826666650694_n.jpg?oh=fa7282477feed4b907c8628e251bfa40&amp;oe=5A5A3223', '', '', '', '', 1),
(45, '2017-09-15 21:02:18', 3, 2, 'Grey Velvet Chair', 'Grey velvet semi-circle chair', '', '0', '', '', '', 2, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21728344_127360781248229_1202319583643444487_n.jpg?oh=e441a1e6ec9c359c4f98c669de13384e&amp;oe=5A17B1ED', '', '', '', '', 1),
(46, '2017-09-15 21:04:37', 3, 2, 'Crocodile', 'Crocodile-textured cupboard', '', '0', '', '', '', 5, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21751551_127360817914892_4029351316848673319_n.jpg?oh=f6ec1758799d9de333dd702b613f1472&amp;oe=5A504B2F', '', '', '', '', 1),
(47, '2017-09-15 21:10:09', 3, 2, 'Royalty', 'Sleek pink velvet chair', '', '0', '', '', '', 2, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21740173_127361057914868_858886856364620679_n.jpg?oh=70f9afd9aa0920fdac588f0700fd1c70&amp;oe=5A4FA7F0', '', '', '', '', 1),
(48, '2017-09-15 21:13:05', 3, 2, 'Tribal', 'Patterned mirror', '', '0', '', '', '', 3, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21751404_127362771248030_5909946789138127451_n.jpg?oh=225f8293879ad05922112cf594085c9b&amp;oe=5A49A9EC', '', '', '', '', 1),
(49, '2017-09-15 21:15:04', 3, 2, 'Spear ', 'Wood spear sculpture', '', '0', '', '', '', 7, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21731363_127816591202648_5633436476072313414_n.jpg?oh=810e160a6120fb93d0855de8add60a89&amp;oe=5A57AD14', '', '', '', '', 1),
(50, '2017-09-15 21:16:17', 3, 2, 'Radial ', 'Radial mirror', '', '0', '', '', '', 3, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21730920_127362697914704_4410347418158222834_n.jpg?oh=eb4ad536428fc72d538c7eac88663f68&amp;oe=5A4FD3A1', '', '', '', '', 1),
(51, '2017-09-15 21:19:45', 3, 2, 'Mirrored Sideboard', 'Mirrored storage cabinet/table', '', '0', '', '', '', 5, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21728009_127816977869276_4044191874282521744_n.jpg?oh=59f2db897e0f86e6ffcca9c6a2f5027a&amp;oe=5A179192', '', '', '', '', 1),
(52, '2017-09-15 21:28:15', 3, 2, 'Queens', '3 Queens', '', '0', '', '', '', 4, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21751943_127817847869189_807076673976791909_n.jpg?oh=2c04b17c349d0d817328a57276464565&amp;oe=5A17B71A', '', '', '', '', 1),
(53, '2017-09-15 21:29:13', 3, 2, 'Sweet Melody', 'This picture tries to incorporate music and art in one representing Papa as not only an artist but a musician too. Being a musician he is able to express his work through visual art, and this piece reflects his creativity in music and his way of communicating the values in music and the story that can be understood behind every song if one is attentive. ', '', '0', '', '', '', 4, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21740022_127817894535851_5965410549533633557_n.jpg?oh=5f6591feae9590a26ff354d69243ec51&amp;oe=5A12FBC3', '', '', '', '', 1),
(54, '2017-09-15 21:29:44', 3, 2, 'The Village', 'This lady is from the Owambo tribe, who come from the North of Namibia. The hut, cattle and palm tree represent her wealth, as these items are the basic necessities she needs in life to survive and be content. She values the minimum she has, and does not need material goods to sustain her happiness, which she views as goods that only bring temporary happiness, jealousy and greed. This painting aims to send the message of the importance of embracing heritage. ', '', '0', '', '', '', 4, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21728224_127817911202516_4261420650336404746_n.jpg?oh=722c42ae140e7d1a23879ab64447440b&amp;oe=5A4CFB3E', '', '', '', '', 1),
(55, '2017-09-15 21:30:25', 3, 2, 'Namibia', 'This lady&rsquo;s dress design is the Namibian flag. The colours all symbolize something; red represents Namibia\'s most important resource, its people. It refers to their heroism and their determination to build a future of equal opportunity for all. White - refers to peace and unity. Green - symbolises vegetation and agricultural resources. Blue - represents the clear Namibian sky and the Atlantic Ocean, the country\'s precious water resources and rain. This lady is dancing, symbolizing Namibia&rsquo;s freedom from foreign rule.', '', '0', '', '', '', 4, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21762025_127817981202509_7778951260368745484_n.jpg?oh=e959d626c60f5c3e53c2afbaa13a5043&amp;oe=5A449934', '', '', '', '', 1),
(56, '2017-09-15 21:31:14', 3, 2, 'Wisdom', 'In today&rsquo;s society, the respect the elders receive is not the same as what they gave their elders when they were young. The youth do not value the advice and wisdom elders have to share. This picture is of two young boys from different tribes (Masi and Himba) listening to advice given by their elders who were once children, who want to share their wisdom to children who are willing to have their cups filled. ', '', '0', '', '', '', 4, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21728087_127817964535844_6713039945472901195_n.jpg?oh=aa7de76afd16fe28b9edd63949261092&amp;oe=5A47E94D', '', '', '', '', 1),
(57, '2017-09-15 21:31:57', 3, 2, 'Wealth', 'Before westernization came to Africa, wealth was measured in cattle owned. This picture aims to tell the viewer not to conform to someone else&rsquo;s beliefs while neglecting one&rsquo;s own. People should take pride in their culture, and learn to embrace it. Also trying to say that you don&rsquo;t learn everything from school but sometimes learn more from personal experience.', '', '0', '', '', '', 4, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21728123_127817851202522_8278473014277918237_n.jpg?oh=847085ebd449d0487468309c70ef931a&amp;oe=5A60E66E', '', '', '', '', 1),
(58, '2017-09-15 21:32:37', 3, 2, 'Nature', 'This piece was done during the drought period in Namibia. Papa constantly visited an eagle that resided in the famous Avis Dam. However during the drought period, the eagle was no longer spotted. The piece was done in an effort to bring rain to fill up the dam, and sends the message that once you neglect nature and your totem, you lose your connection to them.', '', '0', '', '', '', 4, 'https://scontent-lht6-1.xx.fbcdn.net/v/t1.0-9/21761794_127817974535843_4186582749952944673_n.jpg?oh=a899f1218f6c47def0640a8fb0930a3f&amp;oe=5A455B01', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `store_item_categories`
--

CREATE TABLE `store_item_categories` (
  `id` int(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `width` varchar(10) NOT NULL,
  `height` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_item_categories`
--

INSERT INTO `store_item_categories` (`id`, `name`, `width`, `height`) VALUES
(1, 'Banner', '1200', '400'),
(2, 'Item', '180', '200'),
(3, 'Gallery', '1200', '400');

-- --------------------------------------------------------

--
-- Table structure for table `system_bugs`
--

CREATE TABLE `system_bugs` (
  `id` int(5) NOT NULL,
  `description` longtext,
  `severity` int(5) DEFAULT '1',
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE `system_log` (
  `id` int(5) NOT NULL,
  `action` varchar(50) NOT NULL,
  `ipaddress` varchar(255) DEFAULT NULL,
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_log`
--

INSERT INTO `system_log` (`id`, `action`, `ipaddress`, `entrydate`, `description`) VALUES
(1211, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-05 13:25:58', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-05 15:25:58'),
(1212, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-06 18:29:04', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-06 20:29:04'),
(1213, 'USER_ADD', '105.232.35.191', '2017-09-06 20:29:54', 'A new user has been added to the system. \n								User details: userid: 2, username: jj. \n								Added by 1.'),
(1214, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-06 18:30:50', 'User has been successfully logged out. userid: 1.'),
(1215, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-06 18:30:55', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-06 20:30:55'),
(1216, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 20:33:41', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1217, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 20:34:03', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1218, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 21:05:12', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1219, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 21:05:55', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1220, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 21:26:14', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1221, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 21:32:20', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1222, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 21:34:07', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1223, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 21:34:34', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1224, 'VIEW_LOAD', '105.232.35.191', '2017-09-06 21:34:55', 'Failed to load view handler: ui/manage-banners-store.php. File not found.'),
(1225, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-06 20:35:15', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-06 22:35:15'),
(1226, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-06 20:35:27', 'User has been successfully logged out. userid: 1.'),
(1227, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-06 20:35:31', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-06 22:35:31'),
(1228, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-07 08:26:32', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-07 10:26:32'),
(1229, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-07 20:55:05', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-07 22:55:05'),
(1230, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-07 20:56:45', 'User has been successfully logged out. userid: 2.'),
(1231, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-08 19:41:00', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-08 21:41:00'),
(1232, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-09 13:37:34', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-09 15:37:34'),
(1233, 'USER_ADD', '105.232.35.191', '2017-09-09 15:40:05', 'A new user has been added to the system. \n								User details: userid: 3, username: ubaha. \n								Added by 1.'),
(1234, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-09 15:35:26', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-09 17:35:26'),
(1235, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-09 16:07:48', 'User has been successfully logged out. userid: 1.'),
(1236, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-09 16:07:56', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-09 18:07:56'),
(1237, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-09 16:08:02', 'User has been successfully logged out. userid: 1.'),
(1238, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-10 12:35:31', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-10 14:35:31'),
(1239, 'VIEW_LOAD', '105.232.35.191', '2017-09-10 14:35:40', 'Failed to load view handler: ui/manage-orders.php. File not found.'),
(1240, 'VIEW_LOAD', '105.232.35.191', '2017-09-10 14:41:26', 'Failed to load view handler: ui/manage-orders.php. File not found.'),
(1241, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-10 13:01:11', 'User has been successfully logged out. userid: 1.'),
(1242, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-10 13:03:08', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-10 15:03:08'),
(1243, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-11 18:04:50', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-11 20:04:50'),
(1244, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-12 06:41:14', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-12 08:41:14'),
(1245, 'QUERY EDIT', '105.232.35.191', '2017-09-12 08:55:57', 'Query has been edited. Query id: 2. Userid: 1.'),
(1246, 'QUERY EDIT', '105.232.35.191', '2017-09-12 08:56:09', 'Query has been edited. Query id: 2. Userid: 1.'),
(1247, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 09:45:12', 'Failed to load view handler: ui/manage-banners.php. File not found.'),
(1248, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 09:45:15', 'Failed to load view handler: ui/manage-store-items.php. File not found.'),
(1249, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 09:50:57', 'Failed to load view handler: ui/manage-store-items.php. File not found.'),
(1250, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 09:54:49', 'Failed to load view handler: ui/manage-banners.php. File not found.'),
(1251, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 09:57:52', 'Failed to load view handler: ui/manage-store-items.php. File not found.'),
(1252, 'LOGIN_FAIL', '105.232.35.191', '2017-09-12 15:41:19', 'Unable to login. Check your username or password or contact the administrator.'),
(1253, 'LOGIN_FAIL', '105.232.35.191', '2017-09-12 15:41:26', 'Unable to login. Check your username or password or contact the administrator.'),
(1254, 'LOGIN_FAIL', '105.232.35.191', '2017-09-12 15:41:34', 'Unable to login. Check your username or password or contact the administrator.'),
(1255, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-12 15:41:42', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-12 17:41:42'),
(1256, 'USER_PWD_CHANGE', '105.232.35.191', '2017-09-12 15:42:10', 'The password of the user has been changed. User id: 3'),
(1257, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-12 15:42:20', 'User has been successfully logged out. userid: 1.'),
(1258, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-12 15:42:31', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-12 17:42:31'),
(1259, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 17:47:54', 'Failed to load view handler: ui/manage-banners.php. File not found.'),
(1260, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 18:33:11', 'Failed to load view handler: ui/manage-store-items.php. File not found.'),
(1261, 'VIEW_LOAD', '105.232.35.191', '2017-09-12 18:49:12', 'Failed to load view handler: ui/manage-orders.php. File not found.'),
(1262, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-12 17:47:54', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-12 19:47:54'),
(1263, 'LOGIN_SUCCESS', '2.30.139.121', '2017-09-12 17:55:06', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-12 19:55:06'),
(1264, 'LOGIN_LOGOUT', '2.30.139.121', '2017-09-12 18:29:56', 'User has been successfully logged out. userid: 3.'),
(1265, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-12 19:19:05', 'User has been successfully logged out. userid: 1.'),
(1266, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-12 19:19:15', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-12 21:19:15'),
(1267, 'LOGIN_LOGOUT', '105.232.35.191', '2017-09-12 19:21:05', 'User has been successfully logged out. userid: 1.'),
(1268, 'LOGIN_SUCCESS', '105.232.35.191', '2017-09-12 21:16:08', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-12 23:16:08'),
(1269, 'LOGIN_SUCCESS', '2.30.139.121', '2017-09-13 16:15:22', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-13 18:15:22'),
(1270, 'LOGIN_SUCCESS', '2.30.139.121', '2017-09-13 17:33:38', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-13 19:33:38'),
(1271, 'LOGIN_SUCCESS', '105.232.90.232', '2017-09-13 17:33:53', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-13 19:33:53'),
(1272, 'LOGIN_FAIL', '105.232.85.114', '2017-09-14 09:01:01', 'Unable to login. Check your username or password or contact the administrator.'),
(1273, 'LOGIN_FAIL', '105.232.85.114', '2017-09-14 09:01:08', 'Unable to login. Check your username or password or contact the administrator.'),
(1274, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 09:01:12', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-14 11:01:12'),
(1275, 'USER_ADD', '105.232.85.114', '2017-09-14 11:02:08', 'A new user has been added to the system. \n								User details: userid: 4, username: william. \n								Added by 1.'),
(1276, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-14 09:02:16', 'User has been successfully logged out. userid: 1.'),
(1277, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 09:03:40', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-14 11:03:40'),
(1278, 'USER_EDIT', '105.232.85.114', '2017-09-14 11:05:07', 'User account has been edited. User account: 3. \n								Editing done by userid 1.'),
(1279, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-14 09:05:26', 'User has been successfully logged out. userid: 1.'),
(1280, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 09:05:29', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-14 11:05:29'),
(1281, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 12:29:46', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-14 14:29:46'),
(1282, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 15:02:45', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-14 17:02:45'),
(1283, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 17:16:56', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-14 19:16:56'),
(1284, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 17:30:05', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-14 19:30:05'),
(1285, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 18:25:45', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-14 20:25:45'),
(1286, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-14 18:27:38', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-14 20:27:38'),
(1287, 'VIEW_LOAD', '105.232.85.114', '2017-09-14 22:21:59', 'Failed to load view handler: ui/manage-payments.php. File not found.'),
(1288, 'VIEW_LOAD', '105.232.85.114', '2017-09-14 22:22:35', 'Failed to load view handler: ui/manage-payments.php. File not found.'),
(1289, 'VIEW_LOAD', '105.232.85.114', '2017-09-14 22:22:57', 'Failed to load view handler: ui/manage-payments.php. File not found.'),
(1290, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 07:36:12', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-15 09:36:12'),
(1291, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 09:34:52', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-15 11:34:52'),
(1292, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 15:47:48', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-15 17:47:48'),
(1293, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 17:28:50', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-15 19:28:49'),
(1294, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-15 18:29:49', 'User has been successfully logged out. userid: 4.'),
(1295, 'LOGIN_SUCCESS', '2.24.166.224', '2017-09-15 18:55:16', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-15 20:55:16'),
(1296, 'LOGIN_SUCCESS', '2.24.166.224', '2017-09-15 18:55:48', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-15 20:55:48'),
(1297, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 22:43:24', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 00:43:24'),
(1298, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-15 22:46:39', 'User has been successfully logged out. userid: 4.'),
(1299, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 22:46:45', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 00:46:45'),
(1300, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-15 23:13:01', 'User has been successfully logged out. userid: 3.'),
(1301, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 23:13:05', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 01:13:05'),
(1302, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-15 23:14:37', 'User has been successfully logged out. userid: 4.'),
(1303, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-15 23:23:08', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 01:23:08'),
(1304, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-15 23:23:18', 'User has been successfully logged out. userid: 4.'),
(1305, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-16 07:34:47', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 09:34:47'),
(1306, 'VIEW_LOAD', '105.232.85.114', '2017-09-16 09:40:32', 'Failed to load view handler: ui/manage-store-items-categories.php. File not found.'),
(1307, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-16 09:11:46', 'User has been successfully logged out. userid: 4.'),
(1308, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-16 10:17:06', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 12:17:06'),
(1309, 'LOGIN_SUCCESS', '105.232.85.114', '2017-09-16 11:18:41', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 13:18:41'),
(1310, 'LOGIN_LOGOUT', '105.232.85.114', '2017-09-16 11:32:18', 'User has been successfully logged out. userid: 4.'),
(1311, 'LOGIN_SUCCESS', '197.243.133.249', '2017-09-16 17:07:22', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-16 19:07:22'),
(1312, 'LOGIN_SUCCESS', '197.243.133.249', '2017-09-17 09:58:28', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-17 11:58:28'),
(1313, 'LOGIN_LOGOUT', '197.243.133.249', '2017-09-17 09:59:49', 'User has been successfully logged out. userid: 4.'),
(1314, 'LOGIN_SUCCESS', '197.243.133.249', '2017-09-17 10:19:36', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-17 12:19:36'),
(1315, 'LOGIN_LOGOUT', '197.243.133.249', '2017-09-17 10:22:33', 'User has been successfully logged out. userid: 4.'),
(1316, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-18 10:58:25', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-18 12:58:25'),
(1317, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-18 17:09:23', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-18 19:09:23'),
(1318, 'LOGIN_SUCCESS', '2.24.166.224', '2017-09-18 17:10:12', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-18 19:10:12'),
(1319, 'LOGIN_SUCCESS', '2.24.166.224', '2017-09-18 17:24:25', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-18 19:24:25'),
(1320, 'LOGIN_LOGOUT', '2.24.166.224', '2017-09-18 17:40:19', 'User has been successfully logged out. userid: 3.'),
(1321, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-18 22:47:30', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-19 00:47:30'),
(1322, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-19 20:26:51', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-19 22:26:51'),
(1323, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-19 21:27:54', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-19 23:27:54'),
(1324, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 03:32:12', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 05:32:12'),
(1325, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 05:59:49', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 07:59:49'),
(1326, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 09:41:09', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 11:41:09'),
(1327, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-20 10:00:49', 'User has been successfully logged out. userid: 4.'),
(1328, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 10:01:00', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 12:01:00'),
(1329, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-20 10:01:10', 'User has been successfully logged out. userid: 4.'),
(1330, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 10:01:27', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 12:01:27'),
(1331, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-20 10:02:47', 'User has been successfully logged out. userid: 4.'),
(1332, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 10:03:16', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 12:03:16'),
(1333, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-20 10:05:19', 'User has been successfully logged out. userid: 4.'),
(1334, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 10:06:28', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 12:06:28'),
(1335, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-20 10:07:27', 'User has been successfully logged out. userid: 4.'),
(1336, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 10:07:32', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-09-20 12:07:32'),
(1337, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-20 10:15:43', 'User has been successfully logged out. userid: 1.'),
(1338, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 10:38:42', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 12:38:42'),
(1339, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 13:07:21', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 15:07:21'),
(1340, 'LOGIN_SUCCESS', '85.255.234.12', '2017-09-20 16:26:25', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 18:26:25'),
(1341, 'LOGIN_LOGOUT', '85.255.234.12', '2017-09-20 16:32:52', 'User has been successfully logged out. userid: 3.'),
(1342, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 18:32:52', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 20:32:52'),
(1343, 'LOGIN_SUCCESS', '2.24.166.224', '2017-09-20 20:05:08', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 22:05:08'),
(1344, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-20 20:27:00', 'User has been successfully logged out. userid: 4.'),
(1345, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-20 20:30:58', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-20 22:30:58'),
(1346, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-21 13:35:05', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-21 15:35:05'),
(1347, 'LOGIN_LOGOUT', '105.232.96.171', '2017-09-21 13:35:13', 'User has been successfully logged out. userid: 4.'),
(1348, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-21 17:56:42', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-21 19:56:42'),
(1349, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-21 19:11:16', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-21 21:11:16'),
(1350, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-22 07:38:59', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-22 09:38:59'),
(1351, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-22 09:53:49', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-22 11:53:49'),
(1352, 'LOGIN_SUCCESS', '105.232.96.171', '2017-09-22 15:04:03', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-22 17:04:03'),
(1353, 'LOGIN_FAIL', '2.24.166.224', '2017-09-22 19:22:17', 'Unable to login. Check your username or password or contact the administrator.'),
(1354, 'LOGIN_SUCCESS', '2.24.166.224', '2017-09-22 19:22:28', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-22 21:22:28'),
(1355, 'USER_PWD_CHANGE', '2.24.166.224', '2017-09-22 19:26:21', 'The password of the user has been changed. User id: 3'),
(1356, 'LOGIN_LOGOUT', '2.24.166.224', '2017-09-22 19:27:38', 'User has been successfully logged out. userid: 3.'),
(1357, 'LOGIN_SUCCESS', '105.232.255.217', '2017-09-23 09:50:23', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-23 11:50:23'),
(1358, 'LOGIN_SUCCESS', '105.232.255.217', '2017-09-23 12:04:32', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-23 14:04:32'),
(1359, 'LOGIN_SUCCESS', '105.232.255.217', '2017-09-23 13:26:49', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-23 15:26:49'),
(1360, 'LOGIN_SUCCESS', '105.232.255.217', '2017-09-24 16:18:49', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-24 18:18:49'),
(1361, 'LOGIN_SUCCESS', '105.232.25.170', '2017-09-24 21:38:04', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-24 23:38:04'),
(1362, 'LOGIN_LOGOUT', '105.232.25.170', '2017-09-24 21:41:14', 'User has been successfully logged out. userid: 4.'),
(1363, 'LOGIN_SUCCESS', '105.232.25.170', '2017-09-25 08:48:47', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-25 10:48:47'),
(1364, 'LOGIN_SUCCESS', '105.232.25.170', '2017-09-25 12:04:47', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-25 14:04:47'),
(1365, 'LOGIN_SUCCESS', '2.24.166.224', '2017-09-26 19:14:10', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-26 21:14:10'),
(1366, 'LOGIN_SUCCESS', '105.232.25.170', '2017-09-27 13:14:43', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-27 15:14:43'),
(1367, 'LOGIN_LOGOUT', '105.232.25.170', '2017-09-27 13:26:55', 'User has been successfully logged out. userid: 4.'),
(1368, 'LOGIN_SUCCESS', '105.232.25.170', '2017-09-27 14:16:00', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-09-27 16:16:00'),
(1369, 'LOGIN_SUCCESS', '2.24.166.224', '2017-10-04 13:45:09', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-04 15:45:09'),
(1370, 'LOGIN_SUCCESS', '2.24.166.224', '2017-10-04 17:56:49', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-04 19:56:49'),
(1371, 'LOGIN_SUCCESS', '105.232.61.66', '2017-10-04 18:11:53', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-04 20:11:53'),
(1372, 'LOGIN_LOGOUT', '105.232.61.66', '2017-10-04 19:01:29', 'User has been successfully logged out. userid: 4.'),
(1373, 'LOGIN_FAIL', '2.24.166.224', '2017-10-05 15:47:00', 'Unable to login. Check your username or password or contact the administrator.'),
(1374, 'LOGIN_SUCCESS', '2.24.166.224', '2017-10-05 15:49:12', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-05 17:49:12'),
(1375, 'LOGIN_FAIL', '105.232.61.66', '2017-10-05 15:59:59', 'Unable to login. Check your username or password or contact the administrator.'),
(1376, 'LOGIN_SUCCESS', '105.232.61.66', '2017-10-05 16:00:33', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-05 18:00:33'),
(1377, 'USER_PWD_CHANGE', '105.232.61.66', '2017-10-05 16:03:03', 'The password of the user has been changed. User id: 2'),
(1378, 'LOGIN_LOGOUT', '105.232.61.66', '2017-10-05 16:04:44', 'User has been successfully logged out. userid: 1.'),
(1379, 'LOGIN_SUCCESS', '105.232.61.66', '2017-10-05 16:05:48', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-05 18:05:48'),
(1380, 'LOGIN_SUCCESS', '108.30.215.203', '2017-10-05 16:07:45', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-05 18:07:45'),
(1381, 'LOGIN_SUCCESS', '105.232.61.66', '2017-10-05 16:12:13', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-05 18:12:13'),
(1382, 'LOGIN_LOGOUT', '108.30.215.203', '2017-10-05 16:13:12', 'User has been successfully logged out. userid: 2.'),
(1383, 'LOGIN_LOGOUT', '105.232.61.66', '2017-10-05 16:21:58', 'User has been successfully logged out. userid: 1.'),
(1384, 'LOGIN_SUCCESS', '105.232.61.66', '2017-10-05 16:24:53', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-05 18:24:53'),
(1385, 'LOGIN_LOGOUT', '105.232.61.66', '2017-10-05 16:26:05', 'User has been successfully logged out. userid: 4.'),
(1386, 'LOGIN_SUCCESS', '108.30.215.203', '2017-10-05 16:26:33', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-05 18:26:33'),
(1387, 'LOGIN_LOGOUT', '2.24.166.224', '2017-10-05 16:38:13', 'User has been successfully logged out. userid: 3.'),
(1388, 'LOGIN_SUCCESS', '105.232.61.66', '2017-10-05 16:47:51', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-05 18:47:51'),
(1389, 'LOGIN_LOGOUT', '105.232.61.66', '2017-10-05 16:57:58', 'User has been successfully logged out. userid: 4.'),
(1390, 'LOGIN_SUCCESS', '105.232.29.224', '2017-10-07 16:18:48', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-07 18:18:48'),
(1391, 'LOGIN_SUCCESS', '2.24.166.224', '2017-10-08 16:05:23', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-08 18:05:23'),
(1392, 'LOGIN_SUCCESS', '170.194.32.58', '2017-10-10 06:32:25', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-10 08:32:25'),
(1393, 'LOGIN_LOGOUT', '170.194.32.58', '2017-10-10 06:42:09', 'User has been successfully logged out. userid: 3.'),
(1394, 'LOGIN_SUCCESS', '2.24.166.224', '2017-10-12 19:07:49', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-12 21:07:49'),
(1395, 'LOGIN_LOGOUT', '2.24.166.224', '2017-10-12 19:08:39', 'User has been successfully logged out. userid: 3.'),
(1396, 'LOGIN_SUCCESS', '2.24.166.224', '2017-10-15 18:45:22', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-15 20:45:22'),
(1397, 'LOGIN_LOGOUT', '2.24.166.224', '2017-10-15 18:45:38', 'User has been successfully logged out. userid: 3.'),
(1398, 'LOGIN_SUCCESS', '170.194.32.58', '2017-10-17 14:01:20', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-17 16:01:20'),
(1399, 'LOGIN_LOGOUT', '170.194.32.58', '2017-10-17 14:01:34', 'User has been successfully logged out. userid: 3.'),
(1400, 'LOGIN_SUCCESS', '2.30.139.118', '2017-10-21 13:50:55', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-21 15:50:55'),
(1401, 'LOGIN_LOGOUT', '2.30.139.118', '2017-10-21 13:51:06', 'User has been successfully logged out. userid: 3.'),
(1402, 'LOGIN_SUCCESS', '85.255.234.134', '2017-10-23 20:13:43', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-10-23 22:13:43'),
(1403, 'LOGIN_SUCCESS', '105.232.62.122', '2017-10-29 06:42:43', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-29 08:42:43'),
(1404, 'LOGIN_SUCCESS', '105.232.62.122', '2017-10-29 11:51:42', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-29 13:51:42'),
(1405, 'LOGIN_LOGOUT', '105.232.62.122', '2017-10-29 12:16:20', 'User has been successfully logged out. userid: 1.'),
(1406, 'LOGIN_SUCCESS', '105.232.62.122', '2017-10-29 12:16:28', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-29 14:16:28'),
(1407, 'LOGIN_LOGOUT', '105.232.62.122', '2017-10-29 12:16:51', 'User has been successfully logged out. userid: 1.'),
(1408, 'LOGIN_SUCCESS', '105.232.62.122', '2017-10-29 12:21:08', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-29 14:21:08'),
(1409, 'LOGIN_LOGOUT', '105.232.62.122', '2017-10-29 12:22:28', 'User has been successfully logged out. userid: 1.'),
(1410, 'LOGIN_SUCCESS', '105.232.62.122', '2017-10-30 10:25:32', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-10-30 12:25:32'),
(1411, 'LOGIN_SUCCESS', '105.232.76.2', '2017-11-01 07:20:27', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-01 09:20:27'),
(1412, 'LOGIN_SUCCESS', '105.232.76.2', '2017-11-02 14:31:19', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-02 16:31:19'),
(1413, 'LOGIN_SUCCESS', '148.252.129.168', '2017-11-03 14:03:46', 'User has successfully logged into the system. Details: userid: 3, logintime: 2017-11-03 16:03:46'),
(1414, 'LOGIN_SUCCESS', '105.232.108.199', '2017-11-07 17:42:24', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-07 19:42:24'),
(1415, 'LOGIN_SUCCESS', '105.232.48.129', '2017-11-17 13:59:22', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-17 15:59:22'),
(1416, 'LOGIN_SUCCESS', '105.232.48.129', '2017-11-17 15:48:25', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-17 17:48:25'),
(1417, 'LOGIN_SUCCESS', '105.232.47.168', '2017-11-18 12:31:04', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-18 14:31:04'),
(1418, 'LOGIN_SUCCESS', '105.232.106.150', '2017-11-18 15:01:07', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-18 17:01:07'),
(1419, 'LOGIN_SUCCESS', '105.232.106.150', '2017-11-18 19:38:51', 'User has successfully logged into the system. Details: userid: 1, logintime: 2017-11-18 21:38:51'),
(1420, 'LOGIN_LOGOUT', '105.232.106.150', '2017-11-18 20:12:05', 'User has been successfully logged out. userid: 1.');

-- --------------------------------------------------------

--
-- Table structure for table `system_queries`
--

CREATE TABLE `system_queries` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `_sql` longtext,
  `entrydate` datetime DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_queries`
--

INSERT INTO `system_queries` (`id`, `title`, `description`, `_sql`, `entrydate`, `user_id`, `enabled`) VALUES
(1, 'add crafts', 'add crafts', 'INSERT INTO categories (name)\nVALUES(\'crafts\');', '2017-09-10 16:42:31', 1, 1),
(2, 'Test cat', 'Test cat', 'SELECT c.name, COUNT(si.id) AS total \r\n	        FROM store_items si, \r\n	             categories c\r\n	        WHERE si.category_id = c.id\r\n	        GROUP BY c.name ASC', '2017-09-12 10:55:34', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `truefalse` tinyint(1) DEFAULT NULL,
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `profilepic` varchar(255) DEFAULT '',
  `passwordexpire` tinyint(1) DEFAULT '0',
  `isactive` tinyint(1) DEFAULT '1',
  `roleid` int(2) NOT NULL,
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` timestamp NOT NULL DEFAULT '2017-08-22 00:00:00',
  `lastlogout` timestamp NOT NULL DEFAULT '2017-08-22 00:00:00',
  `sessionid` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `user_password`, `profilepic`, `passwordexpire`, `isactive`, `roleid`, `entrydate`, `lastlogin`, `lastlogout`, `sessionid`) VALUES
(1, 'admin', '2d6f5cdbaba83e02e7b7ad6e04239191', 'profiles/j15iaEW2LgLuzMo.jpg', 0, 1, 1, '2016-10-29 11:14:57', '2017-11-18 21:38:51', '2017-11-18 22:12:05', ''),
(2, 'jj', 'c2aeb4b2266aede828d315e2a9b20845', '', 0, 1, 3, '2017-09-06 20:29:54', '2017-08-22 00:00:00', '2017-10-05 18:13:12', '3neir2boms64oimsho1p0luve0'),
(3, 'Ubaha', 'f8a8686da257f5fba6f0b8a75a119632', 'profiles/0UWPjzxQDmibiZw.jpeg', 0, 1, 3, '2017-09-09 15:40:05', '2017-11-03 16:03:46', '2017-10-21 15:51:06', '3fmjcfup0qqaacckpu806top33'),
(4, 'william', '2d6f5cdbaba83e02e7b7ad6e04239191', 'profiles/mOdbhzsS1L8uZ85.jpeg', 0, 1, 3, '2017-09-14 11:02:08', '2017-08-22 00:00:00', '2017-10-05 18:57:58', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_noticeboard`
--

CREATE TABLE `user_noticeboard` (
  `id` int(5) NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `body` longtext,
  `image` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `userid` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int(5) NOT NULL,
  `userid_from` int(5) NOT NULL,
  `userid_to` int(5) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` longtext,
  `wasread` tinyint(1) DEFAULT '0',
  `entrydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(5) NOT NULL,
  `fname` varchar(20) NOT NULL COMMENT 'First name',
  `sname` varchar(20) NOT NULL COMMENT 'Surname',
  `title` varchar(20) NOT NULL,
  `initials` varchar(10) NOT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `contactno` varchar(20) DEFAULT NULL COMMENT 'Contact #',
  `email` varchar(50) DEFAULT NULL,
  `cellphone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `fname`, `sname`, `title`, `initials`, `dob`, `address`, `contactno`, `email`, `cellphone`) VALUES
(1, 'Admin', 'Admin', 'N/A', 'N/A', '2016-10-29', 'N/A', 'N/A', 'william.sengdara@gmail.com', '0813918334'),
(2, 'JJ', 'JJ', 'Mr.', 'JJ', '2017-09-14', '', '', 'jj@shopbolanle.com', '081'),
(3, 'Ubaha', 'Shipoke', 'Ms.', 'US', '1992-03-04', '', '', 'ubahashipoke@gmail.com', '081'),
(4, 'William', 'Sengdara', 'Mr.', 'FK', '2017-09-20', '', '', 'william.sengdara@gmail.com', '0813918334');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `isactive` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `name`, `isactive`) VALUES
(1, 'administrators', 1),
(3, 'managers', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adverts`
--
ALTER TABLE `adverts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `advert_types`
--
ALTER TABLE `advert_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `col_sizes`
--
ALTER TABLE `col_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_countries`
--
ALTER TABLE `list_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_sex`
--
ALTER TABLE `list_sex`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_visibility`
--
ALTER TABLE `list_visibility`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`quote_id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quote_emails`
--
ALTER TABLE `quote_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_id` (`quote_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD KEY `quote_id` (`quote_id`);

--
-- Indexes for table `shipping_address`
--
ALTER TABLE `shipping_address`
  ADD PRIMARY KEY (`quote_id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `store_items`
--
ALTER TABLE `store_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_visibility_id` (`visibility_id`);

--
-- Indexes for table `store_item_categories`
--
ALTER TABLE `store_item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_bugs`
--
ALTER TABLE `system_bugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_queries`
--
ALTER TABLE `system_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roleid` (`roleid`);

--
-- Indexes for table `user_noticeboard`
--
ALTER TABLE `user_noticeboard`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid_to` (`userid_to`),
  ADD KEY `userid_from` (`userid_from`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adverts`
--
ALTER TABLE `adverts`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `advert_types`
--
ALTER TABLE `advert_types`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `col_sizes`
--
ALTER TABLE `col_sizes`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `list_countries`
--
ALTER TABLE `list_countries`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;
--
-- AUTO_INCREMENT for table `list_sex`
--
ALTER TABLE `list_sex`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `list_visibility`
--
ALTER TABLE `list_visibility`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `quote_emails`
--
ALTER TABLE `quote_emails`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT for table `store_items`
--
ALTER TABLE `store_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `store_item_categories`
--
ALTER TABLE `store_item_categories`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `system_bugs`
--
ALTER TABLE `system_bugs`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1421;
--
-- AUTO_INCREMENT for table `system_queries`
--
ALTER TABLE `system_queries`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_noticeboard`
--
ALTER TABLE `user_noticeboard`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quote_emails`
--
ALTER TABLE `quote_emails`
  ADD CONSTRAINT `quote_emails_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_emails_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD CONSTRAINT `quote_items_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_address`
--
ALTER TABLE `shipping_address`
  ADD CONSTRAINT `shipping_address_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipping_address_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `list_countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_items`
--
ALTER TABLE `store_items`
  ADD CONSTRAINT `FK_visibility_id` FOREIGN KEY (`visibility_id`) REFERENCES `list_visibility` (`id`);

--
-- Constraints for table `system_bugs`
--
ALTER TABLE `system_bugs`
  ADD CONSTRAINT `system_bugs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `system_queries`
--
ALTER TABLE `system_queries`
  ADD CONSTRAINT `system_queries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD CONSTRAINT `system_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_noticeboard`
--
ALTER TABLE `user_noticeboard`
  ADD CONSTRAINT `user_noticeboard_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_ibfk_1` FOREIGN KEY (`userid_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_notifications_ibfk_2` FOREIGN KEY (`userid_from`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
