INSERT INTO `addresses` VALUES (1,'Test','User','test.user.purchase@example.com','1000','Test Street','','',4,5,'','123123123');
INSERT INTO `clippings` VALUES (1,1,NULL,1,'image','2013-07-05 19:32:09',NULL,0,NULL,1,NULL,NULL,'','normal');
INSERT INTO `email_templates` VALUES (1,'team_scout','approval','Approval','Approval',''),(2,'team_scout','reminder','Reminder','Reminder',''),(3,'team_scout','rejection','Rejection','Rejection',''),(4,'team_scout','follow_up','Follow Up','Follow Up',''),(5,'team_store','welcome','Welcome','Welcome','');
INSERT INTO `field_revisions` VALUES (1,'product',1,'is_sellable','1','2013-07-05 19:32:09'),(2,'product',1,'is_buyable','1','2013-07-05 19:32:09'),(3,'product',1,'status','2','2013-07-05 19:32:09'),(4,'product',1,'storefronts_count','1','2013-07-05 19:32:09'),(5,'product',2,'is_sellable','1','2013-07-05 19:32:09'),(6,'product',2,'is_buyable','1','2013-07-05 19:32:09'),(7,'product',2,'status','2','2013-07-05 19:32:09'),(8,'product',2,'storefronts_count','1','2013-07-05 19:32:09'),(9,'product',3,'is_sellable','1','2013-07-05 19:32:09'),(10,'product',3,'is_buyable','1','2013-07-05 19:32:09'),(11,'product',3,'status','2','2013-07-05 19:32:09'),(12,'product',3,'storefronts_count','1','2013-07-05 19:32:09');
INSERT INTO `folders` VALUES (1,1,NULL,'Favourites','favourites-1','','2013-07-05 19:32:09',NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL),(2,2,NULL,'Favourites','favourites-2','','2013-07-05 19:32:09',NULL,1,0,NULL,NULL,NULL,NULL,NULL,NULL),(3,5,NULL,'Favourites','favourites-3','','2013-07-05 19:32:09',NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL),(4,1712,NULL,'Favourites','favourites-4','','2013-07-05 19:32:09',NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `images` VALUES (1,'','file.jpg','',NULL,1,'2013-07-05 19:32:09','2013-07-05 19:32:09','1',NULL,NULL,'COPYRIGHT !!!',0,0,'',NULL,0,NULL,NULL,'','','local',0,0),(2,'','tmp.jpg','',1,1,'2013-07-05 19:32:09','2013-07-05 19:32:09','2',NULL,NULL,'',0,0,'',1,0,NULL,NULL,'','','local',0,0),(3,'','tmp.jpg','',2,1,'2013-07-05 19:32:09','2013-07-05 19:32:09','3',NULL,NULL,'',0,0,'',2,0,NULL,NULL,'','','local',0,0),(4,'','tmp.jpg','',3,1,'2013-07-05 19:32:09','2013-07-05 19:32:09','4',NULL,NULL,'',0,0,'',3,0,NULL,NULL,'','','local',0,0);
INSERT INTO `locations` VALUES (1,'Everywhere','','region'),(2,'European Union','EU','region'),(3,'France','FR','country'),(4,'United Kingdom','GB','country'),(5,'London','','city'),(6,'Europe - non EU','','region'),(7,'Bulgaria','BG','country'),(8,'United States','','country');
INSERT INTO `locations_branches` VALUES (1,1,0),(2,2,0),(3,3,0),(4,4,0),(5,5,0),(4,5,1),(2,3,1),(2,4,1),(2,5,2),(6,6,0),(7,7,0),(6,7,1),(8,8,0),(1,2,1),(1,3,2),(1,4,2),(1,5,3),(1,6,1),(1,7,2),(1,8,1);
INSERT INTO `nodes` VALUES (1,1,'user',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(2,1,'folder',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(3,2,'user',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',2),(4,2,'folder',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',2),(5,5,'user',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',5),(6,3,'folder',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',5),(7,1712,'user',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1712),(8,4,'folder',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1712),(9,1,'image',0,0,1,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(10,1,'clipping',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(11,1,'storefront',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(12,2,'storefront',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(13,2,'image',0,0,1,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(14,1,'product',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(15,3,'image',0,0,1,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(16,2,'product',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(17,4,'image',0,0,1,'2013-07-05 19:32:09','2013-07-05 19:32:09',1),(18,3,'product',0,0,0,'2013-07-05 19:32:09','2013-07-05 19:32:09',1);
INSERT INTO `product_attribute_groups` VALUES (1,'Colors',0,NULL,'2013-07-05 19:32:09',NULL);
INSERT INTO `product_attributes` VALUES (1,1,'Pink','','2013-07-05 19:32:09','2013-07-05 19:32:09'),(2,1,'Red','','2013-07-05 19:32:09',NULL);
INSERT INTO `product_attributes_product_variations` VALUES (1,1,1),(2,1,2);
INSERT INTO `product_variations` VALUES (1,2,'Pink','50','',''),(2,2,'Orange','80','10',''),(3,3,'Gray','50','',''),(4,3,'Orange','80','10','');
INSERT INTO `products` VALUES (1,'Basket','basket-1',1,2,1,NULL,NULL,NULL,'','',120.00,115.00,NULL,'GBP','',2,'',NULL,NULL,'','',1,1,0,'2013-07-05 19:32:09',NULL,1,0),(2,'Table','table-2',1,3,2,NULL,NULL,NULL,'','',50.00,NULL,NULL,'GBP','',2,'',NULL,NULL,'','',1,1,0,'2013-07-05 19:32:09',NULL,1,0),(3,'Chair','chair-3',1,4,3,NULL,NULL,NULL,'','',50.00,NULL,NULL,'GBP','',2,'',NULL,NULL,'','',1,1,0,'2013-07-05 19:32:09',NULL,1,0);
INSERT INTO `products_purchases` VALUES (1,2,3,50.00,1,1,'GBP',2);
INSERT INTO `products_storefronts` VALUES (1,1,1,NULL),(2,2,1,NULL),(3,3,1,NULL);
INSERT INTO `purchases` VALUES (1,1,NULL,'123456','emp','2013-07-05 19:32:10','2013-07-05 19:32:10','','',NULL,0,'26C6KO','paid',NULL,1,1);
INSERT INTO `roles` VALUES (1,'login','','[]','[]'),(2,'admin','','[\"admin\\\\\\/*\"]','[]');
INSERT INTO `roles_users` VALUES (1,1),(1,2),(2,1),(5,1),(5,2),(1712,1),(1712,2);
INSERT INTO `search_items` VALUES (1,2,'magazine','Favourites','\n\n'),(2,6,'magazine','Favourites','\n\n'),(3,8,'magazine','Favourites','\n\n'),(4,11,'storefronts','My Storefront','\n'),(5,12,'storefronts','My Storefront 2','\n'),(6,14,'shop','Basket','\n\n\n\nMy Storefront'),(7,16,'shop','Table','\n\n\n\nMy Storefront'),(8,18,'shop','Chair','\n\n\n\nMy Storefront');
INSERT INTO `searchable_terms_items` VALUES (1,9,'node',2),(2,9,'node',4),(3,10,'node',1),(5,10,'node',2),(6,10,'node',4),(4,10,'node',5);
INSERT INTO `shipping_methods` VALUES (1,'Courier','2013-07-05 19:32:09','2013-07-05 19:32:09',1),(2,'Freight','2013-07-05 19:32:09','2013-07-05 19:32:10',1),(3,'Post','2013-07-05 19:32:09',NULL,NULL);
INSERT INTO `shipping_methods_storefront_shippings` VALUES (1,1,1),(2,2,2),(3,2,3);
INSERT INTO `storefront_purchases` VALUES (1,1,1,150.00,40.00,'paid','EHF7Z3','','',NULL,NULL,'2013-07-05 19:32:10','2013-07-05 19:32:10',0,NULL);
INSERT INTO `storefront_shipping_locations` VALUES (1,NULL,1,5,10.00,10.00,NULL,1,1,2),(2,NULL,NULL,NULL,20.00,10.00,NULL,2,2,1),(3,NULL,NULL,NULL,20.00,10.00,NULL,2,3,3);
INSERT INTO `storefront_shippings` VALUES (1,'Fast',1,NULL,NULL,NULL,NULL,NULL),(2,'Fast',1,NULL,NULL,NULL,NULL,NULL),(3,'Fast',1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `storefronts` VALUES (1,'mystorefront','My Storefront',1712,'published','','','',85.00,'2013-07-05 19:32:09','2013-07-05 19:32:09','store@example.com',0,1,NULL,'mystorefront@paypal.com',NULL,0,'2013-07-05 19:32:09',0,'','VAT',20.00,NULL,NULL,1,4,'paypal','','',NULL,'',''),(2,'normalstorefront','My Storefront 2',1712,'pending_invitation','','',NULL,85.00,'2013-07-05 19:32:09','2013-07-05 19:32:09','',0,1,NULL,'mystore2front@paypal.com',NULL,0,'2013-07-05 19:32:09',0,'','VAT',20.00,NULL,NULL,0,4,'paypal','','',NULL,'','');
INSERT INTO `terms` VALUES (1,NULL,'Living Room',1,NULL,0,NULL,'living-room','2013-07-05 19:32:09','2013-07-05 19:32:09'),(2,NULL,'Outdoors',1,NULL,0,1,'outdoors','2013-07-05 19:32:09','2013-07-05 19:32:09'),(3,NULL,'Kitchen',1,NULL,0,2,'kitchen','2013-07-05 19:32:09',NULL),(4,NULL,'Traditional',2,NULL,0,3,'traditional','2013-07-05 19:32:09','2013-07-05 19:32:09'),(5,NULL,'Eclectic',2,NULL,0,4,'eclectic','2013-07-05 19:32:09','2013-07-05 19:32:09'),(6,NULL,'Drawing',3,NULL,0,5,'drawing','2013-07-05 19:32:09',NULL),(7,NULL,'Product',3,NULL,0,6,'product','2013-07-05 19:32:09',NULL),(8,NULL,'Designers Block',4,NULL,0,7,'source-designersblock','2013-07-05 19:32:09',NULL),(9,NULL,'Furniture',5,NULL,0,8,'furniture','2013-07-05 19:32:09',NULL),(10,NULL,'Sofas',NULL,9,0,NULL,'sofas','2013-07-05 19:32:09',NULL);
INSERT INTO `terms_items` VALUES (3,1,9,'node'),(5,1,10,'node'),(1,2,9,'node'),(2,4,9,'node'),(4,5,9,'node'),(6,5,10,'node');
INSERT INTO `test_authors` VALUES (1,'Jonathan Geiger','jonathan@jonathan-geiger.com','',1),(2,'Paul Banks','paul@banks.com','',NULL),(3,'Bobby Tables','bobby@sql-injection.com','',2);
INSERT INTO `test_blogs` VALUES (1,'Flowers blog','http://flowers.wordpress.com/',1,1),(2,'Awesome programming','http://programming-blog.com',3,0),(3,'Tabless','http://bobby-tables-ftw.com',1,1);
INSERT INTO `test_blogs_test_tags` VALUES (1,1,1),(2,2,1),(3,3,2),(4,4,2),(5,5,3),(6,6,3);
INSERT INTO `test_categories` VALUES (1,'Category One',NULL,0,1,1),(2,'Category Two',NULL,1,1,1),(3,'Category Three',1,0,1,2),(4,'Category Four',NULL,1,1,3),(5,'Category Five',3,0,NULL,NULL);
INSERT INTO `test_categories_test_posts` VALUES (1,1),(2,1),(3,1),(2,2);
INSERT INTO `test_closurelists` VALUES (1,'One'),(2,'Two'),(3,'Three'),(4,'Four'),(5,'Five'),(6,'Six'),(7,'Seven'),(10,'Ten'),(11,'Eleven');
INSERT INTO `test_closurelists_branches` VALUES (1,1,0),(2,2,0),(3,3,0),(2,3,1),(4,4,0),(5,5,0),(6,6,0),(4,5,1),(4,6,1),(1,2,1),(1,3,2),(1,4,1),(1,5,2),(1,6,2),(7,7,0),(10,10,0),(11,11,0),(10,11,1);
INSERT INTO `test_copyrights` VALUES (1,'My Copyright',1);
INSERT INTO `test_elements` VALUES (1,'Part 1','http://parts.wordpress.com/','staff@example.com','Big Part',20,1),(2,'Part 2','http://parts.wordpress.com/','staff@example.com','Small Part',10,1);
INSERT INTO `test_images` VALUES (1,'file.jpg',1,'test_post'),(2,'file2.jpg',1,'test_author'),(3,'file3.jpg',NULL,NULL);
INSERT INTO `test_positions` VALUES (1,'Staff'),(2,'Freelancer');
INSERT INTO `test_posts` VALUES (1,'First Post','first-post','draft',1373052730,1264985737,NULL,1,1,NULL,NULL),(2,'Second Post','first-post','review',1373052730,1264985740,NULL,1,3,1,NULL),(3,'Third Post','third-post','draft',1373052730,1264985740,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `test_roles` VALUES (1,'login','Login Permssion'),(2,'admin','Admin Permssion');
INSERT INTO `test_roles_users` VALUES (1,1),(1,2),(2,2);
INSERT INTO `test_tags` VALUES (1,'red','red',1),(2,'green','green',1),(3,'orange','orange',2),(4,'--black','black',1),(5,'* List 1','list-1',1),(6,'* List 2','list-2',1);
INSERT INTO `test_user_tokens` VALUES (1,1,'92b1e2f536fa11fa996731b98b219f837d4436c8','4c14538f3c4a3b8cf30086958911d0d0ae1b2eb7','',1373052730,1373068800),(2,2,'92b1e2f536fa11fa996731b98b219f837d4436c8','f0dc12b77cf0214fbd04e2a224422f44adc7652c','',1373052730,1373068800),(3,1,'92b1e2f536fa11fa996731b98b219f837d4436c8','59ed73c1a3c105e7409c69c21770d674949c07e9','',1373052730,1372102330);
INSERT INTO `test_users` VALUES (1,'admin@example.com','admin','519b05d6ffcab58b7525cdea9c58a8fdb4584e3bd41427db1fcc20ef05dafad6',5,1370460730,'facebook-test','','10.20.10.1'),(2,'user@example.com','user','519b05d6ffcab58b7525cdea9c58a8fdb4584e3bd41427db1fcc20ef05dafad6',20,1372102330,'','','10.20.10.2');
INSERT INTO `test_videos` VALUES (1,'video.jpg',1,'test_post',0,1,'1','one'),(2,'video2.jpg',NULL,NULL,0,0,'2','one'),(3,'video3.jpg',1,'test_post',1,3,'video3-jpg-3','two'),(4,'video4.jpg',1,'test_post',0,3,'4','two'),(5,'video5.jpg',1,'test_post',0,3,'5','one');
INSERT INTO `test_vocabularies` VALUES (1,'Types',0,0,'2013-07-05 19:32:10',NULL),(2,'Styles',0,0,'2013-07-05 19:32:10',NULL);
INSERT INTO `users` VALUES (1,'admin@example.com','administrator','',0,NULL,'','','','','2013-07-05 19:32:09',NULL,NULL,'','administrator-1','','','',0,NULL,1,1,NULL,'',0,NULL,'','1',''),(2,'user@example.com','user','',0,NULL,'','','','','2013-07-05 19:32:09',NULL,NULL,'','user-2','','','',0,NULL,1,1,NULL,'',0,NULL,'','1',''),(5,'ant@example.com','Ant','',0,NULL,'','','','','2013-07-05 19:32:09',NULL,NULL,'','ant-5','','','',0,NULL,1,1,NULL,'',0,NULL,'','1',''),(1712,'tom@example.com','mallorytom','',0,NULL,'','','','','2013-07-05 19:32:09',NULL,NULL,'','mallorytom-1712','','','',0,NULL,1,1,NULL,'',0,NULL,'','1','');
INSERT INTO `vocabularies` VALUES (1,'Spaces',0,0,'2013-07-05 19:32:09',NULL),(2,'Styles',0,0,'2013-07-05 19:32:09',NULL),(3,'Types',0,0,'2013-07-05 19:32:09',NULL),(4,'Store Sources',0,0,'2013-07-05 19:32:09',NULL),(5,'Product Types',0,0,'2013-07-05 19:32:09',NULL);