<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use App\Mail\NuevoTicketMailable;   

class TicketController extends Controller
{
    /**
     * Mostrar listado de tickets según el rol
     */
    public function index()
    {
        $user = Auth::user();

        // El admin y supervisor ven TODOS los tickets. El usuario común solo los suyos.
        if ($user->hasRole(['admin', 'supervisor'])) {
            $tickets = Ticket::with('user')->latest()->get();
        } else {
            $tickets = Ticket::where('user_id', $user->id)->latest()->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Guardar un nuevo ticket en la base de datos y alertar por Email
     */
    public function store(Request $request)
    {
        // Validar que el usuario tenga permiso para crear
        if (!Auth::user()->can('crear tickets')) {
            abort(403, 'No tienes permisos para crear tickets.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
        ]);

        // ENVÍO DE EMAIL VÍA BREVO AL CREAR UN TICKET
        $correoAdmin = 'gabelvlopez.23@gmail.com'; 
        Mail::to($correoAdmin)->send(new NuevoTicketMailable($request->descripcion));

        return redirect()->back()->with('success', 'Ticket creado con éxito y alerta enviada.');
    }

    /**
     * Cerrar un ticket y enviar notificación por email (Brevo)
     */
    public function cerrar(Ticket $ticket)
    {
        if (!Auth::user()->can('cerrar tickets')) {
            abort(403, 'No tienes permisos para cerrar este ticket.');
        }

        $ticket->update(['estado' => 'cerrado']);

        // ENVIAR NOTIFICACIÓN POR EMAIL AL CREADOR DEL TICKET (VÍA BREVO)
        $ticket->user->notify(new \App\Notifications\TicketCerradoNotification($ticket));

        return redirect()->back()->with('success', 'Ticket cerrado correctamente y usuario notificado.');
    }
}
