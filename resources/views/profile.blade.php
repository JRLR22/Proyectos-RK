@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header del perfil -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ Auth::user()->full_name }}</h1>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Miembro desde {{ Auth::user()->created_at->locale('es')->isoFormat('MMMM YYYY') }}
                        </p>
                    </div>
                </div>
                <button onclick="openTab(event, 'configuracion')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Configuración
                </button>
            </div>
                                <!-- ⬇BOTÓN DE CERRAR SESIÓN -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 mt-4 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar de navegación -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <nav class="space-y-2">
                        <button onclick="openTab(event, 'pedidos')" class="tab-button w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center active bg-blue-50 text-blue-600 font-semibold">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Mis Pedidos
                        </button>
                        
                        <button onclick="openTab(event, 'direcciones')" class="tab-button w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Mis Direcciones
                        </button>
                        
                        <button onclick="openTab(event, 'facturas')" class="tab-button w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Facturas
                        </button>
                        
                        <button onclick="openTab(event, 'configuracion')" class="tab-button w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Configuración
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="lg:w-3/4">
                <!-- Tab: Mis Pedidos -->
                <div id="pedidos-tab" class="tab-content bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-6">Mis Pedidos</h2>
<div class="space-y-4">
    @forelse($orders as $order)
        <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-lg">{{ $order->order_number }}</h3>
                    <p class="text-sm text-gray-600">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    bg-{{ $order->status_color }}-100
                    text-{{ $order->status_color }}-800">
                    {{ $order->status_label }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">
                        {{ $order->total_items }} artículo(s)
                    </p>
                    <p class="text-xl font-bold text-gray-900">
                        ${{ number_format($order->total, 2) }}
                    </p>
                </div>

                <a href="{{ route('orders.show', $order->order_id) }}"
   class="text-blue-600 hover:underline">
   Ver detalle
</a>

                @if($order->can_be_cancelled)
                    <form method="POST" action="{{ route('orders.cancel', $order->order_id) }}">
                        @csrf
                        @method('PUT')
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                            Cancelar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <h3 class="text-xl font-semibold text-gray-700 mb-2">
                No tienes pedidos
            </h3>
            <a href="{{ route('inicio') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded">
                Explorar Libros
            </a>
        </div>
    @endforelse
</div>
                </div>


                <!-- Tab: Mis Direcciones -->
                <div id="direcciones-tab" class="tab-content bg-white rounded-lg shadow-md p-6 hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Mis Direcciones</h2>
                        <button onclick="openAddressModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar Dirección
                        </button>
                    </div>
                    <div id="direcciones-container">
                        <div class="text-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-500">Cargando direcciones...</p>
                        </div>
                    </div>
                </div>

                <!-- Tab: Facturas -->
                <div id="facturas-tab" class="tab-content bg-white rounded-lg shadow-md p-6 hidden">
                    <h2 class="text-2xl font-bold mb-6">Mis Facturas</h2>
                    <div id="facturas-container">
                        <div class="text-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-500">Cargando facturas...</p>
                        </div>
                    </div>
                </div>

                <!-- Tab: Configuración -->
                <div id="configuracion-tab" class="tab-content bg-white rounded-lg shadow-md p-6 hidden">
                    <h2 class="text-2xl font-bold mb-6">Configuración de Cuenta</h2>
                    
                    <!-- Formulario de actualización de perfil -->
                    <form id="profile-form" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                                <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ Auth::user()->email }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="pt-4 border-t">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                    
                    <!-- Cambiar contraseña -->
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-xl font-bold mb-4">Cambiar Contraseña</h3>
                        <form id="password-form" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                                <input type="password" name="current_password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                                <input type="password" name="new_password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                                <input type="password" name="new_password_confirmation" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                                Actualizar Contraseña
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar dirección -->
<div id="address-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
    <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 my-8 max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 id="address-modal-title" class="text-2xl font-bold">Agregar Dirección</h3>
            <button onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="address-form" class="space-y-4">
            @csrf
            <input type="hidden" name="address_id" id="address_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del destinatario *</label>
                    <input type="text" name="recipient_name" id="recipient_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                    <input type="tel" name="phone" id="phone" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Calle y número *</label>
                <input type="text" name="street_address" id="street_address" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Apartamento/Depto</label>
                    <input type="text" name="apartment" id="apartment"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Colonia</label>
                    <input type="text" name="neighborhood" id="neighborhood"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                    <input type="text" name="city" id="city" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <input type="text" name="state" id="state" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal *</label>
                    <input type="text" name="postal_code" id="postal_code" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Referencias</label>
                <textarea name="references" id="references" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_default" id="is_default" class="mr-2">
                <label for="is_default" class="text-sm text-gray-700">Establecer como dirección predeterminada</label>
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                    Guardar Dirección
                </button>
                <button type="button" onclick="closeAddressModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Sistema de pestañas
