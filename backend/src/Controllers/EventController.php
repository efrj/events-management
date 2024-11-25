<?php

namespace App\Controllers;

use App\Models\Event;
use App\Services\EventValidator;
use App\Services\ApiResponse;

class EventController
{
    private Event $eventModel;
    
    public function __construct()
    {
        $this->eventModel = new Event();
    }
    
    public function index(): void
    {
        try {
            $events = $this->eventModel->findAll();
            ApiResponse::send($events);
        } catch (\Exception $e) {
            ApiResponse::error('Erro ao buscar eventos', 500);
        }
    }
    
    public function show(int $id): void
    {
        try {
            $event = $this->eventModel->findById($id);
            
            if (!$event) {
                ApiResponse::error('Evento não encontrado', 404);
            }
            
            ApiResponse::send($event);
        } catch (\Exception $e) {
            ApiResponse::error('Erro ao buscar evento', 500);
        }
    }
    
    public function create(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                ApiResponse::error('Dados inválidos', 400);
            }
            
            $errors = EventValidator::validate($data);
            if (!empty($errors)) {
                ApiResponse::send(['errors' => $errors], 422, 'Erro de validação');
            }
            
            $id = $this->eventModel->create($data);
            $event = $this->eventModel->findById($id);
            
            ApiResponse::send($event, 201, 'Evento criado com sucesso');
        } catch (\Exception $e) {
            ApiResponse::error('Erro ao criar evento', 500);
        }
    }
    
    public function update(int $id): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                ApiResponse::error('Dados inválidos', 400);
            }
            
            $event = $this->eventModel->findById($id);
            if (!$event) {
                ApiResponse::error('Evento não encontrado', 404);
            }
            
            $errors = EventValidator::validate($data);
            if (!empty($errors)) {
                ApiResponse::send(['errors' => $errors], 422, 'Erro de validação');
            }
            
            $this->eventModel->update($id, $data);
            $updatedEvent = $this->eventModel->findById($id);
            
            ApiResponse::send($updatedEvent, 200, 'Evento atualizado com sucesso');
        } catch (\Exception $e) {
            ApiResponse::error('Erro ao atualizar evento', 500);
        }
    }
        
    public function delete(int $id): void
    {
        try {
            $event = $this->eventModel->findById($id);
            if (!$event) {
                ApiResponse::error('Evento não encontrado', 404);
            }
            
            $this->eventModel->delete($id);
            ApiResponse::send(null, 200, 'Evento excluído com sucesso');
        } catch (\Exception $e) {
            ApiResponse::error('Erro ao excluir evento', 500);
        }
    }
}
