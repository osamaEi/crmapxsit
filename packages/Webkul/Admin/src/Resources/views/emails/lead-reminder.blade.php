<x-admin::emails.layout>
    <div style="background:#f8fafc;border-radius:8px;padding:24px;margin-bottom:24px;">
        <h2 style="margin:0 0 8px 0;font-size:20px;font-weight:700;color:#1e293b;">
            ⏰ Lead Reminder
        </h2>
        <p style="margin:0;font-size:14px;color:#64748b;">
            You have a reminder for the following lead.
        </p>
    </div>

    <table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
        <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;font-size:13px;color:#64748b;width:140px;">Lead</td>
            <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;font-size:14px;font-weight:600;color:#1e293b;">
                {{ $lead->title }}
            </td>
        </tr>
        @if ($reminder->stage_name)
        <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;font-size:13px;color:#64748b;">Stage</td>
            <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;font-size:14px;color:#1e293b;">
                {{ $reminder->stage_name }}
            </td>
        </tr>
        @endif
        <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;font-size:13px;color:#64748b;">Reminder Time</td>
            <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;font-size:14px;color:#1e293b;">
                {{ \Carbon\Carbon::parse($reminder->remind_at)->format('d M Y, h:i A') }}
            </td>
        </tr>
        @if ($reminder->comment)
        <tr>
            <td style="padding:10px 0;font-size:13px;color:#64748b;vertical-align:top;">Comment</td>
            <td style="padding:10px 0;font-size:14px;color:#1e293b;">
                {{ $reminder->comment }}
            </td>
        </tr>
        @endif
    </table>

    <a
        href="{{ route('admin.leads.view', $lead->id) }}"
        style="display:inline-block;background:#0E90D9;color:#ffffff;padding:12px 24px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:600;"
    >
        View Lead
    </a>
</x-admin::emails.layout>
