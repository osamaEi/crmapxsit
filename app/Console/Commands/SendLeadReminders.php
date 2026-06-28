<?php

namespace App\Console\Commands;

use App\Mail\LeadReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendLeadReminders extends Command
{
    protected $signature = 'leads:send-reminders';

    protected $description = 'Send due lead reminder emails to assigned users';

    public function handle(): void
    {
        $due = DB::table('lead_reminders')
            ->where('sent', false)
            ->where('remind_at', '<=', now())
            ->get();

        foreach ($due as $reminder) {
            $lead = DB::table('leads')->where('id', $reminder->lead_id)->first();
            $user = DB::table('users')->where('id', $reminder->user_id)->first();

            if (! $lead || ! $user || ! $user->email) {
                DB::table('lead_reminders')->where('id', $reminder->id)->update(['sent' => true]);

                continue;
            }

            try {
                Mail::to($user->email)->send(new LeadReminderMail($reminder, $lead, $user));
            } catch (\Throwable $e) {
                $this->error('Failed to send reminder #'.$reminder->id.': '.$e->getMessage());
            }

            DB::table('lead_reminders')->where('id', $reminder->id)->update(['sent' => true]);
        }

        $this->info("Sent {$due->count()} reminder(s).");
    }
}
