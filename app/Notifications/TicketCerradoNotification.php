<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCerradoNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu ticket ha sido cerrado - Sistema de Tickets')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Te informamos que tu ticket ha sido resuelto y cerrado por un administrador.')
            ->line('**Detalles del Ticket:**')
            ->line('• **ID:** #' . $this->ticket->id)
            ->line('• **Título:** ' . $this->ticket->titulo)
            ->action('Ver mis tickets', url('/tickets'))
            ->line('Gracias por usar nuestro sistema de soporte.');
    }
}
