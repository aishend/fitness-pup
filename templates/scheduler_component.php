<?php

function render_filters($action_url, $hidden_inputs, $selects, $show_submit_btn = true, $extra_html = '', $form_id = '') {
    $id_attr = $form_id !== '' ? ' id="' . htmlspecialchars($form_id) . '"' : '';
    echo '<form method="GET" action="' . htmlspecialchars($action_url) . '" class="schedule-filters"' . $id_attr . '>';

    foreach ($hidden_inputs as $name => $value) {
        echo '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '">';
    }

    foreach ($selects as $select) {
        $group_class = isset($select['class']) ? $select['class'] : '';
        echo '<div class="input-group ' . htmlspecialchars($group_class) . '">';
        echo '<label>' . htmlspecialchars($select['label']) . '</label>';

        $onchange = !empty($select['onchange']) ? ' onchange="this.form.submit()"' : '';
        echo '<select name="' . htmlspecialchars($select['name']) . '"' . $onchange . '>';

        foreach ($select['options'] as $option) {
            $selected = ($select['selected'] == $option['value']) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($option['value']) . '" ' . $selected . '>';
            echo htmlspecialchars($option['label']);
            echo '</option>';
        }

        echo '</select>';
        echo '</div>';
    }

    if ($show_submit_btn) {
        echo '<button type="submit" class="btn btn-primary filter-btn">Filter</button>';
    }

    if (!empty($extra_html)) {
        echo $extra_html;
    }

    echo '</form>';
}

function render_week_navigation($base_url, $prev_date, $next_date, $week_display, $week_number, $extra_params_string = '') {
    $prev_url = $base_url . '?date=' . $prev_date . $extra_params_string;
    $next_url = $base_url . '?date=' . $next_date . $extra_params_string;

    echo '<div class="schedule-navigation">';
    echo '<a href="' . htmlspecialchars($prev_url) . '" class="btn btn-primary nav-btn">&larr; Previous Week</a>';

    echo '<div class="current-week">';
    echo '<h2>' . htmlspecialchars($week_display) . '</h2>';
    echo '<p>Week ' . htmlspecialchars($week_number) . '</p>';
    echo '</div>';

    echo '<a href="' . htmlspecialchars($next_url) . '" class="btn btn-primary nav-btn">Next Week &rarr;</a>';
    echo '</div>';
}

