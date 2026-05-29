<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de pedido</title>
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
                <!-- Body -->
                <tr>
                    <td style="padding:40px;">
                        <h2 style="color:#1a1a2e;margin:0 0 8px;">¡Gracias por tu pedido, {{ $customerName }}!</h2>
                        <p style="color:#555;line-height:1.7;margin:0 0 24px;">
                            Hemos recibido tu pedido y lo estamos procesando. Te notificaremos cuando haya novedades.
                        </p>

                        <!-- Order number -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="color:#777;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Número de pedido</td>
                                <td align="right" style="color:#e94560;font-weight:bold;font-size:16px;">{{ $order->orderNumber }}</td>
                            </tr>
                        </table>

                        <!-- Items -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8e8e8;border-radius:6px;overflow:hidden;margin-bottom:24px;">
                            <tr style="background-color:#f8f8f8;">
                                <td style="padding:10px 16px;font-size:12px;color:#777;text-transform:uppercase;letter-spacing:1px;">Producto</td>
                                <td align="center" style="padding:10px 16px;font-size:12px;color:#777;text-transform:uppercase;letter-spacing:1px;">Uds.</td>
                                <td align="right" style="padding:10px 16px;font-size:12px;color:#777;text-transform:uppercase;letter-spacing:1px;">Precio</td>
                                <td align="right" style="padding:10px 16px;font-size:12px;color:#777;text-transform:uppercase;letter-spacing:1px;">Subtotal</td>
                            </tr>
                            @foreach($order->items as $item)
                            <tr style="border-top:1px solid #e8e8e8;">
                                <td style="padding:12px 16px;">
                                    <span style="color:#1a1a2e;font-weight:bold;">{{ $item->productSnapshot['artist'] }}</span><br>
                                    <span style="color:#555;font-size:13px;">{{ $item->productSnapshot['album_title'] }}</span>
                                </td>
                                <td align="center" style="padding:12px 16px;color:#555;">{{ $item->quantity }}</td>
                                <td align="right" style="padding:12px 16px;color:#555;white-space:nowrap;">{{ number_format($item->pricePerUnit, 2, ',', '.') }} €</td>
                                <td align="right" style="padding:12px 16px;color:#333;font-weight:bold;white-space:nowrap;">{{ number_format($item->subtotal, 2, ',', '.') }} €</td>
                            </tr>
                            @endforeach
                        </table>

                        <!-- Totals -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="padding:4px 0;color:#555;">Subtotal</td>
                                <td align="right" style="padding:4px 0;color:#333;">{{ number_format($order->subtotal, 2, ',', '.') }} €</td>
                            </tr>
                            @if($order->discountAmount > 0)
                            <tr>
                                <td style="padding:4px 0;color:#27ae60;">Descuento VIP (15%)</td>
                                <td align="right" style="padding:4px 0;color:#27ae60;">−{{ number_format($order->discountAmount, 2, ',', '.') }} €</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding:4px 0;color:#555;">Envío</td>
                                <td align="right" style="padding:4px 0;color:#333;">
                                    @if($order->shippingCost == 0)
                                        <span style="color:#27ae60;">Gratis</span>
                                    @else
                                        {{ number_format($order->shippingCost, 2, ',', '.') }} €
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0;color:#555;">IVA (21%)</td>
                                <td align="right" style="padding:4px 0;color:#333;">{{ number_format($order->taxAmount, 2, ',', '.') }} €</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 0 4px;color:#1a1a2e;font-weight:bold;font-size:16px;border-top:2px solid #e8e8e8;">Total</td>
                                <td align="right" style="padding:12px 0 4px;color:#e94560;font-weight:bold;font-size:18px;border-top:2px solid #e8e8e8;">{{ number_format($order->totalAmount, 2, ',', '.') }} €</td>
                            </tr>
                        </table>

                        <!-- Shipping & Payment -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;border:1px solid #e8e8e8;border-radius:6px;overflow:hidden;">
                            <tr style="background-color:#f8f8f8;">
                                <td style="padding:12px 16px;font-size:12px;color:#777;text-transform:uppercase;letter-spacing:1px;" width="50%">Dirección de envío</td>
                                <td style="padding:12px 16px;font-size:12px;color:#777;text-transform:uppercase;letter-spacing:1px;border-left:1px solid #e8e8e8;" width="50%">Método de pago</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 16px;color:#555;line-height:1.6;vertical-align:top;">
                                    {{ $shippingAddress['street'] }}, {{ $shippingAddress['street_number'] }}
                                    @if($shippingAddress['apartment'])
                                        , {{ $shippingAddress['apartment'] }}
                                    @endif
                                    <br>
                                    {{ $shippingAddress['postal_code'] }} {{ $shippingAddress['city'] }}<br>
                                    {{ $shippingAddress['state_province'] }}, {{ $shippingAddress['country'] }}
                                </td>
                                <td style="padding:12px 16px;color:#555;vertical-align:top;border-left:1px solid #e8e8e8;">
                                    {{ $paymentSummary }}
                                </td>
                            </tr>
                        </table>

                        @if($order->customerNotes)
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;border:1px solid #e8e8e8;border-radius:6px;overflow:hidden;">
                            <tr style="background-color:#f8f8f8;">
                                <td style="padding:12px 16px;font-size:12px;color:#777;text-transform:uppercase;letter-spacing:1px;">Notas del pedido</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 16px;color:#555;line-height:1.6;">{{ $order->customerNotes }}</td>
                            </tr>
                        </table>
                        @endif

                        <p style="color:#555;line-height:1.7;margin:0 0 24px;">
                            Puedes consultar el estado de tu pedido en cualquier momento desde tu cuenta.
                        </p>
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
                            © {{ date('Y') }} Discomania — Vinilos & Música. Todos los derechos reservados.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
