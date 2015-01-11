CREATE DATABASE IF NOT EXISTS `graph` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `graph`;

CREATE TABLE IF NOT EXISTS `Nodes` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO `Nodes` (`id`, `name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E');

CREATE TABLE IF NOT EXISTS `Edges` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `in_node` int(11) NOT NULL,
  `out_node` int(11) NOT NULL,
  `length` int(11) NOT NULL,

  FOREIGN KEY (in_node) REFERENCES Nodes(id),
  FOREIGN KEY (out_node) REFERENCES Nodes(id)
) ENGINE=InnoDB;

INSERT INTO `Edges` (`id`, `in_node`, `out_node`, `length`) VALUES
(1, 1, 2, 10),
(2, 1, 3, 20),
(3, 4, 5, 25);