function openTab(event, tabName) {
    // Ocultar todos los tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remover clase active de todos los botones
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-50', 'text-blue-600', 'font-semibold');
    });
    
    // Mostrar el tab seleccionado
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Marcar botón como activo
    event.currentTarget.classList.add('active', 'bg-blue-50', 'text-blue-600', 'font-semibold');
    
    // Cargar datos según el tab
    switch(tabName) {
        case 'pedidos':
            loadOrders();
            break;
        case 'favoritos':
            loadFavorites();
            break;
        case 'direcciones':
            loadAddresses();
            break;
        case 'facturas':
            loadInvoices();
            break;
    }
}

// Cargar pedidos
async function loadOrders() {
    try {
        const response = await fetch('/api/orders', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayOrders(data.orders);
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('pedidos-container').innerHTML = `
            <div class="text-center py-12 text-red-600">
                <p>Error al cargar los pedidos</p>
            </div>
        `;
    }
}

function displayOrders(orders) {
    const container = document.getElementById('pedidos-container');
    
    if (orders.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes pedidos</h3>
                <p class="text-gray-500 mb-4">¡Empieza a comprar!</p>
                <a href="{{ route('inicio') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Explorar Libros
                </a>
            </div>
        `;
        return;
    }
    
    const statusColors = {
        'warning': 'bg-yellow-100 text-yellow-800',
        'info': 'bg-blue-100 text-blue-800',
        'primary': 'bg-indigo-100 text-indigo-800',
        'secondary': 'bg-purple-100 text-purple-800',
        'success': 'bg-green-100 text-green-800',
        'danger': 'bg-red-100 text-red-800',
    };
    
    container.innerHTML = `
        <div class="space-y-4">
            ${orders.map(order => `
                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg">${order.order_number}</h3>
                            <p class="text-sm text-gray-600">${order.created_at}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold ${statusColors[order.status_color] || 'bg-gray-100 text-gray-800'}">
                            ${order.status_label}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">${order.total_items} artículo(s)</p>
                            <p class="text-xl font-bold text-gray-900">$${parseFloat(order.total).toFixed(2)}</p>
                        </div>
                        <div class="flex gap-2">
                            ${order.can_be_cancelled ? `
                                <button onclick="cancelOrder(${order.order_id})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                    Cancelar
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Cancelar pedido
async function cancelOrder(orderId) {
    if (!confirm('¿Estás seguro de que deseas cancelar este pedido?')) return;
    
    const reason = prompt('Por favor, indica el motivo de la cancelación (opcional):');
    
    try {
        const response = await fetch(`/orders/${orderId}/cancel`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Pedido cancelado exitosamente');
            loadOrders();
        } else {
            alert(data.message || 'Error al cancelar el pedido');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cancelar el pedido');
    }
}

// Cargar favoritos
async function loadFavorites() {
    try {
        const response = await fetch('/wishlist', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayFavorites(data.wishlist);
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('favoritos-container').innerHTML = `
            <div class="text-center py-12 text-red-600">
                <p>Error al cargar los favoritos</p>
            </div>
        `;
    }
}

function displayFavorites(wishlist) {
    const container = document.getElementById('favoritos-container');
    
    if (wishlist.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes favoritos</h3>
                <p class="text-gray-500 mb-4">Agrega libros a tu lista de deseos</p>
                <a href="{{ route('inicio') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Explorar Libros
                </a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            ${wishlist.map(item => `
                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <img src="${item.book.cover_url}" alt="${item.book.title}" class="w-full h-48 object-cover mb-4 rounded">
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">${item.book.title}</h3>
                    <p class="text-gray-600 text-sm mb-2">${item.book.authors}</p>
                    <p class="text-xl font-bold text-green-600 mb-4">$${parseFloat(item.book.discounted_price || item.book.price).toFixed(2)}</p>
                    
                    <div class="flex gap-2">
                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="book_id" value="${item.book.book_id}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Agregar al Carrito
                            </button>
                        </form>
                        <button onclick="removeFromWishlist(${item.wishlist_id})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            ✕
                        </button>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

async function removeFromWishlist(wishlistId) {
    try {
        const response = await fetch(`/wishlist/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadFavorites();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Cargar direcciones
async function loadAddresses() {
    try {
        const response = await fetch('/api/addresses', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayAddresses(data.addresses);
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('direcciones-container').innerHTML = `
            <div class="text-center py-12 text-red-600">
                <p>Error al cargar las direcciones</p>
            </div>
        `;
    }
}

function displayAddresses(addresses) {
    const container = document.getElementById('direcciones-container');
    
    if (addresses.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes direcciones guardadas</h3>
                <p class="text-gray-500 mb-4">Agrega una dirección para facilitar tus compras</p>
                <button onclick="openAddressModal()" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Agregar Dirección
                </button>
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            ${addresses.map(address => `
                <div class="border rounded-lg p-6 ${address.is_default ? 'border-blue-500 bg-blue-50' : 'hover:shadow-lg'}">
                    ${address.is_default ? '<span class="inline-block bg-blue-600 text-white text-xs px-2 py-1 rounded mb-2">Predeterminada</span>' : ''}
                    <h3 class="font-bold text-lg mb-2">${address.recipient_name}</h3>
                    <p class="text-gray-600 text-sm mb-1">${address.phone}</p>
                    <p class="text-gray-700 text-sm mb-4">${address.full_address}</p>
                    
                    <div class="flex gap-2">
                        <button onclick="editAddress(${address.address_id})" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Editar
                        </button>
                        ${!address.is_default ? `
                            <button onclick="setDefaultAddress(${address.address_id})" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Predeterminada
                            </button>
                            <button onclick="deleteAddress(${address.address_id})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                ✕
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Modal de dirección
function openAddressModal(addressData = null) {
    const modal = document.getElementById('address-modal');
    const form = document.getElementById('address-form');
    const title = document.getElementById('address-modal-title');
    
    if (addressData) {
        title.textContent = 'Editar Dirección';
        document.getElementById('address_id').value = addressData.address_id;
        document.getElementById('recipient_name').value = addressData.recipient_name;
        document.getElementById('phone').value = addressData.phone;
        document.getElementById('street_address').value = addressData.street_address;
        document.getElementById('apartment').value = addressData.apartment || '';
        document.getElementById('neighborhood').value = addressData.neighborhood || '';
        document.getElementById('city').value = addressData.city;
        document.getElementById('state').value = addressData.state;
        document.getElementById('postal_code').value = addressData.postal_code;
        document.getElementById('references').value = addressData.references || '';
        document.getElementById('is_default').checked = addressData.is_default;
    } else {
        title.textContent = 'Agregar Dirección';
        form.reset();
        document.getElementById('address_id').value = '';
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAddressModal() {
    const modal = document.getElementById('address-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function editAddress(addressId) {
    try {
        const response = await fetch(`/addresses/${addressId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            openAddressModal(data.address);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function setDefaultAddress(addressId) {
    try {
        const response = await fetch(`/addresses/${addressId}/set-default`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadAddresses();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteAddress(addressId) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta dirección?')) return;
    
    try {
        const response = await fetch(`/addresses/${addressId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadAddresses();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Submit del formulario de dirección
document.getElementById('address-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const addressId = document.getElementById('address_id').value;
    const url = addressId ? `/addresses/${addressId}` : '/addresses';
    const method = addressId ? 'PUT' : 'POST';
    
    const data = {
        recipient_name: formData.get('recipient_name'),
        phone: formData.get('phone'),
        street_address: formData.get('street_address'),
        apartment: formData.get('apartment'),
        neighborhood: formData.get('neighborhood'),
        city: formData.get('city'),
        state: formData.get('state'),
        postal_code: formData.get('postal_code'),
        references: formData.get('references'),
        is_default: formData.get('is_default') ? true : false
    };
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeAddressModal();
            loadAddresses();
            alert(result.message);
        } else {
            alert('Error al guardar la dirección');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar la dirección');
    }
});

// Cargar facturas
async function loadInvoices() {
    try {
        const response = await fetch('/api/invoices', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayInvoices(data.invoices);
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('facturas-container').innerHTML = `
            <div class="text-center py-12 text-red-600">
                <p>Error al cargar las facturas</p>
            </div>
        `;
    }
}

function displayInvoices(invoices) {
    const container = document.getElementById('facturas-container');
    
    if (invoices.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes facturas</h3>
                <p class="text-gray-500">Las facturas se generan automáticamente después de completar tus compras</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${invoices.map(invoice => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-blue-600">${invoice.invoice_number}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${invoice.order_number}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${invoice.issue_date}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">$${parseFloat(invoice.total).toFixed(2)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <a href="/invoices/${invoice.invoice_id}" class="text-blue-600 hover:text-blue-800">Ver</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="/invoices/${invoice.invoice_id}/download" class="text-green-600 hover:text-green-800">Descargar PDF</a>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

// Actualizar perfil
document.getElementById('profile-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        first_name: formData.get('first_name'),
        last_name: formData.get('last_name'),
        email: formData.get('email'),
        phone: formData.get('phone')
    };
    
    try {
        const response = await fetch('/user/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Perfil actualizado exitosamente');
            location.reload();
        } else {
            alert('Error al actualizar el perfil');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al actualizar el perfil');
    }
});

// Cambiar contraseña
document.getElementById('password-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        current_password: formData.get('current_password'),
        new_password: formData.get('new_password'),
        new_password_confirmation: formData.get('new_password_confirmation')
    };
    
    if (data.new_password !== data.new_password_confirmation) {
        alert('Las contraseñas no coinciden');
        return;
    }
    
    try {
        const response = await fetch('user/password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Contraseña actualizada exitosamente');
            this.reset();
        } else {
            alert(result.message || 'Error al actualizar la contraseña');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al actualizar la contraseña');
    }
});

// Cargar pedidos al iniciar
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
});
</script>

<style>
.tab-button.active {
    background-color: #EFF6FF;
    color: #2563EB;
    font-weight: 600;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection