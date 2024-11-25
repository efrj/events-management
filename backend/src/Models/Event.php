<?php

namespace App\Models;

use App\Config\Database;
use App\Interfaces\EventRepositoryInterface;

class Event implements EventRepositoryInterface
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM events 
                  WHERE deleted_at IS NULL 
                  ORDER BY start ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $query = "SELECT * FROM events 
                  WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $query = "INSERT INTO events (title, description, start, end) 
                  VALUES (:title, :description, :start, :end)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'start' => $data['start'],
            'end' => $data['end']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $query = "UPDATE events 
                  SET title = :title, 
                      description = :description, 
                      start = :start, 
                      end = :end 
                  WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'start' => $data['start'],
            'end' => $data['end']
        ]);
    }

    public function delete(int $id): bool
    {
        $query = "UPDATE events 
                  SET deleted_at = CURRENT_TIMESTAMP 
                  WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function hasTimeConflict(string $start, string $end, ?int $excludeId = null): bool
    {
        $query = "SELECT COUNT(*) as count 
                  FROM events 
                  WHERE deleted_at IS NULL 
                  AND (
                      (start BETWEEN :start AND :end)
                      OR (end BETWEEN :start AND :end)
                      OR (:start BETWEEN start AND end)
                      OR (:end BETWEEN start AND end)
                  )";

        $params = [
            'start' => $start,
            'end' => $end
        ];
        
        if ($excludeId !== null) {
            $query .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return (int)$stmt->fetch()['count'] > 0;
    }
}
