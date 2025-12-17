<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - Librerías Gonvill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    
    <!-- PayPal JS SDK V2 -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=MXN&locale=es_MX"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Finalizar Compra</h1>

        <!-- Dirección de Envío -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Dirección de Envío</h2>
    
    @if($addresses->count() > 1)
        <div class="space-y-3">
            @foreach($addresses as $address)
                <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ $address->is_default ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input 
                        type="radio" 
                        name="address_id" 
                        value="{{ $address->address_id }}"
                        {{ $address->is_default ? 'checked' : '' }}
                        onchange="updateOrderAddress({{ $address->address_id }})"
                        class="mt-1"
                    >
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $address->recipient_name }}</p>
                        <p class="text-sm text-gray-600">{{ $address->street_address }}</p>
                        @if($address->apartment)
                            <p class="text-sm text-gray-600">{{ $address->apartment }}</p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                        <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                    </div>
                </label>
            @endforeach
        </div>
    @else
        <div class="p-4 border rounded-lg">
            <p class="font-semibold text-gray-800">{{ $selectedAddress->recipient_name }}</p>
            <p class="text-sm text-gray-600">{{ $selectedAddress->full_address }}</p>
            <p class="text-sm text-gray-500">{{ $selectedAddress->phone }}</p>
        </div>
    @endif
    
    <a href="{{ route('profile') }}" class="text-blue-600 hover:underline text-sm mt-3 inline-block">
        + Agregar nueva dirección
    </a>
