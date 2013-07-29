<?php 

Database::instance(Kohana::TESTING)->query(Database::INSERT, 'INSERT INTO `table1` SET id = 1, name = "test record"');