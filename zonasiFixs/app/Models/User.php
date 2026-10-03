<?php
// User Model
class User {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    // Find user by ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Find user by NISN
    public function findByNisn($nisn) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE nisn = ?");
        $stmt->execute([$nisn]);
        return $stmt->fetch();
    }

    // Find user by NIK
    public function findByNik($nik) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE nik = ?");
        $stmt->execute([$nik]);
        return $stmt->fetch();
    }

    // Authenticate user
    public function authenticate($nisn, $password) {
        $user = $this->findByNisn($nisn);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    // Create new user
    public function create($data) {
        $sql = "INSERT INTO users (nisn, nik, nama, password, email, no_hp) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nisn'],
            $data['nik'],
            $data['nama'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['email'] ?? null,
            $data['no_hp'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    // Update user
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'password') {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    // Update password
    public function updatePassword($id, $password) {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }
}
