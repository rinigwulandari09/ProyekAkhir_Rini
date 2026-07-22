<div style="font-family: 'Segoe UI', Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="background-color: #214122; padding: 20px; text-align: center;">
        <h2 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px;">Pemberitahuan Pengingat</h2>
    </div>

    <div style="padding: 24px; background-color: #ffffff; line-height: 1.6;">
        @if(!empty($judul))
        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 18px; color: #214122;">
            {{ $judul }}
        </h3>
        @endif

        <p style="margin-top: 0; margin-bottom: 20px; font-size: 15px; color: #444;">
            {{ $pesan }}
        </p>

        @if(!empty($deadline))
        <div style="background-color: #f9fbf9; border-left: 4px solid #214122; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 4px 4px 0;">
            <span style="font-size: 13px; color: #666; display: block; text-transform: uppercase; font-weight: bold; margin-bottom: 2px;">Tanggal Pelaksanaan / Deadline:</span>
            <strong style="font-size: 16px; color: #214122;">{{ \Carbon\Carbon::parse($deadline)->translatedFormat('d F Y') }}</strong>
        </div>
        @endif
    </div>

    <div style="background-color: #f5f5f5; padding: 16px 24px; border-top: 1px solid #e0e0e0; font-size: 13px; color: #666; text-align: center;">
        <p style="margin: 0 0 4px 0;">Email ini dikirim otomatis oleh <strong>Sistem Pengingat Superadmin</strong>.</p>
        <p style="margin: 0; font-size: 11px; color: #999;">Mohon tidak membalas email ini secara langsung.</p>
    </div>
</div>