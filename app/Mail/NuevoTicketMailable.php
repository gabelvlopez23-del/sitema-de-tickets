<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevoTicketMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $descripcion;

    public function __construct($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Ticket Creado en el Sistema',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nuevo_ticket',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
