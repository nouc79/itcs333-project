
CREATE DATABASE IF NOT EXISTS uob_room_booking;
USE uob_room_booking;


CREATE TABLE IF NOT EXISTS rooms (
    id INT(11) NOT NULL AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL,
    floor ENUM('Ground', 'First Floor', 'Second Floor') NOT NULL,
    section ENUM('IS', 'CS', 'CE') NOT NULL,
    capacity INT(11) NOT NULL,
    equipment TEXT NOT NULL,
    available_timeslots TEXT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO rooms (room_number, floor, section, capacity, equipment, available_timeslots) VALUES
('059', 'Ground', 'IS', 20, 'Projector, Whiteboard', '08:00-12:00, 14:00-16:00'),
('1046', 'First Floor', 'CS', 40, 'Smart Board, Projector', '09:00-11:00, 13:00-15:00'),
('2088', 'Second Floor', 'CE', 30, 'Workstations, Smart Board', '10:00-12:00, 15:00-17:00'),
('1065', 'First Floor', 'CS', 30, 'Projector, Whiteboard', '08:00-10:00, 13:00-15:00'),
('2083', 'Second Floor', 'CE', 25, 'Smart Board, Workstations', '09:00-11:00, 14:00-16:00'),
('055', 'Ground', 'CS', 40, 'Projector, Smart Board', '08:00-10:00, 11:00-13:00'),
('1070', 'First Floor', 'CE', 35, 'Projector, Smart Board', '10:00-12:00, 15:00-17:00'),
('2033', 'Second Floor', 'IS', 30, 'Workstations, Whiteboard', '09:00-11:00, 14:00-16:00');