function render_schedule($classes_array, $user_role, $my_trainerID = null) {
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    echo '<div class="weekly-grid">';

    foreach ($days as $day) {
        echo '<div class="day-column">';
        echo '<div class="day-header"><h3>' . $day . '</h3></div>';

        $has_classes = false;

        foreach ($classes_array as $class) {
            if ($class['day'] === $day) {
                $has_classes = true;

                $enrolled_class = ($user_role === 'member' && $class['is_enrolled']) ? 'enrolled' : '';

                echo '<div class="class-slot ' . $enrolled_class . '">';
                if (!empty($class['class_image'])) {
                    echo '<img src="img/classes/' . htmlspecialchars($class['class_image']) . '" alt="" class="class-slot-image">';
                }
                echo '<div class="class-time">' . htmlspecialchars($class['time']) . '</div>';
                echo '<h4 class="class-title">' . htmlspecialchars($class['title']) . '</h4>';
                if (!empty($class['trainerUserID'])) {
                    echo '<p class="class-trainer"><a href="profile.php?id=' . (int)$class['trainerUserID'] . '">' . htmlspecialchars($class['trainer']) . '</a></p>';
                } else {
                    echo '<p class="class-trainer">' . htmlspecialchars($class['trainer']) . '</p>';
                }
                echo '<div class="class-capacity">' . (int)$class['booked'] . '/' . (int)$class['capacity'] . ' Spots</div>';

                // Admins can manage all classes; trainers can only manage their own
                $canManage = $user_role === 'admin'
                    || ($user_role === 'trainer' && $my_trainerID !== null && (int)$class['trainerID'] === (int)$my_trainerID);

                if ($canManage) {
                    echo '<div class="card-actions">';

                    $clickParams = sprintf(
                        "openClassModal('edit', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                        (int)$class['classID'],
                        htmlspecialchars($class['title'], ENT_QUOTES),
                        (int)$class['trainerID'],
                        htmlspecialchars($class['type'], ENT_QUOTES),
                        (int)$class['capacity'],
                        htmlspecialchars($class['date'], ENT_QUOTES),
                        htmlspecialchars($class['start_time'], ENT_QUOTES),
                        htmlspecialchars($class['end_time'], ENT_QUOTES),
                        htmlspecialchars($class['class_image'] ?? '', ENT_QUOTES)
                    );

                    echo '<button type="button" onclick="' . $clickParams . '" class="btn btn-action btn-primary">Edit</button>';

                    echo '<form id="delete-class-form-' . (int)$class['classID'] . '" method="POST" action="actions/admin/action_class.php">';
                    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'] ?? '') . '">';
                    echo '<input type="hidden" name="classID" value="' . (int)$class['classID'] . '">';
                    echo '<input type="hidden" name="action" value="remove">';
                    echo '<button type="button" class="btn btn-action btn-danger" onclick="openDeleteClassModal(' . (int)$class['classID'] . ', \'' . htmlspecialchars($class['title'], ENT_QUOTES) . '\')">Delete</button>';
                    echo '</form>';
                    echo '</div>';

                } else {
                    if ($user_role === 'member') {
                        echo '<div class="slot-actions">';
                        if ($class['is_enrolled']) {
                            echo '<form method="POST" action="actions/action_class.php">';
                            echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
                            echo '<input type="hidden" name="classID" value="' . (int)$class['classID'] . '">';
                            echo '<input type="hidden" name="action" value="cancel">';
                            echo '<button type="submit" class="btn btn-cancel">Cancel Enrollment</button>';
                            echo '</form>';
                        } else {
                            echo '<form method="POST" action="actions/action_class.php">';
                            echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
                            echo '<input type="hidden" name="classID" value="' . (int)$class['classID'] . '">';
                            echo '<input type="hidden" name="action" value="enroll">';
                            echo '<button type="submit" class="btn btn-primary">Enroll</button>';
                            echo '</form>';
                        }
                        echo '</div>';
                    } elseif ($user_role === 'guest') {
                        echo '<div class="slot-actions">';
                        echo '<a href="login.php" class="btn btn-primary">Login to Enroll</a>';
                        echo '</div>';
                    }
                    // trainers viewing another trainer's class see nothing
                }

                echo '</div>';
            }
        }

        if (!$has_classes) {
            echo '<p class="no-classes-msg">No classes</p>';
        }

        echo '</div>';
    }
    echo '</div>';
}

