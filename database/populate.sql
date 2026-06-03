-- 1. Membership Plans 
INSERT INTO MembershipPlans (planID, name, tagline, monthly_price, features, is_featured) VALUES 
(1, 'Paw Starter', 'For the adventurous pup parent', 9.99, '4 Fitness Classes/month,Basic Pet Care (4 hours),Equipment Access', 0),
(2, 'Best Pet Friend', 'Your pup''s best friend too!', 19.99, 'Unlimited Fitness Classes,Full-day Pet Care (8 hours),24/7 Equipment Access,Priority Support', 1),
(3, 'Family Pet Pack', 'For multiple furry friends', 34.99, 'Unlimited Fitness Classes,Unlimited Pet Care for all your pets,24/7 Equipment Access,Priority Support,Family friendly', 0);

-- 2. Users  
INSERT INTO Users (userID, username, password, name, email, profilePhoto, planID, role, bio) VALUES 
(1, 'admin', '$2y$12$aRppgcj5W/j.oWSzbpL2COu7wl1bS8.4Rm9NeS1Q7CUwyOebFfObq', 'Main Admin', 'admin@fitnesspup.com', 'admin.png', NULL, 'admin', 'Fitness Pup system manager.'),
(2, 'trainer', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Default Trainer', 'trainer@fitnesspup.com', 'trainer.png', NULL, 'trainer', 'Ready for the workouts.'),
(3, 'member', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Default Member', 'member@fitnesspup.com', 'member.png', 2, 'member', 'Working out with my dog!'),
(4, 'goncalo_r', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Gonçalo Ribeiro', 'goncalo@fitnesspup.com', 'goncalo.png', NULL, 'pet-trainer', 'Loves keeping puppies active, safe, and happily worn out.'),
(5, 'leonor_a', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Leonor Alpoim', 'leonor@fitnesspup.com', 'leonor.jpg', NULL, 'pet-trainer', 'Ensures every pup gets attention and tail-wagging fun.'),
(6, 'leandro_m', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Leandro Moreira', 'leandro@fitnesspup.com', 'leandro.jpg', NULL, 'pet-trainer', 'Passionate about creating a playful and supervised environment.'),
(7, 'daenerys_t', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Daenerys Targaryen', 'khaleesi@fitnesspup.com', 'daenerys.jpg', NULL, 'trainer', 'Expert in Zumba and high-energy motivation.'),
(8, 'jon_snow', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Jon Snow', 'jon@fitnesspup.com', 'jon.jpg', NULL, 'trainer', 'Specialist in Park HIIT and outdoor circuits.'),
(9, 'sara_m', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Sara Martins', 'sara.m@fitnesspup.com', 'sara.jpg', 1, 'member', 'Enjoys yoga sessions with her golden retriever.'),
(10, 'tiago_c', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Tiago Costa', 'tiago.c@fitnesspup.com', 'tiago.jpg', 2, 'member', 'Focused on strength training and healthy living.'),
(11, 'rita_s', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Rita Silva', 'rita.s@fitnesspup.com', 'rita.jpg', 1, 'member', 'Loves cardio classes and puppy cuddles.'),
(12, 'miguel_p', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Miguel Pereira', 'miguel.p@fitnesspup.com', 'miguel.jpg', 3, 'member', 'Training daily for his first marathon.'),
(13, 'ines_f', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Inês Ferreira', 'ines.f@fitnesspup.com', 'ines.jpg', 2, 'member', 'Believes fitness and pets make life happier.'),
(14, 'joao_r', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'João Rocha', 'joao.r@fitnesspup.com', 'joao.jpg', 1, 'member', 'CrossFit enthusiast and dog lover.'),
(15, 'beatriz_l', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Beatriz Lopes', 'beatriz.l@fitnesspup.com', 'beatriz.jpg', 2, 'member', 'Enjoys pilates and outdoor walks with pets.'),
(16, 'andre_n', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'André Nunes', 'andre.n@fitnesspup.com', 'andre.jpg', 3, 'member', 'Working on consistency and endurance.'),
(17, 'carla_o', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Carla Oliveira', 'carla.o@fitnesspup.com', 'carla.jpg', 1, 'member', 'Loves spinning classes and healthy routines.'),
(18, 'diogo_t', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Diogo Teixeira', 'diogo.t@fitnesspup.com', 'diogo.jpg', 2, 'member', 'Enjoys HIIT and training with friends.'),
(19, 'mariana_g', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Mariana Gomes', 'mariana.g@fitnesspup.com', 'mariana.jpg', 1, 'member', 'Fitness beginner with a passion for animals.'),
(20, 'pedro_a', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Pedro Almeida', 'pedro.a@fitnesspup.com', 'pedro.jpg', 3, 'member', 'Training for better mobility and health.'),
(21, 'ana_v', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Ana Vieira', 'ana.v@fitnesspup.com', 'ana.jpg', 2, 'member', 'Finds balance through yoga and meditation.'),
(22, 'luis_h', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Luís Henriques', 'luis.h@fitnesspup.com', 'luis.jpg', 1, 'member', 'Enjoys lifting weights and long hikes.'),
(23, 'sofia_b', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Sofia Barbosa', 'sofia.b@fitnesspup.com', 'sofia.jpg', 3, 'member', 'Pet lover discovering the joy of fitness.'),
(24, 'fabio_d', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Fábio Dias', 'fabio.d@fitnesspup.com', 'fabio.jpg', 2, 'member', 'Focused on gaining strength and confidence.'),
(25, 'claudia_e', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Cláudia Esteves', 'claudia.e@fitnesspup.com', 'claudia.jpg', 1, 'member', 'Loves energetic group fitness classes.'),
(26, 'rui_k', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Rui Klein', 'rui.k@fitnesspup.com', 'rui.jpg', 2, 'member', 'Trying to stay fit while spoiling his corgi.'),
(27, 'teresa_i', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Teresa Iglesias', 'teresa.i@fitnesspup.com', 'teresa.jpg', 3, 'member', 'Enjoys dance workouts and social classes.'),
(28, 'nuno_x', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Nuno Xavier', 'nuno.x@fitnesspup.com', 'nuno.jpg', 1, 'member', 'Building healthier habits every week.'),
(29, 'patricia_w', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Patrícia Weiss', 'patricia.w@fitnesspup.com', 'patricia.jpg', 2, 'member', 'Loves pets, pilates, and protein shakes.'),
(30, 'david_y', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'David Young', 'david.y@fitnesspup.com', 'david.jpg', 3, 'member', 'Dedicated to improving endurance and energy.'),
(31, 'marta_q', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Marta Queirós', 'marta.q@fitnesspup.com', 'marta.jpg', 2, 'member', 'Enjoys calm workouts and puppy playtime.'),
(32, 'henrique_u', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Henrique Urbano', 'henrique.u@fitnesspup.com', 'henrique.jpg', 1, 'member', 'Gym regular focused on muscle growth.'),
(33, 'eva_z', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Eva Zamora', 'eva.z@fitnesspup.com', 'eva.jpg', 3, 'member', 'Fitness enthusiast and animal rescue volunteer.'),
(34, 'bruno_j', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Bruno Jardim', 'bruno.j@fitnesspup.com', 'bruno.jpg', 1, 'member', 'Enjoys circuit training and outdoor fitness.'),
(35, 'catarina_t', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Catarina Torres', 'catarina.t@fitnesspup.com', 'catarina.jpg', 2, 'member', 'Believes exercise is better with furry friends nearby.'),
(36, 'sergio_m', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Sérgio Monteiro', 'sergio.m@fitnesspup.com', 'sergio.jpg', 3, 'member', 'Focused on discipline, fitness, and wellbeing.'),
(37, 'lara_c', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Lara Cruz', 'lara.c@fitnesspup.com', 'lara.jpg', 1, 'member', 'Enjoys beginner-friendly workout programs.'),
(38, 'vitor_f', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Vítor Freitas', 'vitor.f@fitnesspup.com', 'vitor.jpg', 2, 'member', 'Combining fitness goals with pet parenting.'),
(39, 'alex_fit', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Alex Ferreira', 'alex@fitnesspup.com', 'alex.jpg', NULL, 'trainer', 'Specialist in strength and conditioning.'),
(40, 'monica_yoga', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Mónica Silva', 'monica@fitnesspup.com', 'monica.jpg', NULL, 'trainer', 'Experienced yoga and flexibility instructor.'),
(41, 'ricardo_hiit', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Ricardo Lopes', 'ricardo@fitnesspup.com', 'ricardo.jpg', NULL, 'trainer', 'Passionate about HIIT and endurance training.'),
(42, 'paula_fit', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Paula Mendes', 'paula@fitnesspup.com', 'paula.jpg', NULL, 'trainer', 'Focused on healthy movement and posture.'),
(43, 'daniel_core', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Daniel Costa', 'daniel@fitnesspup.com', 'daniel.jpg', NULL, 'trainer', 'Core training expert and motivational coach.'),
(44, 'filipa_run', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Filipa Rocha', 'filipa@fitnesspup.com', 'filipa.jpg', NULL, 'trainer', 'Running coach with marathon experience.'),
(45, 'marco_box', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Marco Batista', 'marco@fitnesspup.com', 'marco.jpg', NULL, 'trainer', 'Boxing trainer focused on discipline and power.'),
(46, 'juliana_p', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Juliana Pinto', 'juliana@fitnesspup.com', 'juliana.jpg', NULL, 'trainer', 'Encourages confidence through fitness.'),
(47, 'eduardo_s', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Eduardo Santos', 'eduardo@fitnesspup.com', 'eduardo.jpg', NULL, 'trainer', 'Strength training and gym programming specialist.'),
(48, 'raquel_move', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Raquel Moreira', 'raquel@fitnesspup.com', 'raquel.jpg', NULL, 'trainer', 'Dynamic instructor for energetic group classes.'),
(49, 'samuel_fit', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Samuel Neves', 'samuel@fitnesspup.com', 'samuel.jpg', NULL, 'trainer', 'Fitness mentor focused on sustainable progress.'),
(50, 'bea_cardio', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Beatriz Cardoso', 'bea@fitnesspup.com', 'bea.jpg', NULL, 'trainer', 'Cardio and dance fitness enthusiast.'),
(51, 'hugo_power', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Hugo Fernandes', 'hugo@fitnesspup.com', 'hugo.jpg', NULL, 'trainer', 'Powerlifting coach with years of experience.'),
(52, 'ines_balance', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Inês Carvalho', 'ines.c@fitnesspup.com', 'inesc.jpg', NULL, 'trainer', 'Helps members improve flexibility and balance.'),
(53, 'tomas_cycle', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Tomás Ribeiro', 'tomas@fitnesspup.com', 'tomas.jpg', NULL, 'trainer', 'Indoor cycling coach with high-energy classes.'),
(54, 'patricia_strength', '$2y$12$90EO.xkHWJqEL81kH00DIulvwmi/gVLX6EWETLG/uaXhqKIKuTN3K', 'Patrícia Sousa', 'patricia.s@fitnesspup.com', 'patricia_s.jpg', NULL, 'trainer', 'Dedicated to helping members achieve strength goals.');


INSERT INTO MemberStats (userID, workout_count, weekly_streak) VALUES
(3, 43, 12);


INSERT INTO Badges (badgeID, title, description, image_path) VALUES 
(1, 'Early Bird', '5 AM Sessions', 'badge_early_bird.png'),
(2, 'Night Owl', 'Late night workouts', 'to_create.png'),
(3, 'Iron Pup', '100 Workouts completed', 'to_create.png');

INSERT INTO UserBadges (userID, badgeID) VALUES 
(3, 1);

-- 3. Administrators
INSERT INTO Administrators (adminID, userID) VALUES 
(1, 1);

-- 4. Trainers
INSERT INTO Trainers (trainerID, userID, specialty, certifications) VALUES 
(1, 2, 'General Fitness', 'CPT - Certified Personal Trainer'),
(2, 7, 'Cardio & Dance', 'Zumba Certified Instructor'),
(3, 8, 'HIIT', 'CrossFit Level 1'),
(7, 39, 'Strength & Conditioning', 'NSCA-CSCS Certified'),
(8, 40, 'Yoga & Flexibility', 'RYT-200 Yoga Alliance Certified'),
(9, 41, 'HIIT & Endurance', 'CrossFit Level 2'),
(10, 42, 'Movement & Posture', 'NASM Certified Personal Trainer'),
(11, 43, 'Core & Functional Training', 'ACE Certified Personal Trainer'),
(12, 44, 'Running & Endurance', 'USATF Level 1 Running Coach'),
(13, 45, 'Boxing & Conditioning', 'USA Boxing Certified Coach'),
(14, 46, 'General Fitness', 'CPT - Certified Personal Trainer'),
(15, 47, 'Strength & Programming', 'NSCA-CPT Certified'),
(16, 48, 'Group Fitness & Cardio', 'AFAA Group Fitness Certified'),
(17, 49, 'Functional Fitness', 'ACE Certified Personal Trainer'),
(18, 50, 'Cardio & Dance', 'Zumba Certified Instructor'),
(19, 51, 'Powerlifting', 'USAPL Certified Coach'),
(20, 52, 'Flexibility & Balance', 'NASM Corrective Exercise Specialist'),
(21, 53, 'Indoor Cycling', 'Spinning Certified Instructor'),
(22, 54, 'Strength Training', 'NSCA-CSCS Certified'),
-- Pet Trainers
(4, 4, 'Pet Agility & Play', 'Certified Canine Fitness Trainer'),
(5, 5, 'Animal Behaviour', 'Fear Free Certified Professional'),
(6, 6, 'Pet Conditioning', 'Certified Pet Activity Specialist');

-- 5. Pets
INSERT INTO Pets (petID, name, breed, age, vaccinated, ownerID, photo) VALUES 
(1,  'Ghost',   'White Shepherd',     3, 1, 3,  'ghost.png'),
(2,  'Summer',  'Husky',              2, 1, 3,  'summer.png'),
(3,  'Nala',    'Labrador Retriever', 2, 1, 9,  'nala.png'),
(4,  'Rex',     'German Shepherd',    4, 1, 10, 'rex.png'),
(5,  'Mia',     'Beagle',             1, 0, 10, 'mia.png'),
(6,  'Bolinha', 'French Bulldog',     3, 1, 11, 'bolinha.png'),
(7,  'Thor',    'Rottweiler',         5, 1, 12, 'thor.png'),
(8,  'Luna',    'Golden Retriever',   2, 1, 13, 'luna.png'),
(9,  'Duke',    'Doberman',           3, 0, 14, 'duke.png'),
(10, 'Pipoca',  'Poodle',             2, 1, 15, 'pipoca.png'),
(11, 'Bruno',   'Boxer',              4, 1, 16, 'bruno.png'),
(12, 'Mel',     'Cocker Spaniel',     1, 1, 17, 'mel.png'),
(13, 'Zeus',    'Dalmatian',          2, 1, 18, 'zeus.png'),
(14, 'Coco',    'Shih Tzu',           3, 1, 19, 'coco.png'),
(15, 'Rambo',   'Akita',              6, 1, 20, 'rambo.png'),
(16, 'Bella',   'Border Collie',      2, 1, 21, 'bella.png'),
(17, 'Potato',  'Corgi',              3, 1, 26, 'potato.png');

-- 6. Classes
INSERT INTO Classes (classID, name, type, description, class_status, capacity, start_time, end_time, trainerID)
VALUES
(1,  'Zumba Class',            'Cardio',    'Dance your way to fitness!',                                 'upcoming', 20, '2026-06-02 10:30:00', '2026-06-02 11:15:00', 2),
(2,  'Park HIIT Circuit',      'HIIT',      'Intense outdoor training for you and your dog.',            'upcoming', 15, '2026-05-25 17:00:00', '2026-05-25 18:00:00', 3),
(3,  'Morning Yoga Flow',      'Yoga',      'Start your day with calm and focus.',                       'upcoming', 12, '2026-06-03 08:00:00', '2026-06-03 09:00:00', 2),
(4,  'Strength & Tone',        'Strength',  'Full body resistance training session.',                    'upcoming', 10, '2026-06-04 09:00:00', '2026-06-04 10:00:00', 3),
(5,  'Cardio Blast',           'Cardio',    'High energy cardio to burn calories.',                      'upcoming', 18, '2026-06-05 11:00:00', '2026-06-05 12:00:00', 2),
(6,  'Evening HIIT',           'HIIT',      'Push your limits after work.',                              'upcoming', 15, '2026-06-06 18:30:00', '2026-06-06 19:30:00', 3),
(7,  'Pilates Core',           'Pilates',   'Strengthen your core and improve posture.',                 'upcoming', 10, '2026-06-07 10:00:00', '2026-06-07 11:00:00', 2),
(8,  'Afternoon Yoga',         'Yoga',      'Unwind and stretch in the afternoon sun.',                  'upcoming', 12, '2026-06-09 14:00:00', '2026-06-09 15:00:00', 3),
(9,  'Weekend Strength',       'Strength',  'Saturday morning gains session.',                           'upcoming', 10, '2026-06-13 09:30:00', '2026-06-13 10:30:00', 2),

(10, 'Sunrise Mobility',       'Mobility',  'Gentle mobility drills to wake up the body.',               'upcoming', 14, '2026-06-01 07:00:00', '2026-06-01 07:45:00', 2),
(11, 'Lunch Express HIIT',     'HIIT',      'Fast midday HIIT workout.',                                 'upcoming', 16, '2026-06-01 12:30:00', '2026-06-01 13:15:00', 3),
(12, 'Bootcamp Basics',        'Bootcamp',  'Intro bootcamp with full body exercises.',                  'upcoming', 20, '2026-06-01 18:00:00', '2026-06-01 19:00:00', 2),

(13, 'Core & Stretch',         'Pilates',   'Core stability followed by stretching.',                    'upcoming', 11, '2026-06-02 08:00:00', '2026-06-02 08:50:00', 3),
(14, 'Spin Burn',              'Cycling',   'Indoor cycling with intervals and climbs.',                 'upcoming', 14, '2026-06-02 09:00:00', '2026-06-02 09:45:00', 2),
(15, 'Senior Fitness',         'Wellness',  'Low impact guided movement session.',                       'upcoming', 18, '2026-06-02 11:30:00', '2026-06-02 12:15:00', 3),
(16, 'Box Fit',                'Boxing',    'Boxing inspired cardio and coordination drills.',           'upcoming', 16, '2026-06-02 18:00:00', '2026-06-02 19:00:00', 2),
(17, 'Night Flow Yoga',        'Yoga',      'Slow evening yoga to relax and reset.',                     'upcoming', 12, '2026-06-02 20:00:00', '2026-06-02 21:00:00', 3),

(18, 'Power Pilates',          'Pilates',   'Controlled movements focused on strength.',                 'upcoming', 10, '2026-06-03 09:30:00', '2026-06-03 10:20:00', 2),
(19, 'Functional Strength',    'Strength',  'Compound lifts and functional movement patterns.',          'upcoming', 12, '2026-06-03 12:00:00', '2026-06-03 13:00:00', 3),
(20, 'Dance Cardio Mix',       'Cardio',    'Dance combinations with nonstop movement.',                 'upcoming', 22, '2026-06-03 18:30:00', '2026-06-03 19:20:00', 2),
(21, 'Recovery Stretch',       'Mobility',  'Guided stretching for recovery and flexibility.',           'upcoming', 15, '2026-06-03 20:00:00', '2026-06-03 20:40:00', 3),

(22, 'Morning Bootcamp',       'Bootcamp',  'Outdoor-inspired strength and conditioning.',               'upcoming', 18, '2026-06-04 07:30:00', '2026-06-04 08:30:00', 2),
(23, 'Kettlebell Express',     'Strength',  'Short kettlebell session for power and endurance.',         'upcoming', 10, '2026-06-04 10:30:00', '2026-06-04 11:15:00', 3),
(24, 'Beginner Yoga',          'Yoga',      'Accessible yoga for all levels.',                           'upcoming', 14, '2026-06-04 13:00:00', '2026-06-04 14:00:00', 2),
(25, 'HIIT & Core',            'HIIT',      'Intervals plus focused core work.',                         'upcoming', 15, '2026-06-04 18:00:00', '2026-06-04 19:00:00', 3),

(26, 'Cardio Kick',            'Cardio',    'Kickboxing inspired cardio combo.',                         'upcoming', 17, '2026-06-05 08:00:00', '2026-06-05 08:50:00', 2),
(27, 'Barre Balance',          'Barre',     'Low impact barre session for balance and tone.',            'upcoming', 12, '2026-06-05 09:00:00', '2026-06-05 09:50:00', 3),
(28, 'Spin Sprint',            'Cycling',   'High cadence indoor cycling session.',                      'upcoming', 14, '2026-06-05 12:30:00', '2026-06-05 13:15:00', 2),
(29, 'Full Body Circuit',      'Circuit',   'Timed stations for endurance and strength.',                'upcoming', 18, '2026-06-05 18:30:00', '2026-06-05 19:30:00', 3),
(30, 'Sunset Meditation',      'Wellness',  'Breathing and guided meditation to end the day.',          'upcoming', 20, '2026-06-05 20:00:00', '2026-06-05 20:30:00', 2),

(31, 'Saturday Sweat',         'HIIT',      'Weekend HIIT challenge session.',                           'upcoming', 16, '2026-06-06 09:00:00', '2026-06-06 10:00:00', 2),
(32, 'Family Fitness',         'Wellness',  'Light fun session suitable for families.',                  'upcoming', 25, '2026-06-06 11:00:00', '2026-06-06 12:00:00', 3),
(33, 'Glutes & Legs',          'Strength',  'Lower body focused resistance training.',                   'upcoming', 12, '2026-06-06 16:30:00', '2026-06-06 17:30:00', 2),
(34, 'Boxing Drills',          'Boxing',    'Footwork, combos and conditioning.',                        'upcoming', 14, '2026-06-06 19:45:00', '2026-06-06 20:30:00', 3),

(35, 'Sunday Reset Yoga',      'Yoga',      'Gentle weekend reset with mobility and breath.',            'upcoming', 15, '2026-06-07 08:30:00', '2026-06-07 09:30:00', 3),
(36, 'TRX Strength',           'Strength',  'Suspension training for full body control.',                'upcoming', 10, '2026-06-07 11:30:00', '2026-06-07 12:20:00', 2),
(37, 'Pilates Stretch Fusion', 'Pilates',   'Mix of pilates activation and flexibility.',                'upcoming', 11, '2026-06-07 17:00:00', '2026-06-07 17:50:00', 3),

(38, 'Mobility Monday',        'Mobility',  'Joint care and range of motion work.',                      'completed', 14, '2026-05-18 07:30:00', '2026-05-18 08:15:00', 2),
(39, 'Power Hour',             'Strength',  'Strength blocks with progressive overload.',                'completed', 10, '2026-05-18 18:00:00', '2026-05-18 19:00:00', 3),
(40, 'Yoga Restore',           'Yoga',      'Restorative yoga and deep breathing.',                      'completed', 12, '2026-05-19 19:00:00', '2026-05-19 20:00:00', 2),
(41, 'Express Cardio',         'Cardio',    'Short and sharp calorie burning class.',                    'completed', 18, '2026-05-20 12:15:00', '2026-05-20 13:00:00', 3),
(42, 'Cancelled Spin',         'Cycling',   'Cancelled maintenance slot for studio bikes.',              'cancelled', 14, '2026-05-21 18:00:00', '2026-05-21 18:45:00', 2),

(43, 'Morning Pump',           'Strength',  'Upper and lower body pump workout.',                        'upcoming', 12, '2026-06-08 07:45:00', '2026-06-08 08:45:00', 2),
(44, 'Desk Break Mobility',    'Mobility',  'Midday mobility for people who sit all day.',               'upcoming', 20, '2026-06-08 13:00:00', '2026-06-08 13:30:00', 3),
(45, 'Latin Dance Fit',        'Cardio',    'Latin inspired cardio choreography.',                       'upcoming', 24, '2026-06-08 19:00:00', '2026-06-08 20:00:00', 2),

(46, 'Yoga for Beginners',     'Yoga',      'Simple guided poses and breathwork.',                       'upcoming', 16, '2026-06-09 08:00:00', '2026-06-09 09:00:00', 2),
(47, 'MetCon Challenge',       'HIIT',      'Metabolic conditioning in timed rounds.',                   'upcoming', 14, '2026-06-09 10:00:00', '2026-06-09 10:50:00', 3),
(48, 'Core Burner',            'Pilates',   'Focused abdominal and lower back work.',                    'upcoming', 10, '2026-06-09 18:00:00', '2026-06-09 18:45:00', 2),

(49, 'Strength Foundations',   'Strength',  'Learn the basics of safe lifting.',                         'upcoming', 10, '2026-06-10 09:00:00', '2026-06-10 10:00:00', 3),
(50, 'Dance HIIT Fusion',      'HIIT',      'Cardio intervals mixed with dance moves.',                  'upcoming', 18, '2026-06-10 17:30:00', '2026-06-10 18:20:00', 2),
(51, 'Evening Stretch',        'Mobility',  'Light stretching for recovery.',                              'upcoming', 15, '2026-06-10 20:15:00', '2026-06-10 20:50:00', 3);


-- 7. Classes Enrollments
INSERT INTO Classes_Enrollments (enrollmentID, userID, classID, status, enrollment_date) VALUES
(1,  3,  1, 'enrolled', '2026-05-10 09:00:00'),
(2,  3,  2, 'enrolled', '2026-05-19 14:30:00'),
-- Class 1 (Zumba Class)
(3,  9,  1, 'enrolled', '2026-05-08 10:00:00'),
(4,  11, 1, 'enrolled', '2026-05-08 10:15:00'),
(5,  13, 1, 'enrolled', '2026-05-08 11:00:00'),
(6,  15, 1, 'enrolled', '2026-05-09 09:00:00'),
(7,  17, 1, 'enrolled', '2026-05-09 09:30:00'),
(8,  19, 1, 'enrolled', '2026-05-09 14:00:00'),
(9,  21, 1, 'enrolled', '2026-05-10 08:00:00'),
(10, 25, 1, 'enrolled', '2026-05-10 08:45:00'),
-- Class 2 (Park HIIT Circuit)
(11, 10, 2, 'enrolled', '2026-05-17 10:00:00'),
(12, 12, 2, 'enrolled', '2026-05-17 11:00:00'),
(13, 14, 2, 'enrolled', '2026-05-18 09:00:00'),
(14, 16, 2, 'enrolled', '2026-05-18 10:00:00'),
(15, 18, 2, 'enrolled', '2026-05-18 11:00:00'),
(16, 20, 2, 'enrolled', '2026-05-19 09:00:00'),
(17, 22, 2, 'enrolled', '2026-05-19 10:00:00'),
(18, 24, 2, 'enrolled', '2026-05-19 11:00:00'),
-- Class 38 (Mobility Monday)
(19, 9,  38, 'enrolled', '2026-05-14 09:00:00'),
(20, 10, 38, 'enrolled', '2026-05-14 09:30:00'),
(21, 11, 38, 'enrolled', '2026-05-14 10:00:00'),
(22, 12, 38, 'enrolled', '2026-05-15 08:00:00'),
(23, 13, 38, 'enrolled', '2026-05-15 09:00:00'),
(24, 14, 38, 'enrolled', '2026-05-15 10:00:00'),
-- Class 39 (Power Hour)
(25, 15, 39, 'enrolled', '2026-05-14 11:00:00'),
(26, 16, 39, 'enrolled', '2026-05-14 12:00:00'),
(27, 17, 39, 'enrolled', '2026-05-15 11:00:00'),
(28, 18, 39, 'enrolled', '2026-05-15 12:00:00'),
(29, 20, 39, 'enrolled', '2026-05-16 08:00:00'),
(30, 24, 39, 'enrolled', '2026-05-16 09:00:00'),
-- Class 40 (Yoga Restore)
(31, 19, 40, 'enrolled', '2026-05-15 13:00:00'),
(32, 21, 40, 'enrolled', '2026-05-15 14:00:00'),
(33, 22, 40, 'enrolled', '2026-05-16 10:00:00'),
(34, 23, 40, 'enrolled', '2026-05-16 11:00:00'),
(35, 29, 40, 'enrolled', '2026-05-17 08:00:00'),
(36, 31, 40, 'enrolled', '2026-05-17 09:00:00'),
-- Class 41 (Express Cardio)
(37, 25, 41, 'enrolled', '2026-05-16 12:00:00'),
(38, 26, 41, 'enrolled', '2026-05-17 10:00:00'),
(39, 28, 41, 'enrolled', '2026-05-17 11:00:00'),
(40, 30, 41, 'enrolled', '2026-05-18 08:00:00'),
(41, 34, 41, 'enrolled', '2026-05-18 09:00:00'),
(42, 36, 41, 'enrolled', '2026-05-18 10:00:00'),
-- Default member (user 3) enrolled in completed classes
(43, 3, 38, 'enrolled', '2026-05-14 08:00:00'),
(44, 3, 39, 'enrolled', '2026-05-14 08:30:00'),
(45, 3, 40, 'enrolled', '2026-05-15 08:00:00'),
(46, 3, 41, 'enrolled', '2026-05-16 08:00:00');

-- 8. Pet Rooms
INSERT INTO Pet_Rooms (pet_roomID, name, capacity, description, pet_trainer_userID) VALUES 
(1, 'Puppy Playground', 10, 'Safe indoor space with interactive toys.', 6), 
(2, 'Big Paws Yard', 5, 'Fenced outdoor area for large dogs.', 4);   

-- 9. Pet Sessions (Criadas pelos Pet Trainers)
INSERT INTO Pet_Sessions (sessionID, title, start_time, end_time, pet_roomID, trainerID, capacity) VALUES 
-- Sessões na "Puppy Playground" 
(1, 'Morning Playgroup', '2026-05-25 09:00:00', '2026-05-25 12:00:00', 1, 6, 10),
(2, 'Midday Nap & Care', '2026-05-25 12:00:00', '2026-05-25 15:00:00', 1, 5, 10),
(3, 'Afternoon Activities', '2026-05-25 15:00:00', '2026-05-25 18:00:00', 1, 4, 10),

(4, 'Morning Playgroup', '2026-05-26 09:00:00', '2026-05-26 12:00:00', 1, 6, 10),
(5, 'Afternoon Activities', '2026-05-26 15:00:00', '2026-05-26 18:00:00', 1, 4, 10),

-- Sessões no "Big Paws Yard" 
(6, 'Morning Yard Run', '2026-05-25 09:00:00', '2026-05-25 12:00:00', 2, 4, 5),
(7, 'Afternoon Agility', '2026-05-25 16:00:00', '2026-05-25 19:00:00', 2, 5, 5),

(8, 'Morning Yard Run', '2026-05-27 09:00:00', '2026-05-27 12:00:00', 2, 4, 5);


-- 10. Pet Enrollments 
INSERT INTO Pet_Enrollments (enrollmentID, sessionID, petID, status, enrollment_date) VALUES 

(1, 7, 1, 'enrolled', '2026-05-20 14:00:00'),

(2, 1, 2, 'enrolled', '2026-05-20 14:05:00');

-- Equipment Categories (Human)
INSERT INTO EquipmentCategory (categoryID, name, targetGroup) VALUES
(1, 'Cardio', 'Human'),
(2, 'Free Weights', 'Human'),
(3, 'Strength Machines', 'Human'),
(4, 'Functional Training', 'Human'),
-- Equipment Categories (Pet)
(5, 'Agility', 'Pet'),
(6, 'Conditioning', 'Pet'),
(7, 'Recovery & Wellness', 'Pet');

-- 11. Equipment
INSERT INTO Equipment (name, description, categoryID, status, image) VALUES
-- Human Equipment
('Treadmill A', 'Commercial treadmill with incline up to 15%.', 1, 'available', 'treadmill_a.png'),
('Treadmill B', 'Commercial treadmill with incline up to 15%.', 1, 'in_use', 'treadmill_b.png'),
('Treadmill C', 'Commercial treadmill with incline up to 15%.', 1, 'maintenance', 'treadmill_c.png'),
('Rowing Machine', 'Air resistance rowing machine for full body cardio.', 1, 'available', 'rowing_machine.png'),
('Stationary Bike', 'Upright bike with adjustable resistance levels.', 1, 'available', 'stationary_bike.png'),
('Elliptical Trainer', 'Low impact cross trainer for endurance workouts.', 1, 'in_use', 'elliptical_trainer.png'),
('Stair Climber', 'Step machine targeting glutes, quads and calves.', 1, 'available', 'stair_climber.png'),
('Dumbbell Rack (2–40kg)', 'Full rack of rubber-coated dumbbells in pairs.', 2, 'available', 'dumbbell_rack.png'),
('Barbell Set', 'Olympic barbells with full plate selection.', 2, 'available', 'barbell_set.png'),
('EZ Curl Bar', 'Angled bar for bicep and tricep isolation.', 2, 'available', 'ez_curl_bar.png'),
('Kettlebell Set (8–32kg)', 'Cast iron kettlebells in 8 weight variations.', 2, 'in_use', 'kettlebell_set.png'),
('Lat Pulldown Machine', 'Cable-based machine for back and shoulder work.', 3, 'available', 'lat_pulldown.png'),
('Leg Press Machine', 'Plate-loaded leg press for quad and glute development.', 3, 'available', 'leg_press.png'),
('Chest Press Machine', 'Guided press machine for chest and triceps.', 3, 'in_use', 'chest_press.png'),
('Cable Crossover Station', 'Dual pulley system for chest, back and arms.', 3, 'available', 'cable_crossover.png'),
('Smith Machine', 'Guided barbell for squats, presses and lunges.', 3, 'maintenance', 'smith_machine.png'),
('Seated Row Machine', 'Cable row machine targeting mid and lower back.', 3, 'available', 'seated_row.png'),
('Pull-Up Rig', 'Multi-grip pull-up and dip station.', 4, 'available', 'pullup_rig.png'),
('Battle Ropes', 'Heavy duty 15m ropes for conditioning circuits.', 4, 'available', 'battle_ropes.png'),
('TRX Suspension System', 'Bodyweight suspension trainer anchored to ceiling.', 4, 'in_use', 'trx.png'),
('Plyo Boxes (3 sizes)', 'Wooden plyometric boxes for jumps and step-ups.', 4, 'available', 'plyo_boxes.png'),
('Resistance Band Station', 'Wall-mounted anchors with full band set.', 4, 'available', 'resistance_bands.png'),
-- Pet Equipment
('Agility Tunnel', 'Collapsible tunnel for chase and confidence training.', 5, 'available', 'agility_tunnel.png'),
('Weave Poles (set of 6)', 'Slalom poles for coordination and focus drills.', 5, 'available', 'weave_poles.png'),
('Jump Hoops (set of 4)', 'Adjustable height hoops for jump training.', 5, 'in_use', 'jump_hoops.png'),
('Pause Table', 'Elevated platform for stay and control exercises.', 5, 'available', 'pause_table.png'),
('Seesaw Plank', 'Balance seesaw for confidence and body awareness.', 5, 'maintenance', 'seesaw_plank.png'),
('Dog Treadmill', 'Low speed treadmill sized for dogs up to 40kg.', 6, 'available', 'dog_treadmill.png'),
('Balance Discs (set of 4)', 'Inflatable discs for core stability and proprioception.', 6, 'available', 'balance_discs.png'),
('Resistance Drag Harness', 'Lightweight harness with drag attachment for strength walks.', 6, 'in_use', 'drag_harness.png'),
('Ladder Drill Mat', 'Flat rope ladder for paw placement and coordination.', 6, 'available', 'ladder_mat.png'),
('Hydrotherapy Pool', 'Warm water pool for post-training recovery and joint care.', 7, 'available', 'hydrotherapy_pool.png'),
('Massage Mat', 'Textured mat for muscle stimulation and relaxation.', 7, 'available', 'massage_mat.png'),
('Cooling Station', 'Misting fan and cooling mat area for post-session rest.', 7, 'available', 'cooling_station.png'),
('Paw Care Station', 'Equipped with wash basin, towels and balm for paw maintenance.', 7, 'in_use', 'paw_care_station.png');

-- 12. Reviews (linked to Classes_Enrollments for completed classes)
INSERT INTO Reviews (enrollmentID, rating, comment, review_date) VALUES
-- Class 1 (Zumba Class)
(2,  4, 'I liked the music during the workout.',                                                       '2026-06-03 08:00:00'),
(3,  5, 'Amazing energy in this class! The music was perfect and the vibe was incredible.',            '2026-06-03 09:00:00'),
(4,  4, 'Really fun class, loved the music selection. Will definitely come back!',                     '2026-06-03 10:00:00'),
(5,  5, 'Best way to start the morning. Felt energised for the whole day after this!',                 '2026-06-03 11:00:00'),
(6,  4, 'Good workout, felt great afterwards. Trainer has amazing charisma.',                          '2026-06-03 12:00:00'),
(7,  5, 'Loved every second. The choreography was challenging but so fun!',                            '2026-06-04 09:00:00'),
(8,  3, 'Decent class but felt a bit crowded. The moves were easy to follow though.',                  '2026-06-04 10:00:00'),
(9,  5, 'This is my favourite class at FitnessPup. Absolutely incredible!',                           '2026-06-04 11:00:00'),
(10, 4, 'Great session overall. Would have liked a bit more cool down time.',                          '2026-06-04 12:00:00'),
-- Class 2 (Park HIIT Circuit)
(11, 5, 'Jon Snow really pushes you to your limits. Loved every minute of this outdoor HIIT!',        '2026-05-26 09:00:00'),
(12, 4, 'Tough but worth it. Great outdoor atmosphere and well structured intervals.',                 '2026-05-26 10:00:00'),
(13, 5, 'Best HIIT class I have ever taken. Trainer energy was through the roof!',                    '2026-05-26 11:00:00'),
(14, 4, 'Really challenging but I left feeling like a champion. Great class!',                        '2026-05-26 12:00:00'),
(15, 3, 'Pretty intense for a beginner, but I managed. Fun nonetheless.',                             '2026-05-27 09:00:00'),
(16, 5, 'Outdoor setting made all the difference. Will be a regular from now on.',                    '2026-05-27 10:00:00'),
(17, 4, 'Solid workout, great pacing and the trainer was very motivating.',                           '2026-05-27 11:00:00'),
(18, 4, 'Would recommend to anyone looking for a real challenge. Great class!',                       '2026-05-27 12:00:00'),
-- Class 38 (Mobility Monday)
(19, 5, 'Perfect way to start the week. My body felt amazing after these drills.',                    '2026-05-19 09:00:00'),
(20, 4, 'Good mobility drills, learned a lot of new exercises for my routine.',                       '2026-05-19 10:00:00'),
(21, 4, 'Loved the gentle pace and focus on form. Exactly what Monday needs.',                        '2026-05-19 11:00:00'),
(22, 3, 'Good session but I expected more dynamic movements. Still worth it.',                        '2026-05-19 12:00:00'),
(23, 5, 'Outstanding class for joint health and flexibility. Highly recommended!',                    '2026-05-19 13:00:00'),
-- Class 39 (Power Hour)
(25, 5, 'Incredible session! Progressive overload done right. My muscles are still sore!',            '2026-05-19 14:00:00'),
(26, 4, 'Challenging but very satisfying. The rep schemes were perfectly designed.',                  '2026-05-19 15:00:00'),
(27, 3, 'Good workout but quite difficult. Could use more guidance on form.',                         '2026-05-20 09:00:00'),
(28, 5, 'Loved the structure of this class. Felt strong and accomplished after!',                     '2026-05-20 10:00:00'),
(29, 4, 'Great strength session, the trainer knows exactly how to push you safely.',                  '2026-05-20 11:00:00'),
-- Class 40 (Yoga Restore)
(31, 5, 'Exactly what I needed after a long week. So relaxing and restorative!',                      '2026-05-20 12:00:00'),
(32, 5, 'Beautiful class, perfect for finding balance and inner peace.',                              '2026-05-20 13:00:00'),
(33, 4, 'Calm and rejuvenating. Left feeling completely refreshed and at ease.',                      '2026-05-21 09:00:00'),
(34, 5, 'Lovely atmosphere and great instruction. This class is a hidden gem!',                       '2026-05-21 10:00:00'),
(35, 4, 'Very soothing experience. The deep breathing techniques were a highlight.',                  '2026-05-21 11:00:00'),
-- Class 41 (Express Cardio)
(37, 5, 'Quick and efficient, loved the intensity packed into such a short session!',                 '2026-05-21 12:00:00'),
(38, 4, 'Short but effective. Great for my busy schedule. Trainer nailed the pacing.',               '2026-05-21 13:00:00'),
(39, 4, 'Solid cardio session. Trainer kept great energy throughout the whole class.',                '2026-05-22 09:00:00'),
(40, 3, 'Good class but needed slightly more variety in the exercises.',                              '2026-05-22 10:00:00'),
(41, 5, 'Fantastic condensed cardio session. Left feeling pumped and energised!',                    '2026-05-22 11:00:00'),
(42, 4, 'Really appreciated the clear instructions. Great class for all fitness levels.',             '2026-05-22 12:00:00'),
-- Default member reviews for completed classes
(43, 5, 'Great way to kick off the week. My joints feel so much better after this session.',         '2026-05-19 08:30:00'),
(44, 4, 'Solid strength workout. The progressive overload approach really works.',                    '2026-05-19 09:00:00'),
(46, 5, 'Short but incredibly efficient. Perfect for a busy day.',                                    '2026-05-21 09:00:00');