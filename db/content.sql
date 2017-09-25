-- Le scénario est le suivant : 6 amis, bob, max, jean, sa copine, fred et sa femme partent à la montagne.
-- jean et fred paient pour leurs dames. Ils font le plein, louent le chalet, mangent et prennent le Télécabine.
-- Ces 4 dépenses concernent tout le monde. Comme ils ne s'entendent pas sur tout, ils ne font pas le reste ensemble.
-- Les mecs, fidèles à eux-mêmes, se boivent des bières en arrivant. bob et jean vont au téléscope. Les dames vont 
-- dans les grottes. max et fred font du VTT. Le lendemain, ils vont tous ensemble à Aquaparc et se mangent une 
-- fondue, avant de rentrer.

insert into pcea.users (username, password, salt, role) values ("bob", '$2y$13$F9v8pl5u5WMrCorP9MLyJeyIsOLj.0/xqKd/hqa5440kyeB7FQ8te', 'YcM=A$nsYzkyeDVjEUa7W9K', 'ROLE_USER');
insert into pcea.users (username, password, salt, role) values ("max", '$2y$13$F9v8pl5u5WMrCorP9MLyJeyIsOLj.0/xqKd/hqa5440kyeB7FQ8te', 'YcM=A$nsYzkyeDVjEUa7W9K', 'ROLE_USER');
insert into pcea.users (username, password, salt, role) values ("jean", '$2y$13$F9v8pl5u5WMrCorP9MLyJeyIsOLj.0/xqKd/hqa5440kyeB7FQ8te', 'YcM=A$nsYzkyeDVjEUa7W9K', 'ROLE_USER');
insert into pcea.users (username, password, salt, role) values ("fred", '$2y$13$F9v8pl5u5WMrCorP9MLyJeyIsOLj.0/xqKd/hqa5440kyeB7FQ8te', 'YcM=A$nsYzkyeDVjEUa7W9K', 'ROLE_USER');

insert into pcea.events (name, currency) values ("Weekend montagne", "CHF");

insert into pcea.users_has_events (users_id, events_id) values (1, 1);
insert into pcea.users_has_events (users_id, events_id) values (2, 1);
insert into pcea.users_has_events (users_id, events_id) values (3, 1);
insert into pcea.users_has_events (users_id, events_id) values (4, 1);

insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Essence", 50.5, NOW(), 1, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Location chalet", 300, NOW(), 2, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Bouffe", 75, NOW(), 3, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Télécabine", 60, NOW(), 4, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Bières au sommet", 42, NOW(), 1, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Entrées téléscope", 30, NOW(), 3, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Visite des grottes", 20, NOW(), 3, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("VTT", 27, NOW(), 4, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Aquaparc", 150, NOW(), 4, 1);
insert into pcea.spents (name, amount, insert_date, buyer, events_id) values ("Fondue", 80, NOW(), 2, 1);

-- essence
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 1, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 1, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 1, 2);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 1, 2);

-- chalet
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 2, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 2, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 2, 2);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 2, 2);

-- bouffe
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 3, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 3, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 3, 2);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 3, 2);

-- télécabine
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 4, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 4, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 4, 2);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 4, 2);

-- bières
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 5, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 5, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 5, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 5, 1);

-- téléscope
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 6, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 6, 1);

-- grottes
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 7, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 7, 1);

-- vtt
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 8, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 8, 1);

-- aquaparc
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 9, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 9, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 9, 2);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 9, 2);

-- fondue
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (1, 10, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (2, 10, 1);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (3, 10, 2);
insert into pcea.users_has_spents (users_id, spents_id, user_weight) values (4, 10, 2);
