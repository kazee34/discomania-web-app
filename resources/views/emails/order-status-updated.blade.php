<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de tu pedido</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4;padding:30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                <!-- Header -->
                <tr>
                    <td style="background-color:#1a1a2e;padding:24px 40px;text-align:center;">
                        <img src="{{ asset('images/discomania_logo.jpg') }}" alt="Discomania" style="max-height:64px;width:auto;display:inline-block;">
                    </td>
                </tr>

                @php
                    $statusMessages = [
                        'processing' => ['title' => 'Tu pedido está en preparación', 'desc' => 'Estamos preparando tu pedido. En breve lo enviaremos.', 'color' => '#f59e0b'],
                        'shipped'    => ['title' => 'Tu pedido ha sido enviado', 'desc' => 'Tu pedido está en camino. Pronto lo recibirás en la dirección indicada.', 'color' => '#3b82f6'],
                        'delivered'  => ['title' => '¡Tu pedido ha sido entregado!', 'desc' => 'Esperamos que disfrutes tu compra. Gracias por confiar en Discomania.', 'color' => '#22c55e'],
                        'cancelled'  => ['title' => 'Tu pedido ha sido cancelado', 'desc' => 'Tu pedido ha sido cancelado. Se procesará el reembolso de <strong>' . number_format($totalAmount, 2, ',', '.') . ' €</strong> en los próximos días hábiles según el método de pago utilizado.', 'color' => '#ef4444'],
                    ];
                    $info = $statusMessages[$newStatus->value] ?? ['title' => 'Estado actualizado', 'desc' => 'El estado de tu pedido ha cambiado.', 'color' => '#6b7280'];
                @endphp

                <!-- Status badge -->
                <tr>
                    <td style="padding:24px 40px 0;text-align:center;">
                        <span style="display:inline-block;background-color:{{ $info['color'] }};color:#fff;border-radius:20px;padding:6px 20px;font-size:13px;font-weight:bold;text-transform:uppercase;letter-spacing:1px;">
                            {{ $newStatus->value }}
                        </span>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:24px 40px 40px;">
                        <h2 style="color:#1a1a2e;margin:0 0 8px;">{{ $info['title'] }}</h2>
                        <p style="color:#777;margin:0 0 4px;font-size:13px;">Hola, {{ $customerName }}</p>
                        <p style="color:#555;line-height:1.7;margin:0 0 24px;">{!! $info['desc'] !!}</p>

                        <!-- Order reference -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f8f8;border-radius:6px;border:1px solid #e8e8e8;margin-bottom:24px;">
                            <tr>
                                <td style="padding:16px 24px;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="color:#333;font-weight:bold;">Pedido</td>
                                            <td align="right" style="color:#e94560;font-weight:bold;">{{ $orderNumber }}</td>
                                        </tr>
                                        @if($newStatus->value !== 'cancelled')
                                        <tr>
                                            <td style="color:#333;font-weight:bold;padding-top:6px;">Total</td>
                                            <td align="right" style="color:#333;padding-top:6px;">{{ number_format($totalAmount, 2, ',', '.') }} €</td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="background-color:#e94560;border-radius:4px;padding:12px 28px;">
                                    <a href="{{ config('app.url') }}" style="color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;">
                                        Ver mis pedidos
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Footer -->
                <tr>
                    <td style="background-color:#f8f8f8;padding:20px 40px;text-align:center;border-top:1px solid #e8e8e8;">
                        <p style="color:#999;font-size:12px;margin:0;">
                            © {{ date('Y') }} Discomania. Todos los derechos reservados.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
