-- ============================================================
-- neon_schema.sql
-- PostgreSQL Schema for Neon (Serverless Postgres)
-- Compatible with Campus Maintenance System (CMRWAS)
-- ============================================================

-- Table: users
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) DEFAULT 'user',
  status VARCHAR(20) DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: technicians
CREATE TABLE IF NOT EXISTS technicians (
  id SERIAL PRIMARY KEY,
  technician_code VARCHAR(20) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL,
  specialization VARCHAR(50) NOT NULL,
  contact VARCHAR(20) NOT NULL,
  status VARCHAR(20) DEFAULT 'Available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: equipment
CREATE TABLE IF NOT EXISTS equipment (
  id SERIAL PRIMARY KEY,
  equipment_code VARCHAR(20) UNIQUE NOT NULL,
  equipment_name VARCHAR(150) NOT NULL,
  category VARCHAR(100) NOT NULL,
  location VARCHAR(150) NOT NULL,
  condition_status VARCHAR(50) DEFAULT 'Good',
  equipment_status VARCHAR(50) DEFAULT 'Operational',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: maintenance_requests
CREATE TABLE IF NOT EXISTS maintenance_requests (
  id SERIAL PRIMARY KEY,
  request_code VARCHAR(20) UNIQUE NOT NULL,
  user_id INT REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  requester_name VARCHAR(100) NOT NULL,
  location VARCHAR(150) NOT NULL,
  category VARCHAR(50) NOT NULL,
  priority VARCHAR(20) DEFAULT 'Medium',
  description TEXT NOT NULL,
  status VARCHAR(30) DEFAULT 'Pending',
  technician_id INT REFERENCES technicians(id) ON DELETE SET NULL ON UPDATE CASCADE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: transaction_history
CREATE TABLE IF NOT EXISTS transaction_history (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  action VARCHAR(100) NOT NULL,
  request_id INT REFERENCES maintenance_requests(id) ON DELETE SET NULL ON UPDATE CASCADE,
  details TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- View: request_details
CREATE OR REPLACE VIEW request_details AS 
SELECT 
  mr.id AS id,
  mr.request_code AS request_code,
  mr.user_id AS user_id,
  mr.requester_name AS requester_name,
  mr.location AS location,
  mr.category AS category,
  mr.priority AS priority,
  mr.description AS description,
  mr.status AS status,
  mr.technician_id AS technician_id,
  t.name AS technician,
  t.specialization AS specialization,
  u.username AS created_by,
  mr.created_at AS created_at,
  mr.updated_at AS updated_at 
FROM maintenance_requests mr
LEFT JOIN technicians t ON mr.technician_id = t.id
LEFT JOIN users u ON mr.user_id = u.id;

-- View: dashboard_summary
CREATE OR REPLACE VIEW dashboard_summary AS 
SELECT 
  COUNT(0) AS total_requests,
  SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_requests,
  SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress_requests,
  SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_requests 
FROM maintenance_requests;

-- Initial Seed Data
INSERT INTO users (id, full_name, username, email, password, role, status) VALUES
(1, 'Administrator', 'admin', 'admin@cmrwas.com', 'admin123', 'admin', 'active'),
(2, 'Juan Dela Cruz', 'juan', 'juan@example.com', 'user123', 'user', 'active')
ON CONFLICT (username) DO NOTHING;

INSERT INTO technicians (id, technician_code, name, specialization, contact, status) VALUES
(1, 'TECH-001', 'Mark Reyes', 'Electrical', '09123456789', 'Available'),
(2, 'TECH-002', 'John Cruz', 'Plumbing', '09234567890', 'Busy'),
(3, 'TECH-003', 'Michael Santos', 'Carpentry', '09345678901', 'Available')
ON CONFLICT (technician_code) DO NOTHING;

INSERT INTO equipment (id, equipment_code, equipment_name, category, location, condition_status, equipment_status) VALUES
(1, 'EQ-001', 'Air Conditioner', 'HVAC', 'Room 305', 'Good', 'Operational'),
(2, 'EQ-002', 'Ceiling Fan', 'Electrical', 'Room 201', 'Needs Repair', 'Under Maintenance'),
(3, 'EQ-003', 'Water Pump', 'Plumbing', 'Building A', 'Good', 'Operational')
ON CONFLICT (equipment_code) DO NOTHING;

INSERT INTO maintenance_requests (id, request_code, user_id, requester_name, location, category, priority, description, status, technician_id) VALUES
(1, 'MR-001', 2, 'Juan Dela Cruz', 'Room 201', 'Electrical', 'High', 'Broken ceiling light.', 'Pending', NULL),
(2, 'MR-002', 2, 'Juan Dela Cruz', 'Laboratory 1', 'Plumbing', 'Medium', 'Water leaking from sink.', 'Approved', 2),
(3, 'MR-003', 2, 'Juan Dela Cruz', 'Library', 'Furniture', 'Low', 'Broken chair.', 'In Progress', 3),
(4, 'MR-004', 2, 'Juan Dela Cruz', 'Room 305', 'Air Conditioning', 'High', 'Air conditioner not working.', 'Completed', 1)
ON CONFLICT (request_code) DO NOTHING;

-- Reset sequence counters to match highest inserted IDs
SELECT setval('users_id_seq', (SELECT COALESCE(MAX(id), 1) FROM users));
SELECT setval('technicians_id_seq', (SELECT COALESCE(MAX(id), 1) FROM technicians));
SELECT setval('equipment_id_seq', (SELECT COALESCE(MAX(id), 1) FROM equipment));
SELECT setval('maintenance_requests_id_seq', (SELECT COALESCE(MAX(id), 1) FROM maintenance_requests));
SELECT setval('transaction_history_id_seq', (SELECT COALESCE(MAX(id), 1) FROM transaction_history));