function render_pet_schedule($schedule_array, $user_role, $my_pets, $enrolled_pets_by_session, $my_petTrainerID = null) {
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    echo '<div class="weekly-grid">';

    foreach ($days as $day) {
        echo '<div class="day-column">';
        echo '<div class="day-header"><h3>' . $day . '</h3></div>';

        $has_slots = false;

        foreach ($schedule_array as $slot) {
            if ($slot['day'] === $day) {
                $has_slots = true;

                $enrolled_class = ($user_role === 'member' && $slot['is_enrolled'] > 0) ? 'enrolled' : '';
                $is_full = ($slot['booked'] >= $slot['capacity']);
                $trainer_name = isset($slot['trainer']) && !empty($slot['trainer']) ? $slot['trainer'] : 'Staff Member';

                echo '<div class="class-slot ' . $enrolled_class . '">';
                echo '<div class="class-time">' . $slot['time'] . '</div>';
                echo '<h4 class="class-title">' . htmlspecialchars($slot['title'], ENT_QUOTES) . '</h4>';
                $trainer_link = !empty($slot['trainerID'])
                    ? '<a href="profile.php?id=' . (int)$slot['trainerID'] . '">' . htmlspecialchars($trainer_name, ENT_QUOTES) . '</a>'
                    : htmlspecialchars($trainer_name, ENT_QUOTES);
                echo '<p class="class-trainer">Room: ' . htmlspecialchars($slot['room_name'], ENT_QUOTES) . '<br>Trainer: ' . $trainer_link . '</p>';
                echo '<div class="class-capacity">' . $slot['booked'] . '/' . $slot['capacity'] . ' Pets</div>';

                // Admins can manage all sessions; pet-trainers can only manage their own
                $canManage = $user_role === 'admin'
                    || ($user_role === 'pet-trainer' && $my_petTrainerID !== null && (int)$slot['trainerID'] === (int)$my_petTrainerID);

                if ($canManage) {
                    echo '<div class="card-actions">';

                    $clickParams = sprintf(
                        "openPetSessionModal('edit', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                        $slot['reservationID'],
                        htmlspecialchars($slot['title'], ENT_QUOTES),
                        $slot['pet_roomID'],
                        $slot['trainerID'],
                        $slot['capacity'],
                        $slot['date'],
                        $slot['start_time'],
                        $slot['end_time']
                    );

                    echo '<button type="button" onclick="' . $clickParams . '" class="btn btn-action btn-primary">Edit</button>';
                    echo '<button type="button" class="btn btn-action btn-danger" onclick="openDeletePetSessionModal(' . $slot['reservationID'] . ', \'' . htmlspecialchars($slot['title'], ENT_QUOTES) . '\')">Delete</button>';
                    echo '</div>';

                } else {
                    echo '<div class="slot-actions">';
                    if ($user_role === 'member') {
                        $session_enrolled = $enrolled_pets_by_session[$slot['reservationID']] ?? [];
                        $enrolled_json = htmlspecialchars(json_encode($session_enrolled), ENT_QUOTES);
                        $modal_attrs = 'type="button" onclick="openPetBookingModal(this)"'
                            . ' data-session-id="' . $slot['reservationID'] . '"'
                            . ' data-capacity="' . $slot['capacity'] . '"'
                            . ' data-booked="' . $slot['booked'] . '"'
                            . ' data-enrolled="' . $enrolled_json . '"';

                        if ($slot['is_enrolled'] > 0) {
                            echo '<button ' . $modal_attrs . ' class="btn btn-primary">Manage Booking</button>';
                        } elseif ($is_full) {
                            echo '<button class="btn btn-dark" disabled style="opacity:0.5;cursor:not-allowed;">Room Full</button>';
                        } else {
                            echo '<button ' . $modal_attrs . ' class="btn btn-primary">Book Spot</button>';
                        }
                    } elseif ($user_role === 'guest') {
                        echo '<a href="login.php" class="btn btn-primary">Login to Book</a>';
                    }
                    echo '</div>';
                }

                echo '</div>';
            }
        }

        if (!$has_slots) {
            echo '<p class="no-classes-msg">No sessions today</p>';
        }

        echo '</div>';
    }
    echo '</div>';
}

function getWeekDateRange($date_string) {
    $current_date = new DateTime($date_string);
    $start_of_week = clone $current_date;

    if ($start_of_week->format('N') != 1) {
        $start_of_week->modify('last monday');
    }

    $end_of_week = clone $start_of_week;
    $end_of_week->modify('+6 days');

    $prev_week = clone $start_of_week; $prev_week->modify('-7 days');
    $next_week = clone $start_of_week; $next_week->modify('+7 days');

    return [
        'start' => $start_of_week,
        'end_sql' => $end_of_week->format('Y-m-d') . ' 23:59:59',
        'prev_date' => $prev_week->format('Y-m-d'),
        'next_date' => $next_week->format('Y-m-d'),
        'display' => $start_of_week->format('j M') . ' - ' . $end_of_week->format('j M Y'),
        'week_number' => $start_of_week->format('W')
    ];
}
?>