<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido/a a Discomania!</title>
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
                        <h2 style="color:#1a1a2e;margin:0 0 16px;">¡Bienvenido/a, {{ $customerName }}!</h2>
                        <p style="color:#555;line-height:1.7;margin:0 0 16px;">
                            Tu cuenta en <strong>Discomania</strong> ha sido creada correctamente.
                            Ya puedes explorar nuestro catálogo de vinilos, CDs y merchandising.
                        </p>
                        <p style="color:#555;line-height:1.7;margin:0 0 24px;">
                            Si tienes cualquier pregunta, no dudes en ponerte en contacto con nosotros.
                        </p>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="background-color:#e94560;border-radius:4px;padding:12px 28px;">
                                    <a href="{{ config('app.url') }}" style="color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;">
                                        Ir a la tienda
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
