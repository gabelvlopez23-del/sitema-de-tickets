<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sistema de Gestión de Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Mensaje de Éxito -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulario de Creación (Solo para el rol con permiso) -->
            @can('crear tickets')
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Crear un Nuevo Ticket</h3>
                    <form action="{{ route('tickets.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Título</label>
                            <input type="text" name="titulo" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Descripción</label>
                            <textarea name="descripcion" rows="3" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150">
                            Enviar Ticket
                        </button>
                    </form>
                </div>
            @endcan

            <!-- Tabla de Listado de Tickets -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Listado de Tickets</h3>
                
                @if($tickets->isEmpty())
                    <p class="text-gray-500 text-sm">No hay tickets registrados en este momento.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creador</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">#{{ $ticket->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $ticket->user->name }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $ticket->titulo }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $ticket->descripcion }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ticket->estado === 'abierto' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($ticket->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            @can('cerrar tickets')
                                                @if($ticket->estado === 'abierto')
                                                    <form action="{{ route('tickets.cerrar', $ticket) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                                            Cerrar Ticket
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400">Cerrado</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">No autorizado</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
