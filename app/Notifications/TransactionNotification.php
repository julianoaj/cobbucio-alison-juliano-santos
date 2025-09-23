<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Transaction $transaction, public string $event = 'created')
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $t = $this->transaction;
        $amount = number_format((float) $t->amount, 2, ',', '.');

        $description = $this->transaction->to_user_id === $notifiable->id
            ? "Você recebeu uma transferência no valor de R$ {$amount} do {$this->transaction->user->name}."
            : 'Você realizou uma transação.';

        return (new MailMessage())
            ->subject("{$t->type} — {$this->event}")
            ->greeting("Olá {$notifiable->name},")
            ->line($description)
            ->action('Ver transação', url(route('wallet.show', $t->id)))
            ->salutation('Atenciosamente,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
