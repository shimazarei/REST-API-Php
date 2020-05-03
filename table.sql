USE DB1;

DROP table node_tree;

DROP table node_tree_name;

CREATE TABLE node_tree(iNode INT PRIMARY KEY, level INT, iLeft INT, iRight INT)ENGINE=InnoDB;

CREATE TABLE node_tree_name(Id INT PRIMARY KEY AUTO_INCREMENT,iNode INT , Language VARCHAR(200),nodeName VARCHAR(200), KEY iNode(iNode), CONSTRAINT FK_id FOREIGN KEY (iNode) REFERENCES node_tree (iNode) ON DELETE CASCADE ON UPDATE CASCADE)ENGINE=InnoDB;





