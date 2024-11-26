<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingStatusUpdated extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Determine the channels the notification should be sent through.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Send via database and email
    }

    /**
     * Get the data to be stored in the database notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'status' => $this->booking->status,
            'payment_status' => $this->booking->payment_status,
            'message' => 'The booking status and payment status have been updated.',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Booking Status Updated')
                    ->line('The status and payment status of your booking have been updated.')
                    ->line('Booking ID: ' . $this->booking->id)
                    ->line('New Status: ' . $this->booking->status)
                    ->line('Payment Status: ' . $this->booking->payment_status)
                    ->line('Thank you for using our service!');
    }
}
