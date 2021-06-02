# kemuri_task
/***   Kemuri Task by Vigneswaran **/

#Note: Analytic functions are used.  MySQL supports basic table partitioning but does not support vertical partitioning ( MySQL 5.6).
#MySQL Version Used: 10.4.15-MariaDB-cll-lve
############################################################################################################################
Manual document attached for further clarifications.
Demo link:
http://codeomega.in/kemuri_task_oops/
############################################################################################################################



Database constants are present in classes/cls_common.php file:
	private $mdbhost = "localhost";
    private $mdbname = "Kemuri";
    private $mdbuser = "root";
    private $mdbpass = "";


The below tables need to be created.

CREATE TABLE IF NOT EXISTS `buyorsell` (
  `bId` int(11) NOT NULL AUTO_INCREMENT,
  `bDate` date NOT NULL,
  `bUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bAmount` float(10,2) NOT NULL,
  `bShareCount` int(5) NOT NULL,
  `bType` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bSellDate` date NOT NULL,
  PRIMARY KEY (`bId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `shareprice` (
  `sId` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(100) NOT NULL,
  `sDate` date NOT NULL,
  `sPrice` float(8,2) NOT NULL,
  PRIMARY KEY (`sId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;


INSERT INTO `buyorsell` (`bId`, `bDate`, `bUpdate`, `bAmount`, `bShareCount`, `bType`, `bName`, `bSellDate`) VALUES
(8, '2020-02-11', '2021-05-24 12:40:59', 67.00, 200, 'Buy', 'sd', '0000-00-00'),
(9, '2020-02-18', '2021-05-30 12:47:36', 102.00, 187, 'Sell', 'sd', '0000-00-00'),
(10, '2020-02-11', '2021-05-31 19:07:37', 1510.00, 200, 'Buy', 'GOOGL', '0000-00-00'),
(11, '2020-02-14', '2021-06-01 19:15:19', 1520.00, 200, 'Buy', 'GOOGL', '2020-02-16');


INSERT INTO `shareprice` (`sId`, `sName`, `sDate`, `sPrice`) VALUES
(2, 'AAPL', '2020-02-11', 320.00),
(3, 'AAPL', '2020-02-13', 324.00),
(4, 'AAPL', '2020-02-15', 319.00),
(5, 'AAPL', '2020-02-18', 319.00),
(6, 'AAPL', '2020-02-19', 323.00),
(7, 'AAPL', '2020-02-21', 313.00),
(8, 'AAPL', '2020-02-23', 320.00),
(9, 'GOOGL', '2020-02-11', 1510.00),
(10, 'GOOGL', '2020-02-12', 1518.00),
(11, 'GOOGL', '2020-02-14', 1520.00),
(12, 'GOOGL', '2020-02-15', 1523.00),
(13, 'GOOGL', '2020-02-16', 1530.00),
(14, 'GOOGL', '2020-02-21', 1483.00),
(15, 'GOOGL', '2020-02-22', 1485.00),
(16, 'MSFT', '2020-02-11', 185.00),
(17, 'MSFT', '2020-02-12', 184.00),
(18, 'MSFT', '2020-02-15', 189.00),
(19, 'MSFT', '2020-02-18', 187.00),
(20, 'MSFT', '2020-02-21', 178.00),
(21, 'MSFT', '2020-02-22', 180.00);

