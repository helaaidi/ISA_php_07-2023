-- PRESENCE TABLE
CREATE TABLE `p5_presence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `digitex` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `cur_day` varchar(100) NOT NULL,
  `cur_time` varchar(100) NOT NULL,
  `in_out` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4


-- DOWNTIMES TABLE
CREATE TABLE `downtimes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `digitex` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `cur_day` varchar(100) NOT NULL,
  `cur_time` varchar(100) NOT NULL,
  `downtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4


-- PERFORMANCES TABLE
CREATE TABLE `p7_op_performance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `op_rfid` varchar(100) NOT NULL,
  `registration_num` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `production_time` float NOT NULL,
  `presence_time` float NOT NULL,
  `downtimes` float NOT NULL,
  `performance` float NOT NULL,
  `cur_day` varchar(100) NOT NULL,
  `cur_time` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4


-- GET NEXT ROW
-- SELECT * FROM table_name WHERE id > _id ORDER BY id ASC LIMIT 1
-- SELECT * FROM foo WHERE id = (SELECT MIN(id) FROM foo WHERE id > 4)

-- GET PREV ROW
-- SELECT * FROM table_name WHERE id < _id ORDER BY id DESC LIMIT 1

-- GET NEXT/PREVIOUS RECORD
-- SELECT name,
--        (SELECT name FROM student s1
--         WHERE s1.id < s.id
--         ORDER BY id DESC LIMIT 1) as previous_name,
--        (SELECT name FROM student s2
--         WHERE s2.id > s.id
--         ORDER BY id ASC LIMIT 1) as next_name
-- FROM student s
--     WHERE id = 7; 


-- UPDATE OPERATION STATES TO 0
UPDATE p3_gamme
SET op_state = 0
WHERE id IN (
	SELECT id FROM p3_gamme WHERE op_state = 1
);