</div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario de pago -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Método de Pago</h2>

                    <!-- Mensajes de alerta -->
                    <div id="payment-alert" class="hidden mb-4 p-4 rounded"></div>

                    <!-- Selector de método -->
                    <div class="mb-6">
                        <div class="flex gap-4">
                            <button onclick="selectPaymentMethod('stripe')" 
                                    id="btn-stripe"
                                    class="flex-1 p-4 border-2 border-gray-300 rounded-lg hover:border-blue-500 transition">
                                <i class="fas fa-credit-card text-2xl text-blue-600 mb-2"></i>
                                <p class="font-semibold">Tarjeta de Crédito/Débito</p>
                                <p class="text-xs text-gray-500">Procesado por Stripe</p>
                            </button>

                            <button onclick="selectPaymentMethod('paypal')" 
                                    id="btn-paypal"
                                    class="flex-1 p-4 border-2 border-gray-300 rounded-lg hover:border-blue-500 transition">
                                <i class="fab fa-paypal text-2xl text-blue-700 mb-2"></i>
                                <p class="font-semibold">PayPal</p>
                                <p class="text-xs text-gray-500">Pago seguro</p>
                            </button>
                        </div>
                    </div>

                    <!-- Formulario de Stripe -->
                    <div id="stripe-form" class="hidden">
                        <h3 class="font-semibold text-gray-700 mb-4">Información de la Tarjeta</h3>
                        
                        <div id="card-element" class="p-4 border border-gray-300 rounded-lg mb-4"></div>
                        <div id="card-errors" class="text-red-500 text-sm mb-4"></div>

                        <button onclick="processStripePayment()" 
                                id="stripe-submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold">
                            <i class="fas fa-lock mr-2"></i>
                            Pagar ${{ number_format($summary['total'], 2) }} MXN
                        </button>

                        <!-- Tarjetas de prueba -->
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm font-semibold text-yellow-800 mb-2">🧪 Tarjetas de Prueba:</p>
                            <ul class="text-xs text-yellow-700 space-y-1">
                                <li><strong>Éxito:</strong> 4242 4242 4242 4242</li>
                                <li><strong>Declinada:</strong> 4000 0000 0000 0002</li>
                                <li>Fecha: Cualquier futura | CVC: Cualquier 3 dígitos</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Botón de PayPal -->
                    <div id="paypal-form" class="hidden">
                        <div id="paypal-button-container" class="mb-4"></div>
                        
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm font-semibold text-blue-800 mb-2">🧪 PayPal Sandbox:</p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>Usa las credenciales de tu cuenta sandbox de PayPal</li>
                                <li>Crea cuentas de prueba en: developer.paypal.com</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div id="payment-loading" class="hidden text-center py-8">
                        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
                        <p class="text-gray-600">Procesando pago...</p>
                    </div>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Resumen del Pedido</h3>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span>${{ number_format($summary['subtotal'], 2) }}</span>
                        </div>
                        @if($summary['discount'] > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Descuento:</span>
                            <span>-${{ number_format($summary['discount'], 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-gray-600">
                            <span>IVA (16%):</span>
                            <span>${{ number_format($summary['tax'] ?? 0, 2) }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between text-xl font-bold text-gray-800">
                            <span>Total:</span>
                            <span>${{ number_format($summary['total'], 2) }} MXN</span>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="border-t pt-4">
                        <h4 class="font-semibold text-gray-700 mb-3">Productos ({{ $summary['items_count'] }})</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($cart as $item)
                            <div class="flex gap-2 text-sm">
                                <img src="{{ $item['cover_url'] ?? asset('img/no-image.png') }}" 
                                     class="w-12 h-16 object-cover rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 line-clamp-2">{{ $item['title'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['quantity'] }} x ${{ number_format($item['price'], 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Seguridad -->
                    <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-lock"></i>
                        <span>Pago 100% seguro y encriptado</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuración
        const orderId = {{ $order_id }};
        const stripeKey = "{{ config('services.stripe.key') }}";
        
        // Variables globales
        let stripe, cardElement, paymentMethod = null;
        let paypalOrderId = null;

        // ==================== FUNCIONES AUXILIARES ====================

        function showAlert(message, type = 'error') {
            const alertDiv = document.getElementById('payment-alert');
            alertDiv.className = `mb-4 p-4 rounded ${type === 'error' ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-green-100 text-green-700 border border-green-300'}`;
            alertDiv.textContent = message;
            alertDiv.classList.remove('hidden');
            
            setTimeout(() => {
                alertDiv.classList.add('hidden');
            }, 5000);
        }

        // ==================== STRIPE ====================

        function initStripe() {
            console.log('Inicializando Stripe...');
            stripe = Stripe(stripeKey);
            const elements = stripe.elements();
            
            cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        '::placeholder': { color: '#aab7c4' }
                    }
                }
            });
            
            cardElement.mount('#card-element');
            
            cardElement.on('change', (event) => {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        }

        async function processStripePayment() {
            const submitBtn = document.getElementById('stripe-submit');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';

            try {
                const intentResponse = await fetch('{{ route('stripe.intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order_id: orderId })
                });

                const intentData = await intentResponse.json();

                if (!intentData.success) {
                    throw new Error(intentData.message || 'Error al crear el pago');
                }

                const { error, paymentIntent } = await stripe.confirmCardPayment(
                    intentData.client_secret,
                    { payment_method: { card: cardElement } }
                );

                if (error) {
                    throw new Error(error.message);
                }

                const confirmResponse = await fetch('{{ route('stripe.confirm') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ payment_intent_id: paymentIntent.id })
                });

                const confirmData = await confirmResponse.json();

                if (confirmData.success) {
                    showAlert('¡Pago exitoso! Redirigiendo...', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route('profile') }}';
                    }, 1500);
                } else {
                    throw new Error(confirmData.message || 'Error al confirmar el pago');
                }

            } catch (error) {
                console.error('Error en Stripe:', error);
                showAlert(error.message, 'error');
                document.getElementById('card-errors').textContent = error.message;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i>Pagar ${{ number_format($summary["total"], 2) }} MXN';
            }
        }

        // ==================== PAYPAL ====================

        function initPayPal() {
            console.log('Inicializando PayPal...');
            
            // Limpiar contenedor antes de renderizar
            const container = document.getElementById('paypal-button-container');
            container.innerHTML = '';
            
            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'blue',
                    shape: 'rect',
                    label: 'paypal'
                },
                
                createOrder: async () => {
                    try {
                        console.log('Creando orden PayPal...');
                        
                        const response = await fetch('{{ route('paypal.create') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order_id: orderId })
                        });

                        const data = await response.json();
                        console.log('PayPal Response:', data);
                        
                        if (!data.success) {
                            throw new Error(data.message || 'Error al crear el pago');
                        }

                        paypalOrderId = data.paypal_order_id;
                        return data.paypal_order_id;
                        
                    } catch (error) {
                        console.error('Error al crear orden:', error);
                        showAlert('Error al crear la orden de PayPal: ' + error.message, 'error');
                        throw error;
                    }
                },
                
                onApprove: async (data) => {
                    try {
                        console.log('PayPal aprobado:', data);
                        showAlert('Procesando pago...', 'success');
                        
                        const response = await fetch('{{ route('paypal.capture') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ 
                                paypal_order_id: data.orderID 
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            showAlert('¡Pago exitoso! Redirigiendo...', 'success');
                            setTimeout(() => {
                                window.location.href = '{{ route('profile') }}';
                            }, 1500);
                        } else {
                            throw new Error(result.message || 'Error al procesar el pago');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert('Error al procesar el pago: ' + error.message, 'error');
                    }
                },
                
                onCancel: () => {
                    showAlert('Has cancelado el pago de PayPal', 'error');
                },
                
                onError: (err) => {
                    console.error('PayPal error:', err);
                    showAlert('Error al procesar el pago de PayPal', 'error');
                }
            }).render('#paypal-button-container');
        }

        // ==================== SELECTOR DE MÉTODO ====================

        function selectPaymentMethod(method) {
            console.log('Seleccionando método:', method);
            paymentMethod = method;
            
            document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                btn.classList.remove('border-blue-500', 'bg-blue-50');
                btn.classList.add('border-gray-300');
            });
            
            document.getElementById(`btn-${method}`).classList.remove('border-gray-300');
            document.getElementById(`btn-${method}`).classList.add('border-blue-500', 'bg-blue-50');
            
            document.getElementById('stripe-form').classList.add('hidden');
            document.getElementById('paypal-form').classList.add('hidden');
            
            if (method === 'stripe') {
                document.getElementById('stripe-form').classList.remove('hidden');
                if (!stripe) initStripe();
            } else if (method === 'paypal') {
                document.getElementById('paypal-form').classList.remove('hidden');
                initPayPal();
            }
        }

        // Inicializar con Stripe por defecto
        window.addEventListener('DOMContentLoaded', () => {
            console.log('DOM cargado, inicializando...');
            selectPaymentMethod('stripe');
        });

        // Actualizar dirección de la orden
async function updateOrderAddress(addressId) {
    try {
        const response = await fetch(`/orders/${orderId}/update-address`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ address_id: addressId })
        });
        
        const data = await response.json();
        if (data.success) {
            console.log('Dirección actualizada');
        }
    } catch (error) {
        console.error('Error actualizando dirección:', error);
    }
}
    </script>
</body>
</html>