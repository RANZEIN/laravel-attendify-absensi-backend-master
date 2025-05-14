?php

namespace App\Services;

use App\Models\Broadcast;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class PushNotificationService
{
    /**
     * Send push notification for a broadcast
     *
     * @param Broadcast $broadcast
     * @return void
     */
    public function sendBroadcastNotification(Broadcast $broadcast)
    {
        Log::info('Sending push notifications for broadcast: ' . $broadcast->id);

        // Get recipients based on broadcast settings
        $recipients = $broadcast->send_to_all
            ? $this->getAllUsersDevices()
            : $this->getSelectedUsersDevices($broadcast);

        if (empty($recipients)) {
            Log::info('No recipients with registered devices found');
            return;
        }

        // Group tokens by device type (Android/iOS)
        $androidTokens = [];
        $iosTokens = [];

        foreach ($recipients as $device) {
            if ($device->device_type === 'android') {
                $androidTokens[] = $device->device_token;
            } else {
                $iosTokens[] = $device->device_token;
            }
        }

        // Send to Android devices
        if (!empty($androidTokens)) {
            $this->sendToFCM($androidTokens, $broadcast, 'android');
        }

        // Send to iOS devices
        if (!empty($iosTokens)) {
            $this->sendToFCM($iosTokens, $broadcast, 'ios');
        }
    }

    /**
     * Get all user devices
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAllUsersDevices()
    {
        return UserDevice::where('device_token', '!=', null)
            ->where('device_token', '!=', '')
            ->get();
    }

    /**
     * Get devices for selected users
     *
     * @param Broadcast $broadcast
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSelectedUsersDevices(Broadcast $broadcast)
    {
        return UserDevice::whereIn('user_id', $broadcast->recipients->pluck('id'))
            ->where('device_token', '!=', null)
            ->where('device_token', '!=', '')
            ->get();
    }

    /**
     * Send notification to FCM
     *
     * @param array $tokens
     * @param Broadcast $broadcast
     * @param string $platform
     * @return void
     */
    private function sendToFCM(array $tokens, Broadcast $broadcast, string $platform)
    {
        // Batch tokens in groups of 1000 (FCM limit)
        $tokenBatches = array_chunk($tokens, 1000);

        foreach ($tokenBatches as $tokenBatch) {
            $fileUrl = null;
            if ($broadcast->file_path) {
                $fileUrl = url(\Storage::url($broadcast->file_path));
            }

            $payload = [
                'registration_ids' => $tokenBatch,
                'notification' => [
                    'title' => $broadcast->title,
                    'body' => $broadcast->message,
                    'sound' => 'default',
                ],
                'data' => [
                    'broadcast_id' => $broadcast->id,
                    'title' => $broadcast->title,
                    'message' => $broadcast->message,
                    'type' => 'broadcast',
                    'file_url' => $fileUrl,
                    'file_name' => $broadcast->file_name,
                    'file_type' => $broadcast->file_type,
                    'created_at' => $broadcast->created_at->toIso8601String(),
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                ]
            ];

            // Add platform specific configurations
            if ($platform === 'ios') {
                $payload['notification']['sound'] = 'default';
                $payload['content_available'] = true;
                $payload['mutable_content'] = true;
                $payload['priority'] = 'high';
            }

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'key=' . config('services.fcm.key'),
                    'Content-Type' => 'application/json'
                ])->post('https://fcm.googleapis.com/fcm/send', $payload);

                Log::info('FCM Response: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('FCM Error: ' . $e->getMessage());
            }
        }
    }
}
